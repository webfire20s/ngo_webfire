<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

/* ADD EXPENSE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category = trim($_POST['category']);
    $amount = (float) $_POST['amount'];
    $expense_date = $_POST['expense_date'];
    $description = trim($_POST['description']);

    if (
        !empty($category) &&
        $amount > 0 &&
        !empty($expense_date)
    ) {

        $stmt = $pdo->prepare("
            INSERT INTO expenses
            (
                category,
                amount,
                expense_date,
                description
            )
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $category,
            $amount,
            $expense_date,
            $description
        ]);

        echo '
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
            <span class="text-base">✓</span> Operational expense record has been successfully written to memory.
        </div>';
    }
}

/* DELETE */
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    $pdo->prepare("
        DELETE FROM expenses
        WHERE id=?
    ")->execute([$id]);

    echo '
    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
        <span class="text-base">✕</span> Targeted expense allocation entry successfully purged from memory.
    </div>';
}

/* FILTERS */
$category_filter = $_GET['category'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$query = "
    SELECT *
    FROM expenses
    WHERE 1
";

$params = [];

/* CATEGORY FILTER */
if (!empty($category_filter)) {

    $query .= " AND category = ?";
    $params[] = $category_filter;
}

/* DATE FILTERS */
if (!empty($from)) {

    $query .= " AND expense_date >= ?";
    $params[] = $from;
}

if (!empty($to)) {

    $query .= " AND expense_date <= ?";
    $params[] = $to;
}

$query .= " ORDER BY expense_date DESC, id DESC";

/* FETCH */
$stmt = $pdo->prepare($query);
$stmt->execute($params);

$expenses = $stmt->fetchAll();

/* TOTAL */
$totalStmt = $pdo->prepare("
    SELECT SUM(amount)
    FROM expenses
    WHERE 1
    " .

    (!empty($category_filter) ? " AND category = ?" : "") .

    (!empty($from) ? " AND expense_date >= ?" : "") .

    (!empty($to) ? " AND expense_date <= ?" : "")
);

$totalStmt->execute($params);

$totalExpense = $totalStmt->fetchColumn();
?>

<!-- Main Content Container Block -->
<div class="space-y-6">

    <!-- Header Block Component -->
    <header class="pb-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Financial Control</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Expense Management</h1>
        </div>
    </header>

    <!-- Top Row: Aggregation Summary KPI Card and Search Filters Form Workspace -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
        
        <!-- Metric Output Balance Card Component -->
        <div class="bg-slate-900 text-white rounded-xl p-6 flex flex-col justify-between shadow-md border border-slate-950 relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 text-slate-800/40 text-9xl font-mono select-none pointer-events-none font-bold">
                ₹
            </div>
            <div>
                <span class="text-[10px] font-bold tracking-widest uppercase text-slate-400 block mb-1">
                    Aggregated Liability Metrics
                </span>
                <p class="text-xs text-slate-400/80 font-normal leading-relaxed">
                    Reflecting total net processing volume across currently isolated parameter boundaries.
                </p>
            </div>
            <div class="mt-4 lg:mt-0">
                <span class="text-sm font-light text-slate-400 font-mono">Total Allocation:</span>
                <h3 class="text-3xl font-mono font-bold tracking-tight text-white mt-0.5">
                    ₹<?php echo number_format($totalExpense ?? 0, 2); ?>
                </h3>
            </div>
        </div>

        <!-- Filter Controls Deck Container -->
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-xl p-6 shadow-sm flex flex-col justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
                <span>Filter Accounting Boundaries</span>
            </h3>
            
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 m-0">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                        Category
                    </label>
                    <input type="text" name="category" 
                           value="<?php echo htmlspecialchars($category_filter); ?>"
                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                           placeholder="Search category...">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                        From Date
                    </label>
                    <input type="date" name="from" 
                           value="<?php echo htmlspecialchars($from); ?>"
                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner">
                </div>
                <div class="flex flex-col justify-between">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                        To Date
                    </label>
                    <div class="flex gap-2 items-center">
                        <input type="date" name="to" 
                               value="<?php echo htmlspecialchars($to); ?>"
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-mono font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner">
                        <button type="submit" 
                                class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold tracking-wide uppercase px-4 py-2 rounded-lg transition-colors border border-slate-900 shadow-sm whitespace-nowrap h-full">
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Workspace Split: Ingestion Form Layout vs Registry Records List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Section: Ledger Transaction Ingestion Form Card -->
        <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm p-6 space-y-4 sticky top-6">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 pb-2 border-b border-slate-100">
                Record New Allocation
            </h2>
            
            <form method="POST" class="space-y-4 m-0">
                
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                        Category Label <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="category" required 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-3 py-2 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner"
                           placeholder="e.g., Vehicle Fuel, Maintenance">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                        Amount Valuation (INR) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-sm font-medium text-slate-400 pointer-events-none font-mono">₹</span>
                        <input type="number" step="0.01" name="amount" required 
                               class="w-full bg-slate-50/50 border border-slate-200 rounded-lg pl-7 pr-3 py-2 text-sm font-mono font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner"
                               placeholder="0.00">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                        Transaction Incurred Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="expense_date" required 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                        Description Summary Notes
                    </label>
                    <textarea name="description" rows="4" 
                              class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-3 py-2 text-sm font-normal text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner leading-relaxed"
                              placeholder="Optional descriptive metadata details..."></textarea>
                </div>

                <button type="submit" 
                        class="w-full mt-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold tracking-wide uppercase py-2.5 rounded-lg transition-colors shadow-md border border-slate-900">
                    Commit Expense Entry
                </button>

            </form>
        </div>

        <!-- Section: Active Interactive Data Ledger Registry Row Arrays -->
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden">
            
            <?php if (empty($expenses)): ?>
                <div class="p-12 text-center text-slate-400 italic">
                    No matching transactional liability items found matching active schema boundaries.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse m-0">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 text-[11px] font-bold uppercase tracking-wider bg-slate-50/20">
                                <th class="px-6 py-3.5 font-mono w-16">ID</th>
                                <th class="px-6 py-3.5">Category Class</th>
                                <th class="px-6 py-3.5 w-32">Amount</th>
                                <th class="px-6 py-3.5 w-36">Incurred Date</th>
                                <th class="px-6 py-3.5">Contextual Description</th>
                                <th class="px-6 py-3.5 w-24 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                            <?php foreach ($expenses as $e): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors duration-75">
                                
                                <!-- Record Primary ID Identifier -->
                                <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                    #<?php echo $e['id']; ?>
                                </td>
                                
                                <!-- Expense Categorization Badge -->
                                <td class="px-6 py-4">
                                    <span class="inline-block bg-slate-100 text-slate-800 font-mono text-xs font-bold px-2.5 py-0.5 rounded border border-slate-200/50 tracking-tight">
                                        <?php echo htmlspecialchars($e['category']); ?>
                                    </span>
                                </td>
                                
                                <!-- Curated Currency Numeric Processing Output -->
                                <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-slate-900 text-xs">
                                    ₹<?php echo number_format($e['amount'], 2); ?>
                                </td>
                                
                                <!-- Business Calendar Allocation Target Time -->
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-mono">
                                    <?php echo !empty($e['expense_date']) ? date('d M Y', strtotime($e['expense_date'])) : '—'; ?>
                                </td>
                                
                                <!-- Contextual Description Metadata Output -->
                                <td class="px-6 py-4 max-w-xs">
                                    <div class="text-xs text-slate-600 font-normal break-words whitespace-pre-line leading-relaxed max-h-24 overflow-y-auto">
                                        <?php echo !empty($e['description']) ? nl2br(htmlspecialchars($e['description'])) : '<span class="text-slate-300 italic">No notes provided</span>'; ?>
                                    </div>
                                </td>
                                
                                <!-- Operational Modification Purge Triggers -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                    <a href="?delete=<?php echo $e['id']; ?>" 
                                       onclick="return confirm('Delete expense?')" 
                                       class="inline-block text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-100 hover:border-rose-600 px-2.5 py-1 rounded font-bold tracking-wide uppercase transition-all shadow-xs">
                                        Delete
                                    </a>
                                </td>

                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>

    </div>

</div>

</div> <!-- Closes inner sidebar layout padding container -->
</div> <!-- Closes outer fixed sidebar layout grid width container -->

<?php require '../includes/footer.php'; ?>