<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

/* DELETE */
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM notices WHERE id=?");
    $stmt->execute([$id]);

    echo '
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
        <span class="text-base">✓</span> Notice bulletin completely expunged from system archives.
    </div>';
}

/* FETCH */
$notices = $pdo->query("
    SELECT * FROM notices
    ORDER BY id DESC
")->fetchAll();
?>

<!-- Main Content Area Container -->
<div class="space-y-6">

    <!-- Header Block & Outbound Routing Action Panel -->
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-5 border-b border-slate-200 gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Communications Console</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Manage Notices</h1>
        </div>
        
        <!-- Add Notice Redirect Trigger -->
        <a href="add_notice.php" 
           class="inline-flex items-center justify-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold tracking-wide uppercase px-4 py-2.5 rounded-lg transition-colors shadow-md whitespace-nowrap border border-slate-900">
            <span class="text-sm font-normal leading-none">+</span> Add New Notice
        </a>
    </header>

    <!-- Information Bulletin Records Ledger Table Wrapper -->
    <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-400 font-bold">
                        <th class="py-3.5 px-6 w-16">ID</th>
                        <th class="py-3.5 px-6">Bulletin Title Heading</th>
                        <th class="py-3.5 px-6 w-40">Classification Category</th>
                        <th class="py-3.5 px-6 w-44">Date Released</th>
                        <th class="py-3.5 px-6 text-right w-44">Administrative Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    <?php if (empty($notices)): ?>
                        <tr>
                            <td colspan="5" class="py-10 px-6 text-center text-slate-400 italic">
                                No official notifications or public boards broadcast in system memory.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($notices as $n): ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            
                            <!-- Internal Primary Key Database Pointer -->
                            <td class="py-4 px-6 font-mono text-xs text-slate-400">
                                #<?php echo $n['id']; ?>
                            </td>

                            <!-- Escaped Notice Title text -->
                            <td class="py-4 px-6 font-medium text-slate-900 max-w-md break-words">
                                <?php echo htmlspecialchars($n['title']); ?>
                            </td>

                            <!-- Category Segment Badge -->
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200/60">
                                    <?php echo htmlspecialchars($n['category']); ?>
                                </span>
                            </td>
                            
                            <!-- Formatted Local Date View -->
                            <td class="py-4 px-6 text-slate-500 font-mono text-xs">
                                <?php echo date('d M Y', strtotime($n['created_at'])); ?>
                            </td>

                            <!-- Contextual Route Modification Triggers -->
                            <td class="py-4 px-6 text-right whitespace-nowrap text-xs font-semibold">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="edit_notice.php?id=<?php echo $n['id']; ?>" 
                                       class="text-slate-700 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-2.5 py-1.5 rounded transition-colors shadow-sm">
                                        Edit
                                    </a>
                                    <a href="?delete=<?php echo $n['id']; ?>" 
                                       onclick="return confirm('Delete this notice?')" 
                                       class="text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-100 hover:border-rose-600 px-2.5 py-1.5 rounded transition-all shadow-sm">
                                        Delete
                                    </a>
                                </div>
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