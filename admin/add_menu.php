<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $name = trim($_POST['menu_name']);
    $link = trim($_POST['menu_link']);
    $type = $_POST['menu_type'];
    $sort = (int)$_POST['sort_order'];
    $status = (int)$_POST['status'];

    $stmt = $pdo->prepare("
        INSERT INTO menus
        (
            menu_name,
            menu_link,
            menu_type,
            sort_order,
            status
        )
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $name,
        $link,
        $type,
        $sort,
        $status
    ]);

    echo '
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
        <span class="text-base">✓</span> Structural record integrated successfully. New navigation node initialized.
    </div>';
}
?>

<!-- Main Content Container Block -->
<div class="max-w-2xl space-y-6">

    <!-- Header Block Component -->
    <header class="pb-5 border-b border-slate-200 flex items-end justify-between gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Navigation Schemes</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Add Menu Node</h1>
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
                       placeholder="e.g., Services"
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
                       placeholder="e.g., /services.php or https://..."
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
                            <option value="main">Main Menu</option>
                            <option value="dropdown">Dropdown Menu</option>
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
                           value="0" 
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
                            <option value="1">Active</option>
                            <option value="0">Hidden</option>
                        </select>
                    </div>
                </div>

            </div>

            <!-- Horizontal Separator Boundary Rule -->
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold tracking-wide uppercase px-6 py-3 rounded-lg transition-colors border border-slate-900 shadow-sm whitespace-nowrap">
                    Initialize Menu Node
                </button>
            </div>

        </form>
    </div>

</div>

</div> <!-- Closes inner sidebar layout padding container -->
</div> <!-- Closes outer fixed sidebar layout grid width container -->

<?php require '../includes/footer.php'; ?>