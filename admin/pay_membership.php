<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/functions.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

/* FETCH MEMBERS WITH PENDING MEMBERSHIP */
$members = $pdo->query("
    SELECT m.id as membership_id, u.name, u.email, d.title, d.fee
    FROM memberships m
    JOIN users u ON m.user_id = u.id
    JOIN designations d ON m.designation_id = d.id
    WHERE m.status = 'expired'
")->fetchAll();
?>

<!-- Main Content Area Container -->
<div class="space-y-6">

    <!-- Header Block -->
    <header class="border-b border-slate-200 pb-5">
        <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Cash Collection Desk</span>
        <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Membership Payments</h1>
    </header>

    <!-- Action Log Status Banners -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (!verifyToken($_POST['csrf_token'])) {
            die("Invalid CSRF Token");
        }

        $membership_id = $_POST['membership_id'];

        /* GET MEMBERSHIP DETAILS */
        $stmt = $pdo->prepare("
            SELECT m.*, u.id as user_id, d.fee 
            FROM memberships m
            JOIN users u ON m.user_id = u.id
            JOIN designations d ON m.designation_id = d.id
            WHERE m.id = ?
        ");
        $stmt->execute([$membership_id]);
        $data = $stmt->fetch();

        if ($data) {

            $amount = $data['fee'];
            $user_id = $data['user_id'];

            /* 1. CREATE TRANSACTION */
            $txn_id = 'TXN' . time();

            $stmt = $pdo->prepare("
                INSERT INTO transactions 
                (user_id, type, reference_id, amount, payment_method, status, transaction_id)
                VALUES (?, 'membership', ?, ?, 'cash', 'success', ?)
            ");
            $stmt->execute([$user_id, $membership_id, $amount, $txn_id]);

            /* 2. ACTIVATE MEMBERSHIP */
            $stmt = $pdo->prepare("
                UPDATE memberships 
                SET status = 'active'
                WHERE id = ?
            ");
            $stmt->execute([$membership_id]);

            /* 3. CREATE RECEIPT */
            $receipt_no = 'RCPT' . time();

            $stmt = $pdo->prepare("
                INSERT INTO receipts 
                (user_id, type, reference_id, receipt_no, qr_code, pdf_path)
                VALUES (?, 'membership', ?, ?, '', '')
            ");
            $stmt->execute([$user_id, $membership_id, $receipt_no]);

            logAdminAction($pdo, $_SESSION['user_id'], "Membership payment done for user ID: $user_id");

            echo '
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
                <span class="text-base">✓</span> Payment Successful & Membership Activated
            </div>';
            
            // Refresh dataset to clear the updated user from the dropdown list
            $members = $pdo->query("
                SELECT m.id as membership_id, u.name, u.email, d.title, d.fee
                FROM memberships m
                JOIN users u ON m.user_id = u.id
                JOIN designations d ON m.designation_id = d.id
                WHERE m.status = 'expired'
            ")->fetchAll();
        }
    }
    ?>

    <!-- Form Processing Panel -->
    <div class="max-w-2xl bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm">
        <form method="POST" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?php echo generateToken(); ?>">

            <div>
                <label for="membership_id" class="block text-xs font-bold tracking-wider text-slate-500 uppercase mb-2">
                    Select Member Account
                </label>
                <div class="relative">
                    <select name="membership_id" id="membership_id" required class="w-full bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-lg px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:bg-white transition-all appearance-none cursor-pointer">
                        <?php if (empty($members)): ?>
                            <option value="" disabled selected>No expired or pending accounts available</option>
                        <?php else: ?>
                            <option value="" disabled selected>Choose an account to process cash renewal...</option>
                            <?php foreach ($members as $m): ?>
                                <option value="<?php echo $m['membership_id']; ?>">
                                    <?php echo htmlspecialchars($m['name']); ?> (<?php echo htmlspecialchars($m['title']); ?> — ₹<?php echo $m['fee']; ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                        <span class="text-xs">▼</span>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5 font-normal">Only users with an 'expired' status profile are populated in this registration block.</p>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-4">
                <span class="text-xs text-slate-400 font-normal">
                    Processing a direct cash receipt generates an immediate <strong>TXN code</strong>.
                </span>
                <button type="submit" <?php echo empty($members) ? 'disabled' : ''; ?> class="bg-slate-900 hover:bg-slate-800 disabled:bg-slate-200 disabled:text-slate-400 text-white font-medium text-xs tracking-wider uppercase px-5 py-3 rounded-lg transition-colors shadow-sm whitespace-nowrap">
                    Mark as Paid
                </button>
            </div>
        </form>
    </div>

</div>

</div> <!-- Closes inner sidebar layout padding container -->
</div> <!-- Closes outer fixed sidebar layout grid width container -->

<?php require '../includes/footer.php'; ?>