<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

/* SEARCH */
$search = $_GET['search'] ?? '';

$query = "
    SELECT * FROM users 
    WHERE deleted_at IS NULL
";

$params = [];

if (!empty($search)) {
    $query .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $searchTerm = "%$search%";
    $params = [$searchTerm, $searchTerm, $searchTerm];
}

$query .= " ORDER BY id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll();

/* TOGGLE STATUS */
if (isset($_GET['toggle'])) {
    $id = (int) $_GET['toggle'];

    $pdo->prepare("
        UPDATE users 
        SET status = IF(status='active','inactive','active')
        WHERE id=?
    ")->execute([$id]);

    header("Location: users.php");
    exit;
}

/* SOFT DELETE */
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    $pdo->prepare("
        UPDATE users 
        SET deleted_at = NOW() 
        WHERE id=?
    ")->execute([$id]);

    header("Location: users.php");
    exit;
}
?>

<!-- Main Content Area Container -->
<div class="space-y-6">
    
    <!-- User Management Header -->
    <header class="flex flex-col xl:flex-row xl:items-center xl:justify-between pb-5 border-b border-slate-200 gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Directory Control</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">User Management</h1>
        </div>
        
        <!-- Action controls group alignment -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full xl:w-auto">
            
            <!-- Premium Search Bar Alignment -->
            <form method="GET" class="flex items-center gap-2 flex-1 sm:w-80 max-w-sm">
                <div class="relative w-full">
                    <input type="text" name="search" placeholder="Search by name, email or phone..." value="<?php echo htmlspecialchars($search); ?>" class="w-full text-sm text-slate-800 placeholder-slate-400 bg-white border border-slate-200 rounded-lg px-4 py-2 focus:outline-none focus:border-slate-400 transition-colors shadow-sm">
                </div>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm whitespace-nowrap">
                    Search
                </button>
            </form>

            <!-- Creation Router Link Action Button -->
            <a href="create_member.php" class="inline-flex items-center justify-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold tracking-wide uppercase px-4 py-2.5 rounded-lg transition-colors shadow-md whitespace-nowrap border border-slate-900">
                <span class="text-sm font-normal leading-none">+</span> Add Member
            </a>

        </div>
    </header>

    <!-- Users Enterprise Table Container -->
    <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-400 font-bold">
                        <th class="py-3 px-6 w-16">ID</th>
                        <th class="py-3 px-6">Name</th>
                        <th class="py-3 px-6">Contact Info</th>
                        <th class="py-3 px-6 w-32">Status</th>
                        <th class="py-3 px-6 w-44 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="py-8 px-6 text-center text-slate-400 italic">No registered users matched the search parameters.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            <!-- User ID -->
                            <td class="py-4 px-6 font-mono text-xs text-slate-400">#<?php echo $u['id']; ?></td>
                            
                            <!-- User Identity Details -->
                            <td class="py-4 px-6 font-medium text-slate-900">
                                <?php echo htmlspecialchars($u['name']); ?>
                            </td>
                            
                            <!-- Contact Matrix Column -->
                            <td class="py-4 px-6 space-y-0.5">
                                <span class="block text-slate-700 font-medium"><?php echo htmlspecialchars($u['email']); ?></span>
                                <span class="block font-mono text-xs text-slate-400"><?php echo htmlspecialchars($u['phone']); ?></span>
                            </td>

                            <!-- Dynamic Status Badges -->
                            <td class="py-4 px-6">
                                <?php if ($u['status'] === 'active'): ?>
                                    <span class="inline-flex text-[11px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Active
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex text-[11px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                        Inactive
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Contextual Row Controls -->
                            <td class="py-4 px-6 text-right text-xs font-semibold space-x-2">
                                <a href="?toggle=<?php echo $u['id']; ?>" class="inline-flex text-slate-600 hover:text-slate-900 border border-slate-200 hover:border-slate-300 bg-white px-2.5 py-1.5 rounded transition-colors shadow-sm">
                                    <?php echo $u['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>
                                </a>
                                <a href="?delete=<?php echo $u['id']; ?>" onclick="return confirm('Delete this user?')" class="inline-flex text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-200 hover:border-rose-600 px-2.5 py-1.5 rounded transition-all shadow-sm">
                                    Delete
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