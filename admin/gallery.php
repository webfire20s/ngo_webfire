<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

/* ADD IMAGE */
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $title = trim($_POST['title']);

    $image = null;

    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){

        $allowed = ['jpg','jpeg','png','webp'];

        $ext = strtolower(pathinfo(
            $_FILES['image']['name'],
            PATHINFO_EXTENSION
        ));

        if(in_array($ext, $allowed)){

            if(!is_dir('../uploads/gallery')){
                mkdir('../uploads/gallery', 0777, true);
            }

            $image = 'gallery_' . time() . '_' . rand(1000,9999) . '.' . $ext;

            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                '../uploads/gallery/' . $image
            );

            $stmt = $pdo->prepare("
                INSERT INTO gallery
                (title, image)
                VALUES (?, ?)
            ");

            $stmt->execute([$title, $image]);

            echo '
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
                <span class="text-base">✓</span> Media asset ingested and written to memory successfully.
            </div>';
        }
    }
}

/* DELETE */
if(isset($_GET['delete'])){

    $id = (int)$_GET['delete'];

    $stmt = $pdo->prepare("
        SELECT * FROM gallery
        WHERE id=?
    ");

    $stmt->execute([$id]);

    $img = $stmt->fetch();

    if($img){

        if(file_exists('../uploads/gallery/' . $img['image'])){
            unlink('../uploads/gallery/' . $img['image']);
        }

        $pdo->prepare("
            DELETE FROM gallery
            WHERE id=?
        ")->execute([$id]);
    }
}

/* FETCH */
$gallery = $pdo->query("
    SELECT *
    FROM gallery
    ORDER BY id DESC
")->fetchAll();
?>

<!-- Main Content Area Container -->
<div class="space-y-6">

    <!-- Header Block Component -->
    <header class="pb-5 border-b border-slate-200">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Asset Repositories</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Gallery Management</h1>
        </div>
    </header>

    <!-- Split Architecture: Ingestion Card vs Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Section: Asset Ingestion Form Card -->
        <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm p-6 space-y-4 sticky top-6">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 pb-2 border-b border-slate-100">
                Upload New Media
            </h2>
            
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                
                <!-- Title Entry Input -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                        Asset Title Identification <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" required 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-3 py-2 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                           placeholder="e.g., Campus Event Stage Setup">
                </div>

                <!-- Dropzone Binary Upload Input -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                        Select Target File <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex items-center justify-center border border-dashed border-slate-300 rounded-xl px-4 py-6 bg-slate-50/30 hover:bg-slate-50 transition-colors text-center">
                        <div class="space-y-2">
                            <label class="relative cursor-pointer bg-white rounded-md font-semibold text-slate-900 hover:text-slate-700 border border-slate-200 px-3 py-1.5 shadow-sm text-xs uppercase tracking-wide inline-block">
                                <span>Choose Image</span>
                                <input type="file" name="image" accept="image/*" required class="sr-only">
                            </label>
                            <p class="text-[10px] text-slate-400">Supports standard Web formats</p>
                        </div>
                    </div>
                </div>

                <!-- Run Ingestion Operations Button -->
                <button type="submit" 
                        class="w-full mt-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold tracking-wide uppercase py-2.5 rounded-lg transition-colors shadow-md border border-slate-900">
                    Ingest & Process Asset
                </button>

            </form>
        </div>

        <!-- Section: Active Visual Registry Directory Grid Layout -->
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Active Media Inventories
            </h2>

            <?php if (empty($gallery)): ?>
                <div class="bg-white border border-slate-200/80 rounded-xl p-10 text-center text-slate-400 italic shadow-sm">
                    No graphic assets uploaded inside active media registries.
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach($gallery as $g): ?>
                    <div class="bg-white border border-slate-200/80 rounded-xl p-4 flex flex-col justify-between shadow-sm hover:border-slate-300 transition-all duration-150 group">
                        
                        <!-- Media Bounds Preview Image Box -->
                        <div class="relative w-full aspect-video bg-slate-50 border border-slate-100 rounded-lg overflow-hidden mb-3">
                            <img src="../uploads/gallery/<?php echo $g['image']; ?>" 
                                 alt="<?php echo htmlspecialchars($g['title']); ?>"
                                 class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                            
                            <!-- Absolute Token ID Tracker Placement Badge -->
                            <span class="absolute top-2 left-2 bg-slate-900/80 backdrop-blur-xs text-[10px] font-mono font-bold text-white px-2 py-0.5 rounded">
                                #<?php echo $g['id']; ?>
                            </span>
                        </div>

                        <!-- Metadata Records and Structural Action Row Grid -->
                        <div class="space-y-2">
                            <h3 class="font-semibold text-slate-900 text-sm tracking-tight truncate">
                                <?php echo htmlspecialchars($g['title']); ?>
                            </h3>
                            
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-400 font-mono text-[11px]">
                                    <?php echo !empty($g['created_at']) ? date('d M Y', strtotime($g['created_at'])) : 'System Record'; ?>
                                </span>
                                
                                <a href="?delete=<?php echo $g['id']; ?>" 
                                   onclick="return confirm('Delete image?')" 
                                   class="text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-100 hover:border-rose-600 px-2.5 py-1 rounded font-semibold transition-all shadow-xs">
                                    Delete
                                </a>
                            </div>
                        </div>

                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>

    </div>

</div>

</div> <!-- Closes inner sidebar layout padding container -->
</div> <!-- Closes outer fixed sidebar layout grid width container -->

<?php require '../includes/footer.php'; ?>