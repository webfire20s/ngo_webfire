<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

/* BASIC METRICS */
$totalUsers = $pdo->query("
    SELECT COUNT(*) FROM users WHERE deleted_at IS NULL
")->fetchColumn();

$totalDonations = $pdo->query("
    SELECT SUM(amount) FROM donations
")->fetchColumn();

/* NEW METRICS */
$activeMembers = $pdo->query("
    SELECT COUNT(*) FROM memberships WHERE status = 'active'
")->fetchColumn();

$totalRevenue = $pdo->query("
    SELECT SUM(amount) FROM transactions WHERE status = 'success'
")->fetchColumn();

/* EXPENSES METRIC */
$totalExpenses = $pdo->query("
    SELECT SUM(amount) FROM expenses
")->fetchColumn();

/* PAYMENT METHOD BREAKDOWN */
$methods = $pdo->query("
    SELECT payment_method, COUNT(*) as total
    FROM transactions
    WHERE status = 'success'
    GROUP BY payment_method
")->fetchAll();

/* RECENT TRANSACTIONS */
$transactions = $pdo->query("
    SELECT t.amount, t.payment_method, u.name, t.created_at
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    WHERE t.status = 'success'
    ORDER BY t.id DESC
    LIMIT 5
")->fetchAll();

/* TOP REFERRERS */
$referrers = $pdo->query("
    SELECT u.name, COUNT(m.id) as total
    FROM memberships m
    JOIN users u ON m.referred_by = u.id
    GROUP BY m.referred_by
    ORDER BY total DESC
    LIMIT 5
")->fetchAll();
?>

<!-- Main Content Area Container -->
<div class="space-y-8">
    
    <!-- Dashboard Header -->
    <header class="border-b border-slate-200 pb-5">
        <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Management Dashboard</span>
        <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Admin Overview</h1>
    </header>

    <!-- Overview Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        <!-- Metric Card 1 -->
        <div class="bg-white border border-slate-200/80 rounded-xl p-5 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Total Users</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-3xl font-semibold text-slate-900"><?php echo $totalUsers; ?></span>
                <span class="text-xs text-slate-400 font-medium">registered</span>
            </div>
        </div>

        <!-- Metric Card 2 -->
        <div class="bg-white border border-slate-200/80 rounded-xl p-5 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Active Members</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-3xl font-semibold text-emerald-600"><?php echo $activeMembers; ?></span>
                <span class="text-xs text-emerald-500 font-medium bg-emerald-50 border border-emerald-100 px-1.5 py-0.5 rounded">live access</span>
            </div>
        </div>

        <!-- Metric Card 3 -->
        <div class="bg-white border border-slate-200/80 rounded-xl p-5 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Total Donations</span>
            <div class="mt-2">
                <span class="text-3xl font-semibold text-slate-900">₹<?php echo $totalDonations ?? 0; ?></span>
            </div>
        </div>

        <!-- Metric Card 4 -->
        <!-- Metric Card 4 -->
        <div class="bg-white border border-slate-200/80 rounded-xl p-5 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Total Revenue</span>
            <div class="flex flex-col items-start mt-2">
                <span class="text-3xl font-semibold text-slate-900 leading-none">₹<?php echo $totalRevenue ?? 0; ?></span>
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mt-1.5">Membership Segment</span>
            </div>
        </div>

        <!-- Metric Card 5: Added Expenses Box -->
        <div class="bg-white border border-slate-200/80 rounded-xl p-5 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Total Expenses</span>
            <div class="flex items-baseline gap-1 mt-2">
                <span class="text-3xl font-semibold text-rose-600">₹<?php echo number_format($totalExpenses ?? 0, 2); ?></span>
            </div>
        </div>
    </div>

    <!-- Multi-Column Tables Row Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left Side: Recent Transactions (Takes up 2 columns on desktops) -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Recent Transactions</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] uppercase tracking-wider text-slate-400">
                                <th class="py-3 px-5 font-bold">Name</th>
                                <th class="py-3 px-5 font-bold">Amount</th>
                                <th class="py-3 px-5 font-bold">Method</th>
                                <th class="py-3 px-5 font-bold text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                            <?php if (empty($transactions)): ?>
                                <tr>
                                    <td colspan="4" class="py-6 px-5 text-center text-slate-400 italic">No transactions captured.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($transactions as $t): ?>
                                <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                                    <td class="py-3.5 px-5 font-medium text-slate-900"><?php echo htmlspecialchars($t['name']); ?></td>
                                    <td class="py-3.5 px-5 font-semibold text-slate-800">₹<?php echo $t['amount']; ?></td>
                                    <td class="py-3.5 px-5">
                                        <span class="font-mono text-[11px] bg-slate-100 border border-slate-200 px-2 py-0.5 rounded text-slate-600">
                                            <?php echo strtoupper($t['payment_method']); ?>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-5 text-right font-mono text-xs text-slate-400"><?php echo $t['created_at']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Side: Metrics Breakdown Columns (Takes up 1 column) -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Payment Methods Card -->
            <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Payment Methods</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] uppercase tracking-wider text-slate-400">
                                <th class="py-3 px-5 font-bold">Method</th>
                                <th class="py-3 px-5 font-bold text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                            <?php if (empty($methods)): ?>
                                <tr>
                                    <td colspan="2" class="py-6 px-5 text-center text-slate-400 italic">No breakdown available.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($methods as $m): ?>
                                <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                                    <td class="py-3 px-5 font-mono text-xs text-slate-700"><?php echo strtoupper($m['payment_method']); ?></td>
                                    <td class="py-3 px-5 text-right font-semibold text-slate-900"><?php echo $m['total']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Referrers Card -->
            <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Top Referrers</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] uppercase tracking-wider text-slate-400">
                                <th class="py-3 px-5 font-bold">Name</th>
                                <th class="py-3 px-5 font-bold text-right">Referrals</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                            <?php if (empty($referrers)): ?>
                                <tr>
                                    <td colspan="2" class="py-6 px-5 text-center text-slate-400 italic">No referral data.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($referrers as $r): ?>
                                <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                                    <td class="py-3 px-5 font-medium text-slate-900"><?php echo htmlspecialchars($r['name']); ?></td>
                                    <td class="py-3 px-5 text-right font-semibold text-emerald-600"><?php echo $r['total']; ?></td>
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

</div> <!-- Closes inner sidebar layout padding container -->
</div> <!-- Closes outer fixed sidebar layout grid width container -->

<?php require '../includes/footer.php'; ?>