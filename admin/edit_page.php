<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT *
    FROM pages
    WHERE id=?
");

$stmt->execute([$id]);

$page = $stmt->fetch();

if(!$page){
    require '../includes/admin_header.php';
    require '../includes/sidebar.php';
    echo '
    <div class="max-w-2xl mx-auto my-12 text-center p-8 bg-slate-50 border border-slate-200 rounded-xl shadow-xs">
        <div class="w-12 h-12 bg-rose-50 border border-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="text-rose-600 font-bold text-lg">!</span>
        </div>
        <h3 class="text-lg font-semibold text-slate-900 mb-1">Document Terminated</h3>
        <p class="text-sm text-slate-500 mb-6">The requested content architecture record does not exist or has been permanently deleted.</p>
        <a href="pages.php" class="inline-flex text-xs font-bold uppercase tracking-wide bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg transition-colors">Return to Registry</a>
    </div>';
    require '../includes/footer.php';
    die();
}

/* UPDATE */
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $title = trim($_POST['title']);
    $slug = trim($_POST['slug']);
    $content = $_POST['content'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("
        UPDATE pages
        SET
            title=?,
            slug=?,
            content=?,
            status=?
        WHERE id=?
    ");

    $stmt->execute([
        $title,
        $slug,
        $content,
        $status,
        $id
    ]);

    header("Location: pages.php");
    exit;
}

require '../includes/admin_header.php';
require '../includes/sidebar.php';
?>

<!-- Main Content Container Block -->
<div class="max-w-4xl space-y-6">

    <!-- Header Block Component -->
    <header class="pb-5 border-b border-slate-200 flex items-end justify-between gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Content Architecture</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Edit Layout Page</h1>
        </div>
        <div>
            <a href="pages.php" class="text-xs font-bold tracking-wide uppercase text-slate-500 hover:text-slate-900 transition-colors">
                ← Return to Registry
            </a>
        </div>
    </header>

    <!-- Structural Configuration Form Area Container -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden p-6 sm:p-8">
        <form method="POST" class="space-y-6">

            <!-- Title and Slug Split Grid Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title Input Field -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                        Document Title
                    </label>
                    <input type="text" 
                           name="title" 
                           required 
                           value="<?php echo htmlspecialchars($page['title']); ?>"
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400 focus:bg-white transition-all duration-75">
                </div>

                <!-- Slug Route Input Field -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                        URL Slug Directory Target
                    </label>
                    <input type="text" 
                           name="slug" 
                           required 
                           value="<?php echo htmlspecialchars($page['slug']); ?>"
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-mono text-slate-700 placeholder-slate-400 focus:outline-none focus:border-slate-400 focus:bg-white transition-all duration-75">
                </div>
            </div>

            <!-- Content Workspace Box Area Component -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                    Body Document Markup Content
                </label>
                <textarea id="editor"
                    name="content"
                    rows="15"><?php echo htmlspecialchars($page['content']); ?></textarea>
            </div>

            <!-- Operational Properties Adjustment Grid Segment -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                <!-- State Visibility Toggle Selector Box -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                        Publish State Visibility
                    </label>
                    <div class="relative">
                        <select name="status" 
                                class="w-full sm:w-64 bg-slate-50/50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:border-slate-400 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="published" <?php if($page['status']=='published') echo 'selected'; ?>>Published</option>
                            <option value="draft" <?php if($page['status']=='draft') echo 'selected'; ?>>Draft</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Horizontal Separator Boundary Rule -->
            <div class="pt-6 border-t border-slate-100 flex justify-end">
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
<script>
ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
        console.error(error);
    });
</script>
<?php require '../includes/footer.php'; ?>