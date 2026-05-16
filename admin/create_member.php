<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/functions.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

/* FETCH DESIGNATIONS */
$designations = $pdo->query("SELECT * FROM designations")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!verifyToken($_POST['csrf_token'])) {
        die("Invalid CSRF Token");
    }

    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $designation_id = $_POST['designation_id'];
    $referral = sanitize($_POST['referral'] ?? '');

    /* CHECK DUPLICATE EMAIL */
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetch()) {
        echo '
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
            <span class="text-base">✕</span> Registration Error: Email address already exists inside the database registry.
        </div>';
    } else {

        /* CREATE USER */
        $password = password_hash("123456", PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, phone, password, role)
            VALUES (?, ?, ?, ?, 'member')
        ");
        $stmt->execute([$name, $email, $phone, $password]);

        $user_id = $pdo->lastInsertId();

        /* HANDLE REFERRAL */
        $referred_by = null;
        if (!empty($referral)) {
            $refCheck = $pdo->prepare("SELECT user_id FROM memberships WHERE referral_code = ?");
            $refCheck->execute([$referral]);
            $refUser = $refCheck->fetch();
            if ($refUser) {
                $referred_by = $refUser['user_id'];
            }
        }

        /* GENERATE REFERRAL CODE */
        $referral_code = strtoupper(substr(md5(uniqid()), 0, 8));

        /* CREATE MEMBERSHIP (PENDING) */
        $stmt = $pdo->prepare("
            INSERT INTO memberships 
            (user_id, designation_id, join_date, expiry_date, status, referral_code, referred_by)
            VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 'expired', ?, ?)
        ");
        $stmt->execute([
            $user_id,
            $designation_id,
            $referral_code,
            $referred_by
        ]);

        logAdminAction($pdo, $_SESSION['user_id'], "Created member: $email");

        echo '
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
            <span class="text-base">✓</span> Member baseline account generated successfully. Status set to: Pending Payment.
        </div>';
    }
}
?>

<!-- Main Content Area Container -->
<div class="space-y-6 max-w-4xl">

    <!-- Header Block -->
    <header class="border-b border-slate-200 pb-5">
        <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Onboarding Framework</span>
        <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Create Member</h1>
    </header>

    <!-- User Registration Input Card -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm p-6 sm:p-8">
        <form method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo generateToken(); ?>">
            
            <!-- Dual Column Layout: Personal Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Full Legal Name Input -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Full Legal Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" required 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                           placeholder="Name">
                </div>

                <!-- Primary Email Communications Address -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Primary Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" required 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                           placeholder="email@domain.com">
                </div>

            </div>

            <!-- Dual Column Layout: Categorization Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Telephone / Contact Number -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Mobile / Telephone Number <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="phone" required 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                           placeholder="+91 XXXXX XXXXX">
                </div>

                <!-- Strategic Affiliation Level Assignment -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Tier Designation Rank <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="designation_id" required
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all appearance-none shadow-inner cursor-pointer">
                            <?php foreach ($designations as $d): ?>
                                <option value="<?php echo $d['id']; ?>">
                                    <?php echo htmlspecialchars($d['title']); ?> (₹<?php echo number_format($d['fee'], 2); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                            <span class="text-xs">▼</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Optional Referral Track Framework -->
            <div class="max-w-md">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Inbound Affiliate Referral Code <span class="text-slate-400 font-normal lowercase italic">(optional)</span>
                </label>
                <input type="text" name="referral" 
                       class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-mono tracking-wider text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400 uppercase"
                       placeholder="e.g., A8F3B9D1">
            </div>

            <!-- Execution Actions Footbar -->
            <div class="pt-5 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs text-slate-400">
                    * Default fallback initialization password will map securely to <span class="font-mono bg-slate-50 border border-slate-200 px-1.5 py-0.5 rounded text-slate-600">123456</span>
                </p>
                <button type="submit" 
                        class="px-6 py-2.5 text-sm font-bold tracking-wide uppercase text-white bg-slate-900 hover:bg-slate-800 active:bg-black rounded-lg transition-colors duration-150 shadow-md">
                    Register Member
                </button>
            </div>

        </form>
    </div>

</div>

</div> <!-- Closes inner sidebar layout padding container -->
</div> <!-- Closes outer fixed sidebar layout grid width container -->

<?php require '../includes/footer.php'; ?>