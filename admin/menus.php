<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

/* DELETE */
if(isset($_GET['delete'])){

    $id = (int)$_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM menus WHERE id=?");
    $stmt->execute([$id]);

    echo '
    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
        <span class="text-base">✕</span> Targeted navigation structural record successfully purged from active deployment.
    </div>';
}

/* FETCH MENUS */
$menus = $pdo->query("
    SELECT *
    FROM menus
    ORDER BY menu_type ASC, sort_order ASC
")->fetchAll();
?>

<!-- Main Content Container Block -->
<div class="space-y-6">

    <!-- Header Block Component -->
    <header class="pb-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Navigation Schemes</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Menus Management</h1>
        </div>
        <div>
            <a href="add_menu.php" 
               class="inline-flex items-center justify-center bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold tracking-wide uppercase px-4 py-2.5 rounded-lg transition-colors border border-slate-900 shadow-sm whitespace-nowrap">
                Add New Menu Node
            </a>
        </div>
    </header>

    <!-- Content Row Matrix: Interactive Data Registry Row Arrays -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden">
        
        <?php if (empty($menus)): ?>
            <div class="p-12 text-center text-slate-400 italic">
                No structural layout configuration records found within active navigation definitions.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse m-0">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-[11px] font-bold uppercase tracking-wider bg-slate-50/20">
                            <th class="px-6 py-3.5 font-mono w-16">ID</th>
                            <th class="px-6 py-3.5">Menu Node Name</th>
                            <th class="px-6 py-3.5">Destination Link</th>
                            <th class="px-6 py-3.5 w-32">Type Classification</th>
                            <th class="px-6 py-3.5 w-24 text-center">Sort Order</th>
                            <th class="px-6 py-3.5 w-32 text-center">State Visibility</th>
                            <th class="px-6 py-3.5 w-40 text-right">Actions Matrix</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                        <?php foreach($menus as $menu): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors duration-75">
                            
                            <!-- Record Primary ID Identifier -->
                            <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                #<?php echo $menu['id']; ?>
                            </td>
                            
                            <!-- Menu Name Layout Output -->
                            <td class="px-6 py-4 text-slate-900 font-semibold">
                                <?php echo htmlspecialchars($menu['menu_name']); ?>
                            </td>
                            
                            <!-- Link Address Target Text Code Block -->
                            <td class="px-6 py-4 font-mono text-xs text-slate-500 max-w-xs truncate">
                                <?php echo htmlspecialchars($menu['menu_link']); ?>
                            </td>
                            
                            <!-- Menu Classification Area Tag Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block bg-slate-100 text-slate-800 font-mono text-xs font-bold px-2.5 py-0.5 rounded border border-slate-200/40 tracking-wide">
                                    <?php echo ucfirst($menu['menu_type']); ?>
                                </span>
                            </td>
                            
                            <!-- Position Numeric Priority Weight -->
                            <td class="px-6 py-4 text-center font-mono font-bold text-xs text-slate-600">
                                <?php echo $menu['sort_order']; ?>
                            </td>
                            
                            <!-- Conditional Active Flag Badge Element -->
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <?php if($menu['status']): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-bold font-mono uppercase bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        Active
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-bold font-mono uppercase bg-slate-100 text-slate-400 border border-slate-200/50">
                                        Hidden
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- Row Context Processing Utilities -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs space-x-1">
                                <a href="edit_menu.php?id=<?php echo $menu['id']; ?>" 
                                   class="inline-block text-slate-700 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-2.5 py-1 rounded font-bold tracking-wide uppercase transition-colors">
                                    Edit
                                </a>
                                <a href="?delete=<?php echo $menu['id']; ?>" 
                                   onclick="return confirm('Delete this menu?')" 
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