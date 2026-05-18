<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';

$id = $_GET['id'] ?? 0;

/* FETCH */
$stmt = $pdo->prepare("
    SELECT *
    FROM menus
    WHERE id=?
");

$stmt->execute([$id]);

$menu = $stmt->fetch();

if(!$menu){
    require '../includes/admin_header.php';
    require '../includes/sidebar.php';
    echo '
    <div class="max-w-2xl mx-auto my-12 text-center p-8 bg-slate-50 border border-slate-200 rounded-xl shadow-xs">
        <div class="w-12 h-12 bg-rose-50 border border-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="text-rose-600 font-bold text-lg">!</span>
        </div>
        <h3 class="text-lg font-semibold text-slate-900 mb-1">Target Node Terminated</h3>
        <p class="text-sm text-slate-500 mb-6">The requested navigation scheme item configuration details do not exist or have been purged.</p>
        <a href="menus.php" class="inline-flex text-xs font-bold uppercase tracking-wide bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg transition-colors">Return to Registry</a>
    </div>';
    require '../includes/footer.php';
    die();
}

/* UPDATE */
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $name = trim($_POST['menu_name']);
    $link = trim($_POST['menu_link']);
    $type = $_POST['menu_type'];
    $sort = (int)$_POST['sort_order'];
    $status = (int)$_POST['status'];

    $stmt = $pdo->prepare("
        UPDATE menus
        SET
            menu_name=?,
            menu_link=?,
            menu_type=?,
            sort_order=?,
            status=?
        WHERE id=?
    ");

    $stmt->execute([
        $name,
        $link,
        $type,
        $sort,
        $status,
        $id
    ]);

    $updateSuccess = true;

    /* REFRESH */
    $stmt = $pdo->prepare("
        SELECT *
        FROM menus
        WHERE id=?
    ");

    $stmt->execute([$id]);

    $menu = $stmt->fetch();
}

require '../includes/admin_header.php';
require '../includes/sidebar.php';
?>

<!-- Main Content Container Block -->
<div class="max-w-2xl space-y-6">

    <!-- Success Feedback Overlay Trigger Block -->
    <?php if(isset($updateSuccess) && $updateSuccess): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
            <span class="text-base">✓</span> Dynamic configuration values committed. Node metadata updated.
        </div>
    <?php endif; ?>

    <!-- Header Block Component -->
    <header class="pb-5 border-b border-slate-200 flex items-end justify-between gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Navigation Schemes</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Edit Menu Node</h1>
        </div>
        <div>
            <a href="menus.php" class="text-xs font-bold tracking-wide uppercase text-slate-500 hover:text-slate-900 transition-colors">
                ← Return to Registry
            </a>
        </div>
    </header>

    <!-- Structural Configuration Form Area Container -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden p-6 sm:p-8">
        <form method="POST" class="space-y-6">

            <!-- Name Input Fields Container Row Array -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                    Menu Node Name
                </label>
                <input type="text" 
                       name="menu_name" 
                       required 
                       value="<?php echo htmlspecialchars($menu['menu_name']); ?>"
                       class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400 focus:bg-white transition-all duration-75">
            </div>

            <!-- Destination Reference Link Field -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                    Destination Link Target
                </label>
                <input type="text" 
                       name="menu_link" 
                       required 
                       value="<?php echo htmlspecialchars($menu['menu_link']); ?>"
                       class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-mono text-slate-700 placeholder-slate-400 focus:outline-none focus:border-slate-400 focus:bg-white transition-all duration-75">
            </div>

            <!-- Interactive Attribute Selection Segment Matrix -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                
                <!-- Type Selection Dropdown Layout Component -->
                <div class="sm:col-span-1">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                        Type Classification
                    </label>
                    <div class="relative">
                        <select name="menu_type" 
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:border-slate-400 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="main" <?php if($menu['menu_type'] === 'main') echo 'selected'; ?>>Main Menu</option>
                            <option value="dropdown" <?php if($menu['menu_type'] === 'dropdown') echo 'selected'; ?>>Dropdown Menu</option>
                        </select>
                    </div>
                </div>

                <!-- Position Priority Numeric Array Variable Input -->
                <div class="sm:col-span-1">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                        Sort Order Position
                    </label>
                    <input type="number" 
                           name="sort_order" 
                           value="<?php echo $menu['sort_order']; ?>" 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-mono font-bold text-slate-700 focus:outline-none focus:border-slate-400 focus:bg-white transition-all duration-75">
                </div>

                <!-- State Flag Active Toggle Variable Selector -->
                <div class="sm:col-span-1">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                        State Visibility
                    </label>
                    <div class="relative">
                        <select name="status" 
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:border-slate-400 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="1" <?php if($menu['status'] == 1) echo 'selected'; ?>>Active</option>
                            <option value="0" <?php if($menu['status'] == 0) echo 'selected'; ?>>Hidden</option>
                        </select>
                    </div>
                </div>

            </div>

            <!-- Horizontal Separator Boundary Rule -->
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold tracking-wide uppercase px-6 py-3 rounded-lg transition-colors border border-slate-900 shadow-sm whitespace-nowrap">
                    Commit Changes
                </button>
            </div>

        </form>
    </div>

</div>

</div> <!-- Closes inner sidebar layout padding container -->
</div> <!-- Closes outer fixed sidebar layout grid width container -->

<?php require '../includes/footer.php'; ?>