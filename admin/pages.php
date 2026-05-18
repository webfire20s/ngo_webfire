<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

/* DELETE */
if(isset($_GET['delete'])){

    $id = (int)$_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM pages WHERE id=?");
    $stmt->execute([$id]);

    header("Location: pages.php");
    exit;
}

/* FETCH */
$pages = $pdo->query("
    SELECT *
    FROM pages
    ORDER BY id DESC
")->fetchAll();
?>

<!-- Main Content Container Block -->
<div class="space-y-6">

    <!-- Header Block Component -->
    <header class="pb-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Content Architecture</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Pages Management</h1>
        </div>
        <div>
            <a href="add_page.php" 
               class="inline-flex items-center justify-center bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold tracking-wide uppercase px-4 py-2.5 rounded-lg transition-colors border border-slate-900 shadow-sm whitespace-nowrap">
                + Add New Page
            </a>
        </div>
    </header>

    <!-- Content Registry Matrix Grid Layer -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden">
        
        <?php if (empty($pages)): ?>
            <div class="p-12 text-center text-slate-400 italic">
                No active layout document structures or dynamic site pages initialized.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse m-0">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-[11px] font-bold uppercase tracking-wider bg-slate-50/20">
                            <th class="px-6 py-3.5 font-mono w-16">ID</th>
                            <th class="px-6 py-3.5">Page Title</th>
                            <th class="px-6 py-3.5">URL Slug Identifier</th>
                            <th class="px-6 py-3.5 w-32 text-center">State Status</th>
                            <th class="px-6 py-3.5 w-44">Created Timestamp</th>
                            <th class="px-6 py-3.5 w-40 text-right">Actions Matrix</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                        <?php foreach($pages as $p): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors duration-75">
                            
                            <!-- Document Record Database Identifier key -->
                            <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                #<?php echo $p['id']; ?>
                            </td>
                            
                            <!-- Dynamic Content Title Text Header -->
                            <td class="px-6 py-4 text-slate-900 font-semibold">
                                <?php echo htmlspecialchars($p['title']); ?>
                            </td>
                            
                            <!-- Permanent Route Directory String Pointer -->
                            <td class="px-6 py-4 font-mono text-xs text-slate-500 max-w-xs truncate">
                                /<?php echo htmlspecialchars($p['slug']); ?>
                            </td>
                            
                            <!-- Context-Aware Operational Status Badge -->
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <?php if(strtolower($p['status']) === 'published' || $p['status'] == '1'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-bold font-mono uppercase bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <?php echo ucfirst($p['status']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-bold font-mono uppercase bg-amber-50 text-amber-700 border border-amber-200/60">
                                        <?php echo ucfirst($p['status']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- Initialization Date-Time Log Column -->
                            <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                <?php echo $p['created_at']; ?>
                            </td>
                            
                            <!-- Interface Maintenance Control Suite Links -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs space-x-1">
                                <a href="edit_page.php?id=<?php echo $p['id']; ?>" 
                                   class="inline-block text-slate-700 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-2.5 py-1 rounded font-bold tracking-wide uppercase transition-colors">
                                    Edit
                                </a>
                                <a href="?delete=<?php echo $p['id']; ?>" 
                                   onclick="return confirm('Delete this page?')" 
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

</div> <!-- Closes inner sidebar layout padding container -->
</div> <!-- Closes outer fixed sidebar layout grid width container -->

<?php require '../includes/footer.php'; ?>