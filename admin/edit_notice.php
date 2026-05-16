<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

$id = (int) ($_GET['id'] ?? 0);

/* FETCH */
$stmt = $pdo->prepare("SELECT * FROM notices WHERE id=?");
$stmt->execute([$id]);

$notice = $stmt->fetch();

if (!$notice) {
    die("Notice not found");
}

/* UPDATE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $short = trim($_POST['short_description']);
    $full = trim($_POST['full_content']);

    $stmt = $pdo->prepare("
        UPDATE notices
        SET title=?,
            category=?,
            short_description=?,
            full_content=?
        WHERE id=?
    ");

    $stmt->execute([
        $title,
        $category,
        $short,
        $full,
        $id
    ]);

    echo '
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
        <span class="text-base">✓</span> Changes compiled successfully. Notice bulletin data refreshed in live databases.
    </div>';

    /* REFRESH */
    $stmt = $pdo->prepare("SELECT * FROM notices WHERE id=?");
    $stmt->execute([$id]);
    $notice = $stmt->fetch();
}
?>

<!-- Form Modification Dashboard Container -->
<div class="space-y-6 max-w-4xl">

    <!-- Header Block Component -->
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-5 border-b border-slate-200 gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">System Modifications</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Edit Notice</h1>
        </div>
        
        <!-- Quick Back To Directory Navigation Action Link -->
        <a href="notices.php" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200/70 border border-slate-200/60 px-3 py-2 rounded-lg transition-colors">
            ← Cancel & Return
        </a>
    </header>

    <!-- Main Edit Form Panel Layout Block -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden p-6 sm:p-8">
        
        <form method="POST" class="space-y-6">

            <!-- Title Identity Field String Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Notice Title / Heading
                </label>
                <input type="text" name="title" 
                       value="<?php echo htmlspecialchars($notice['title']); ?>" required 
                       class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400">
            </div>

            <!-- Group Taxonomy Classification Variant Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Classification Category
                </label>
                <input type="text" name="category" 
                       value="<?php echo htmlspecialchars($notice['category']); ?>" required
                       class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400">
            </div>

            <!-- Summary Text Block Document Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Short Description (Teaser Feed Display)
                </label>
                <textarea name="short_description" rows="3" required
                          class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"><?php echo htmlspecialchars($notice['short_description']); ?></textarea>
            </div>

            <!-- Global Unabridged Longform Content Textarea Block Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Full Body Content Block
                </label>
                <textarea name="full_content" rows="10" required
                          class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-3 text-sm text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400 font-sans"><?php echo htmlspecialchars($notice['full_content']); ?></textarea>
            </div>

            <!-- Modification Confirmation Row Panel -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-400 font-mono">
                    Modifying Notice Token: #<?php echo $id; ?>
                </span>
                <button type="submit" 
                        class="px-6 py-2.5 text-xs font-bold tracking-wide uppercase text-white bg-slate-900 hover:bg-slate-800 active:bg-black rounded-lg transition-colors duration-150 shadow-md">
                    Commit Changes
                </button>
            </div>

        </form>

    </div>

</div>

<?php require '../includes/footer.php'; ?>