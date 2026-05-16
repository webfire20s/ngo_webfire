<?php
require '../includes/middleware_user.php';
require '../includes/db.php';
require '../includes/header.php';

$user_id = $_SESSION['user_id'];

/* GET USER INFO */
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

/* GET MEMBERSHIP */
$stmt = $pdo->prepare("
    SELECT m.*, d.title 
    FROM memberships m
    JOIN designations d ON m.designation_id = d.id
    WHERE m.user_id = ?
    ORDER BY m.id DESC LIMIT 1
");
$stmt->execute([$user_id]);
$membership = $stmt->fetch();

/* COUNT REFERRALS */
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM memberships 
    WHERE referred_by = ?
");
$stmt->execute([$user_id]);
$total_referrals = $stmt->fetchColumn();

/* GET TRANSACTIONS */
$stmt = $pdo->prepare("
    SELECT amount, payment_method, created_at 
    FROM transactions 
    WHERE user_id = ? AND type = 'membership'
");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>

<!-- Tailwind CSS Lightweight CDN Link -->
<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-[#f8fafc] text-[#334155] font-sans antialiased p-4 md:p-8">
    <div class="max-w-6xl mx-auto space-y-8">
        
        <!-- Header / Top Bar -->
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 pb-6 gap-4">
            <div>
                <span class="text-xs font-semibold tracking-widest text-slate-400 uppercase">User Dashboard</span>
                <h1 class="text-2xl sm:text-3xl font-light tracking-tight text-slate-900 mt-1">
                    Welcome, <span class="font-semibold"><?php echo $user['name']; ?></span>
                </h1>
            </div>
            <div>
                <a href="../admin/logout.php" class="inline-flex items-center text-xs font-medium text-slate-600 hover:text-rose-600 border border-slate-200 hover:border-rose-200 bg-white hover:bg-rose-50 px-4 py-2 rounded-lg shadow-sm transition-all duration-300">
                    Logout
                </a>
            </div>
        </header>

        <!-- Main Workspace Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left Column: Account Profile & Controls -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Membership Details Card -->
                <div class="bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm">
                    <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase mb-4">Membership Details</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                            <span class="text-slate-500 text-sm">Status</span>
                            <span class="text-[11px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full <?php echo ($membership['status'] == 'active') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'; ?>">
                                <?php echo $membership['status']; ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                            <span class="text-slate-500 text-sm">Designation</span>
                            <span class="text-slate-800 font-semibold text-sm"><?php echo $membership['title']; ?></span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 text-sm">Referral Code</span>
                            <span class="bg-slate-50 px-2 py-1 rounded text-slate-700 font-mono text-xs border border-slate-200">
                                <?php echo $membership['referral_code']; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Interactive Action Cards (Based on Active/Inactive logic) -->
                <?php if ($membership['status'] != 'active'): ?>
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 shadow-sm">
                        <h3 class="text-xs font-bold tracking-wider text-amber-800 uppercase mb-2">Activate Membership</h3>
                        <p class="text-slate-600 text-sm mb-4 leading-relaxed">Your membership is currently inactive. Please complete your payment to unlock full features.</p>

                        <form method="POST" action="../pay.php">
                            <input type="hidden" name="membership_id" value="<?php echo $membership['id']; ?>">
                            <a href="../upi_payment.php?membership_id=<?php echo $membership['id']; ?>" class="block text-center bg-amber-600 hover:bg-amber-500 text-white text-sm font-medium py-2.5 px-4 rounded-lg transition-colors duration-300 shadow-md shadow-amber-600/10">
                                Pay via UPI / QR
                            </a>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if ($membership['status'] == 'active'): ?>
                    <div class="bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm space-y-3">
                        <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase mb-2">Credentials</h3>
                        
                        <a href="../generate_certificate.php" target="_blank" class="flex items-center justify-between group bg-slate-50 hover:bg-slate-100/80 border border-slate-200 text-sm text-slate-700 hover:text-slate-900 px-4 py-3 rounded-lg transition-all duration-200">
                            <span>Download Certificate</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform duration-200">→</span>
                        </a>

                        <a href="../generate_id_card.php" target="_blank" class="flex items-center justify-between group bg-slate-50 hover:bg-slate-100/80 border border-slate-200 text-sm text-slate-700 hover:text-slate-900 px-4 py-3 rounded-lg transition-all duration-200">
                            <span>Download ID Card</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform duration-200">→</span>
                        </a>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Right Column: Network Stats & Ledger -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Referral Insights Row -->
                <div class="bg-white border border-slate-200/80 rounded-xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                    <div>
                        <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Network Stats</h3>
                        <p class="text-slate-500 text-sm mt-1">Total network growth generated through your referral chain.</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 px-5 py-3 rounded-xl min-w-[140px] text-center">
                        <span class="block text-2xl font-semibold text-slate-900"><?php echo $total_referrals; ?></span>
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Members Referred</span>
                    </div>
                </div>

                <!-- Ledger Container -->
                <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Payment History</h3>
                        <a href="../generate_receipt.php?id=<?php echo $membership['id']; ?>" class="inline-flex items-center text-xs font-medium text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-3 py-1.5 rounded transition-colors duration-200">
                            Download Receipt
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] uppercase tracking-wider text-slate-400">
                                    <th class="py-3 px-6 font-bold">Amount</th>
                                    <th class="py-3 px-6 font-bold">Method</th>
                                    <th class="py-3 px-6 font-bold text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                                <?php if (empty($transactions)): ?>
                                    <tr>
                                        <td colspan="3" class="py-8 px-6 text-center text-slate-400 italic">No transactions found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transactions as $t): ?>
                                    <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                                        <td class="py-4 px-6 font-semibold text-slate-900">₹<?php echo $t['amount']; ?></td>
                                        <td class="py-4 px-6">
                                            <span class="font-mono text-xs bg-slate-100 px-2 py-0.5 border border-slate-200 rounded text-slate-600">
                                                <?php 
                                                    echo $t['payment_method'] 
                                                        ? strtoupper($t['payment_method']) 
                                                        : 'N/A'; 
                                                ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right text-slate-400 font-mono text-xs"><?php echo $t['created_at']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<?php require '../includes/footer.php'; ?>