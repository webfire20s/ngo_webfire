<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/functions.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

/* FETCH PENDING PAYMENTS */
$payments = $pdo->query("
    SELECT t.*, u.name, m.status as membership_status
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    JOIN memberships m ON t.reference_id = m.id
    WHERE t.status = 'pending'
")->fetchAll();
?>

<!-- Main Content Area Container -->
<div class="space-y-6">
    
    <!-- Header Block -->
    <header class="border-b border-slate-200 pb-5">
        <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Verification Queue</span>
        <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Pending Payments</h1>
    </header>

    <!-- Action Log Status Banners -->
    <?php
    /* APPROVAL LOGIC */
    if (isset($_GET['approve'])) {

        $id = (int) $_GET['approve'];

        try {
            $pdo->beginTransaction();

            /* LOCK TRANSACTION ROW */
            $stmt = $pdo->prepare("SELECT * FROM transactions WHERE id=? FOR UPDATE");
            $stmt->execute([$id]);
            $txn = $stmt->fetch();

            if (!$txn) {
                throw new Exception("Transaction not found");
            }

            /* VALIDATION */
            if ($txn['status'] !== 'pending') {
                throw new Exception("Already processed");
            }

            if ($txn['amount'] <= 0) {
                throw new Exception("Invalid amount");
            }

            /* CHECK MEMBERSHIP */
            $stmt = $pdo->prepare("SELECT * FROM memberships WHERE id=? FOR UPDATE");
            $stmt->execute([$txn['reference_id']]);
            $membership = $stmt->fetch();

            if (!$membership) {
                throw new Exception("Membership not found");
            }

            if ($membership['status'] === 'active') {
                throw new Exception("Membership already active");
            }

            /* UPDATE TRANSACTION */
            $pdo->prepare("
                UPDATE transactions 
                SET status='success' 
                WHERE id=?
            ")->execute([$id]);

            /* ACTIVATE MEMBERSHIP */
            $pdo->prepare("
                UPDATE memberships 
                SET status='active' 
                WHERE id=?
            ")->execute([$txn['reference_id']]);

            /* CREATE RECEIPT */
            $receipt_no = 'RCPT' . time();

            $pdo->prepare("
                INSERT INTO receipts (user_id, type, reference_id, receipt_no)
                VALUES (?, 'membership', ?, ?)
            ")->execute([$txn['user_id'], $txn['reference_id'], $receipt_no]);

            /* LOG ACTION */
            logAdminAction($pdo, $_SESSION['user_id'], "Approved payment TXN ID: " . $txn['id']);

            $pdo->commit();

            echo '
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
                <span class="text-base">✓</span> Payment Approved Successfully
            </div>';

        } catch (Exception $e) {

            $pdo->rollBack();
            echo '
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
                <span class="text-base">✕</span> Error: ' . $e->getMessage() . '
            </div>';
        }
    }

    if (isset($_GET['reject'])) {

        $id = (int) $_GET['reject'];

        $stmt = $pdo->prepare("UPDATE transactions SET status='failed' WHERE id=? AND status='pending'");
        $stmt->execute([$id]);

        echo '
        <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
            <span class="text-base">⚠</span> Payment Request Rejected
        </div>';
    }
    ?>

    <!-- Ledger Table Wrapper -->
    <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-400 font-bold">
                        <th class="py-3 px-6">User</th>
                        <th class="py-3 px-6">UTR / Txn ID</th>
                        <th class="py-3 px-6">Amount</th>
                        <th class="py-3 px-6">Method</th>
                        <th class="py-3 px-6">Proof File</th>
                        <th class="py-3 px-6">Memb. Status</th>
                        <th class="py-3 px-6 text-right">Verification Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    <?php if (empty($payments)): ?>
                        <tr>
                            <td colspan="7" class="py-8 px-6 text-center text-slate-400 italic">No pending payments currently require approval.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($payments as $p): ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            <!-- User Identity -->
                            <td class="py-4 px-6 font-medium text-slate-900">
                                <?php echo htmlspecialchars($p['name']); ?>
                            </td>
                            
                            <!-- Transaction UTR -->
                            <td class="py-4 px-6 font-mono text-xs text-slate-700 select-all">
                                <?php echo htmlspecialchars($p['transaction_id']); ?>
                            </td>
                            
                            <!-- Financial Amount -->
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                ₹<?php echo $p['amount']; ?>
                            </td>
                            
                            <!-- Payment Channel -->
                            <td class="py-4 px-6">
                                <span class="font-mono text-[10px] bg-slate-100 border border-slate-200 px-2 py-0.5 rounded text-slate-600 font-bold">
                                    <?php echo strtoupper($p['payment_method']); ?>
                                </span>
                            </td>

                            <!-- Attachment Check -->
                            <td class="py-4 px-6">
                                <?php if (!empty($p['proof'])): ?>
                                    <a href="../uploads/payments/<?php echo $p['proof']; ?>" target="_blank" class="inline-flex items-center gap-1 text-xs text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-2 py-1 rounded transition-colors shadow-sm font-medium">
                                        View Proof ↗
                                    </a>
                                <?php else: ?>
                                    <span class="text-xs text-slate-400 italic">No Proof</span>
                                <?php endif; ?>
                            </td>

                            <!-- Membership Current Context Status -->
                            <td class="py-4 px-6">
                                <span class="inline-flex text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200/60">
                                    <?php echo $p['membership_status']; ?>
                                </span>
                            </td>

                            <!-- Workflow Actions Block -->
                            <td class="py-4 px-6 text-right text-xs font-semibold space-x-2 whitespace-nowrap">
                                <a href="?approve=<?php echo $p['id']; ?>" onclick="return confirm('Approve this payment?')" class="inline-flex text-emerald-700 hover:text-white hover:bg-emerald-600 border border-emerald-200 hover:border-emerald-600 px-2.5 py-1.5 rounded transition-all shadow-sm">
                                    Approve
                                </a>
                                <a href="?reject=<?php echo $p['id']; ?>" onclick="return confirm('Reject this payment?')" class="inline-flex text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-200 hover:border-rose-600 px-2.5 py-1.5 rounded transition-all shadow-sm">
                                    Reject
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</div> <!-- Closes inner sidebar layout padding container -->
</div> <!-- Closes outer fixed sidebar layout grid width container -->

<?php require '../includes/footer.php'; ?>