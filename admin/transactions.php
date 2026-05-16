<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

/* FILTERS */
$status = $_GET['status'] ?? '';
$method = $_GET['method'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$query = "
    SELECT t.*, u.name 
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    WHERE 1
";

$params = [];

/* APPLY FILTERS */
if (!empty($status)) {
    $query .= " AND t.status = ?";
    $params[] = $status;
}

if (!empty($method)) {
    $query .= " AND t.payment_method = ?";
    $params[] = $method;
}

if (!empty($from)) {
    $query .= " AND DATE(t.created_at) >= ?";
    $params[] = $from;
}

if (!empty($to)) {
    $query .= " AND DATE(t.created_at) <= ?";
    $params[] = $to;
}

$query .= " ORDER BY t.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$transactions = $stmt->fetchAll();
?>

<!-- Main Content Area Container -->
<div class="space-y-6">

    <!-- Header Block -->
    <header class="border-b border-slate-200 pb-5">
        <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Audit Trail</span>
        <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Transactions Management</h1>
    </header>

    <!-- Administrative Search & Filter Control Panel -->
    <div class="bg-white border border-slate-200/80 rounded-xl p-5 shadow-sm">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
            
            <!-- Status Filter -->
            <div class="space-y-1.5">
                <label for="status" class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">Status</label>
                <select name="status" id="status" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:bg-white transition-all cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="success" <?php echo $status === 'success' ? 'selected' : ''; ?>>Success</option>
                    <option value="failed" <?php echo $status === 'failed' ? 'selected' : ''; ?>>Failed</option>
                </select>
            </div>

            <!-- Method Filter -->
            <div class="space-y-1.5">
                <label for="method" class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">Method</label>
                <select name="method" id="method" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:bg-white transition-all cursor-pointer">
                    <option value="">All Methods</option>
                    <option value="upi" <?php echo $method === 'upi' ? 'selected' : ''; ?>>UPI</option>
                    <option value="cash" <?php echo $method === 'cash' ? 'selected' : ''; ?>>Cash</option>
                    <option value="bank" <?php echo $method === 'bank' ? 'selected' : ''; ?>>Bank</option>
                </select>
            </div>

            <!-- Date From Filter -->
            <div class="space-y-1.5">
                <label for="from" class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">From Date</label>
                <input type="date" name="from" id="from" value="<?php echo htmlspecialchars($from); ?>" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:bg-white transition-all">
            </div>

            <!-- Date To Filter -->
            <div class="space-y-1.5">
                <label for="to" class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">To Date</label>
                <input type="date" name="to" id="to" value="<?php echo htmlspecialchars($to); ?>" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:bg-white transition-all">
            </div>

            <!-- Submission Actions -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-medium text-xs tracking-wider uppercase py-2.5 rounded-lg transition-colors shadow-sm text-center">
                    Filter
                </button>
                <?php if(!empty($status) || !empty($method) || !empty($from) || !empty($to)): ?>
                    <a href="transactions.php" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2.5 rounded-lg text-xs transition-colors border border-slate-200 shadow-sm flex items-center justify-center" title="Clear Filters">
                        ✕
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Financial Ledger Table -->
    <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-400 font-bold">
                        <th class="py-3.5 px-6 w-16">ID</th>
                        <th class="py-3.5 px-6">User</th>
                        <th class="py-3.5 px-6">Amount</th>
                        <th class="py-3.5 px-6">Method</th>
                        <th class="py-3.5 px-6">UTR / Reference</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6">Proof File</th>
                        <th class="py-3.5 px-6 text-right">Settlement Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="8" class="py-10 px-6 text-center text-slate-400 italic">
                                No records matched the specified filter configurations.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $t): ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            <!-- ID Block -->
                            <td class="py-4 px-6 font-mono text-xs text-slate-400">
                                #<?php echo $t['id']; ?>
                            </td>

                            <!-- User Identity -->
                            <td class="py-4 px-6 font-medium text-slate-900">
                                <?php echo htmlspecialchars($t['name']); ?>
                            </td>
                            
                            <!-- Financial Amount -->
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                ₹<?php echo $t['amount']; ?>
                            </td>
                            
                            <!-- Payment Channel -->
                            <td class="py-4 px-6">
                                <span class="font-mono text-[10px] bg-slate-100 border border-slate-200/80 px-2 py-0.5 rounded text-slate-600 font-bold">
                                    <?php echo strtoupper($t['payment_method']); ?>
                                </span>
                            </td>

                            <!-- Transaction UTR -->
                            <td class="py-4 px-6 font-mono text-xs text-slate-700 select-all">
                                <?php echo htmlspecialchars($t['transaction_id']); ?>
                            </td>

                            <!-- Workflow Status -->
                            <td class="py-4 px-6">
                                <?php if ($t['status'] == 'success'): ?>
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        Success
                                    </span>
                                <?php elseif ($t['status'] == 'pending'): ?>
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200/60">
                                        Pending
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200/60">
                                        Failed
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Attachment Check -->
                            <td class="py-4 px-6">
                                <?php if (!empty($t['proof'])): ?>
                                    <a href="../uploads/payments/<?php echo $t['proof']; ?>" target="_blank" class="inline-flex items-center gap-1 text-xs text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-2 py-1 rounded transition-colors shadow-sm font-medium">
                                        View Proof ↗
                                    </a>
                                <?php else: ?>
                                    <span class="text-slate-300 text-xs">—</span>
                                <?php endif; ?>
                            </td>

                            <!-- Date Metrics -->
                            <td class="py-4 px-6 text-right font-mono text-xs text-slate-400 whitespace-nowrap">
                                <?php echo $t['created_at']; ?>
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