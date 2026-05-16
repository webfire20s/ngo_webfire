<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

/* FILTERS */
$search = $_GET['search'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$query = "
    SELECT r.*, 
           u.name AS user_name,
           d.donor_name
    FROM receipts r
    LEFT JOIN users u ON r.user_id = u.id
    LEFT JOIN donations d 
        ON r.reference_id = d.id AND r.type='donation'
    WHERE 1
";

$params = [];

/* SEARCH (Fixed logic alignment and parameter array sizing) */
if (!empty($search)) {
    $query .= " AND (
        u.name LIKE ? 
        OR d.donor_name LIKE ? 
        OR r.receipt_no LIKE ?
    )";
    $term = "%$search%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
}

/* DATE FILTER */
if (!empty($from)) {
    $query .= " AND DATE(r.created_at) >= ?";
    $params[] = $from;
}

if (!empty($to)) {
    $query .= " AND DATE(r.created_at) <= ?";
    $params[] = $to;
}

$query .= " ORDER BY r.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$receipts = $stmt->fetchAll();
?>

<!-- Main Content Area Container -->
<div class="space-y-6">

    <!-- Header Block -->
    <header class="border-b border-slate-200 pb-5">
        <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Invoicing Desk</span>
        <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Receipts Management</h1>
    </header>

    <!-- Administrative Search & Filter Control Panel -->
    <div class="bg-white border border-slate-200/80 rounded-xl p-5 shadow-sm">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
            
            <!-- Text-based Search Context -->
            <div class="space-y-1.5">
                <label for="search" class="block text-[11px] font-bold tracking-wider text-slate-400 uppercase">Search Records</label>
                <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Name / Receipt No..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:bg-white transition-all">
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
                <?php if(!empty($search) || !empty($from) || !empty($to)): ?>
                    <a href="receipts.php" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2.5 rounded-lg text-xs transition-colors border border-slate-200 shadow-sm flex items-center justify-center" title="Clear Filters">
                        ✕
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Corporate Ledger Table Frame -->
    <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-400 font-bold">
                        <th class="py-3.5 px-6 w-16">ID</th>
                        <th class="py-3.5 px-6">Receipt No</th>
                        <th class="py-3.5 px-6">Entity / User</th>
                        <th class="py-3.5 px-6">Amount</th>
                        <th class="py-3.5 px-6">Method</th>
                        <th class="py-3.5 px-6">Settlement Date</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    <?php if (empty($receipts)): ?>
                        <tr>
                            <td colspan="7" class="py-10 px-6 text-center text-slate-400 italic">
                                No verified receipts matched the specified query options.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($receipts as $r): ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            <!-- Unique Identifier Row ID -->
                            <td class="py-4 px-6 font-mono text-xs text-slate-400">
                                #<?php echo $r['id']; ?>
                            </td>

                            <!-- Registered Receipt Number -->
                            <td class="py-4 px-6 font-mono text-xs text-slate-900 font-semibold select-all">
                                <?php echo htmlspecialchars($r['receipt_no']); ?>
                            </td>
                            
                            <!-- Entity / User Context Check -->
                            <td class="py-4 px-6 font-medium text-slate-800">
                                <?php 
                                echo htmlspecialchars(
                                    $r['type'] === 'donation' 
                                    ? ($r['donor_name'] ?? 'Guest') 
                                    : $r['user_name']
                                ); 
                                ?>
                            </td>
                            
                            <!-- Calculated Financial Amount -->
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                ₹<?php echo $r['amount']; ?>
                            </td>
                            
                            <!-- Inbound Mode Check -->
                            <td class="py-4 px-6">
                                <span class="font-mono text-[10px] bg-slate-100 border border-slate-200/80 px-2 py-0.5 rounded text-slate-600 font-bold">
                                    <?php echo strtoupper($r['payment_method'] ?? 'N/A'); ?>
                                </span>
                            </td>

                            <!-- Internal Creation Timestamp -->
                            <td class="py-4 px-6 font-mono text-xs text-slate-400">
                                <?php echo $r['created_at']; ?>
                            </td>

                            <!-- Document Retrieval Engine Links -->
                            <td class="py-4 px-6 text-right text-xs font-semibold">
                                <a href="../generate_receipt.php?id=<?php echo $r['id']; ?>" target="_blank" class="inline-flex items-center gap-1 text-slate-700 hover:text-white hover:bg-slate-900 border border-slate-200 hover:border-slate-900 px-3 py-1.5 rounded transition-all shadow-sm">
                                    View / Download ↗
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