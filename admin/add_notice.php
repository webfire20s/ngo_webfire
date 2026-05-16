<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

/* SUBMIT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $short = trim($_POST['short_description']);
    $full = trim($_POST['full_content']);

    $stmt = $pdo->prepare("
        INSERT INTO notices
        (title, category, short_description, full_content)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $title,
        $category,
        $short,
        $full
    ]);

    echo '
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
        <span class="text-base">✓</span> System broadcast record published successfully to live feeds.
    </div>';
}
?>

<!-- Form Control Dashboard Container -->
<div class="space-y-6 max-w-4xl">

    <!-- Header Block Component -->
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-5 border-b border-slate-200 gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Publishing Interface</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Add Notice</h1>
        </div>
        
        <!-- Quick Back To Directory Navigation Action Link -->
        <a href="notices.php" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200/70 border border-slate-200/60 px-3 py-2 rounded-lg transition-colors">
            ← Return to Directory
        </a>
    </header>

    <!-- Main Creation Form Panel Layout Block -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden p-6 sm:p-8">
        
        <form method="POST" class="space-y-6">

            <!-- Title Identity Field String Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Notice Title / Heading
                </label>
                <input type="text" name="title" required 
                       class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                       placeholder="e.g., Annual Maintenance Downtime Notification">
            </div>

            <!-- Group Taxonomy Classification Variant Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Classification Category
                </label>
                <input type="text" name="category" value="Announcement" required
                       class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400">
            </div>

            <!-- Summary Text Block Document Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Short Description (Teaser Feed Display)
                </label>
                <textarea name="short_description" rows="3" required
                          class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                          placeholder="Provide a concise one or two-sentence overview of this notice update..."></textarea>
            </div>

            <!-- Global Unabridged Longform Content Textarea Block Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Full Body Content Block
                </label>
                <textarea name="full_content" rows="10" required
                          class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-3 text-sm text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400 font-sans"
                          placeholder="Write the comprehensive informational bulletin notes here..."></textarea>
            </div>

            <!-- Submission Core Execution Button Row Panel -->
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" 
                        class="px-6 py-2.5 text-xs font-bold tracking-wide uppercase text-white bg-slate-900 hover:bg-slate-800 active:bg-black rounded-lg transition-colors duration-150 shadow-md">
                    Commit & Publish Notice
                </button>
            </div>

        </form>

    </div>

</div>

<?php require '../includes/footer.php'; ?>