<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

$search = $_GET['search'] ?? '';

$query = "
    SELECT c.*, u.name 
    FROM certificates c
    JOIN users u ON c.user_id = u.id
    WHERE c.type='id_card'
";

$params = [];

if (!empty($search)) {
    $query .= " AND u.name LIKE ?";
    $params[] = "%$search%";
}

$query .= " ORDER BY c.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$cards = $stmt->fetchAll();
?>

<!-- Main Content Area Container -->
<div class="space-y-6">

    <!-- Header Block & Search Control Bar Layout Grid -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Credentialing Infrastructure</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">ID Cards Management</h1>
        </div>

        <!-- Search Input Operational Console -->
        <form method="GET" class="flex items-center gap-2 max-w-sm w-full md:w-auto">
            <div class="relative flex-1 md:w-64">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search user profile..."
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-3 pr-4 py-2 text-sm text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400">
            </div>
            <button type="submit" 
                    class="px-4 py-2 text-xs font-bold tracking-wide uppercase text-white bg-slate-900 hover:bg-slate-800 rounded-lg transition-colors duration-150 shadow-sm">
                Filter
            </button>
            <?php if (!empty($search)): ?>
                <a href="id_cards.php" class="px-3 py-2 text-xs font-medium text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg transition-colors">
                    Clear
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Identity Ledger Records Table Wrapper -->
    <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-400 font-bold">
                        <th class="py-3.5 px-6 w-16">System ID</th>
                        <th class="py-3.5 px-6">Credential ID Number</th>
                        <th class="py-3.5 px-6">Assigned User Holder</th>
                        <th class="py-3.5 px-6">Timestamp Issued At</th>
                        <th class="py-3.5 px-6 text-right">Asset Verification Link</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    <?php if (empty($cards)): ?>
                        <tr>
                            <td colspan="5" class="py-10 px-6 text-center text-slate-400 italic">
                                <?php echo !empty($search) ? 'No credentials matched your active search query parameters.' : 'No issued identity credentials found in database archives.'; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cards as $c): ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            
                            <!-- Internal Primary Key Database Pointer -->
                            <td class="py-4 px-6 font-mono text-xs text-slate-400">
                                #<?php echo $c['id']; ?>
                            </td>

                            <!-- Public Facing Certificate Reference Document Number -->
                            <td class="py-4 px-6 font-semibold tracking-tight text-slate-900">
                                <?php echo htmlspecialchars($c['certificate_no'] ?? '—'); ?>
                            </td>

                            <!-- Assigned Relational User Base Object Profile -->
                            <td class="py-4 px-6 font-medium text-slate-800">
                                <?php echo htmlspecialchars($c['name']); ?>
                            </td>
                            
                            <!-- Tracking Datetime Field Stamp -->
                            <td class="py-4 px-6 text-slate-500 font-mono text-xs">
                                <?php echo $c['issued_at']; ?>
                            </td>

                            <!-- Absolute Contextual File Mapping Actions Area -->
                            <td class="py-4 px-6 text-right whitespace-nowrap text-xs font-semibold">
                                <?php if (!empty($c['pdf_path']) && file_exists("../" . $c['pdf_path'])): ?>
                                    <a href="../<?php echo $c['pdf_path']; ?>" target="_blank" 
                                       class="inline-flex items-center gap-1.5 text-slate-700 hover:text-white hover:bg-slate-900 border border-slate-200 hover:border-slate-900 px-3 py-1.5 rounded-lg transition-all shadow-sm">
                                        Download PDF 📥
                                    </a>
                                <?php else: ?>
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200/60">
                                        File Missing
                                    </span>
                                <?php endif; ?>
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