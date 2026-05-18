<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $title = trim($_POST['title']);
    $slug = trim($_POST['slug']);
    $content = $_POST['content'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("
        INSERT INTO pages
        (title, slug, content, status)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $title,
        $slug,
        $content,
        $status
    ]);

    header("Location: pages.php");
    exit;
}
?>

<!-- Main Content Container Block -->
<div class="max-w-4xl space-y-6">

    <!-- Header Block Component -->
    <header class="pb-5 border-b border-slate-200 flex items-end justify-between gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Content Architecture</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Add New Page</h1>
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
                           placeholder="e.g., Privacy Policy"
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
                           placeholder="e.g., privacy-policy"
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-mono text-slate-700 placeholder-slate-400 focus:outline-none focus:border-slate-400 focus:bg-white transition-all duration-75">
                </div>
            </div>

            <!-- Content Workspace Box Area Component -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                    Body Document Markup Content
                </label>
                <textarea name="content"
                    id="editor"
                    rows="15"
                    placeholder="Compose document source code or copy text layers here..."
                    class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-3 text-sm font-normal text-slate-800 placeholder-slate-400 focus:outline-none focus:border-slate-400 focus:bg-white transition-all duration-75 leading-relaxed resize-y min-h-[300px]"></textarea>
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
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Horizontal Separator Boundary Rule -->
            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold tracking-wide uppercase px-6 py-3 rounded-lg transition-colors border border-slate-900 shadow-sm whitespace-nowrap">
                    Create Layout Page
                </button>
            </div>

        </form>
    </div>

</div>

</div> <!-- Closes inner sidebar layout padding container -->
</div> <!-- Closes outer fixed sidebar layout grid width container -->
<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    ClassicEditor
        .create(document.querySelector('#editor'), {

            toolbar: [
                'heading',
                '|',
                'bold',
                'italic',
                'link',
                'bulletedList',
                'numberedList',
                '|',
                'blockQuote',
                'insertTable',
                '|',
                'undo',
                'redo'
            ]

        })
        .catch(error => {
            console.error(error);
        });

});
</script>
<?php require '../includes/footer.php'; ?>