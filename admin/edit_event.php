<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

$id = (int) ($_GET['id'] ?? 0);

/* FETCH EVENT */
$stmt = $pdo->prepare("
    SELECT * FROM events 
    WHERE id=?
");
$stmt->execute([$id]);

$event = $stmt->fetch();

if (!$event) {
    die("Event not found");
}

/* UPDATE EVENT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $short = trim($_POST['short_description']);
    $full = trim($_POST['full_description']);
    $date = $_POST['event_date'];
    $time = trim($_POST['event_time']);
    $location = trim($_POST['location']);
    $status = $_POST['status'];

    /* KEEP OLD IMAGE */
    $image = $event['featured_image'];

    /* NEW IMAGE UPLOAD */
    if (
        isset($_FILES['featured_image']) &&
        $_FILES['featured_image']['error'] === 0
    ) {

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        $ext = strtolower(pathinfo(
            $_FILES['featured_image']['name'],
            PATHINFO_EXTENSION
        ));

        if (in_array($ext, $allowed)) {

            if (!is_dir('../uploads/events')) {
                mkdir('../uploads/events', 0777, true);
            }

            /* DELETE OLD IMAGE */
            if (
                !empty($event['featured_image']) &&
                file_exists('../uploads/events/' . $event['featured_image'])
            ) {
                unlink('../uploads/events/' . $event['featured_image']);
            }

            /* SAVE NEW IMAGE */
            $image = 'event_' . time() . '_' . rand(1000,9999) . '.' . $ext;

            move_uploaded_file(
                $_FILES['featured_image']['tmp_name'],
                '../uploads/events/' . $image
            );
        }
    }

    /* UPDATE DB */
    $stmt = $pdo->prepare("
        UPDATE events
        SET
            title=?,
            short_description=?,
            full_description=?,
            event_date=?,
            event_time=?,
            location=?,
            featured_image=?,
            status=?
        WHERE id=?
    ");

    $stmt->execute([
        $title,
        $short,
        $full,
        $date,
        $time,
        $location,
        $image,
        $status,
        $id
    ]);

    echo '
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
        <span class="text-base">✓</span> Calendar details synchronized successfully. Storage logs updated.
    </div>';

    /* REFRESH DATA */
    $stmt = $pdo->prepare("
        SELECT * FROM events 
        WHERE id=?
    ");
    $stmt->execute([$id]);

    $event = $stmt->fetch();
}
?>

<!-- Form Modification Dashboard Container -->
<div class="space-y-6 max-w-4xl">

    <!-- Header Block Component -->
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-5 border-b border-slate-200 gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Schedule Modifications</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Edit Event</h1>
        </div>
        
        <!-- Quick Back To Directory Navigation Action Link -->
        <a href="events.php" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200/70 border border-slate-200/60 px-3 py-2 rounded-lg transition-colors">
            ← Cancel & Return
        </a>
    </header>

    <!-- Main Schedule Modification Form Panel Layout Block -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden p-6 sm:p-8">
        
        <form method="POST" enctype="multipart/form-data" class="space-y-6">

            <!-- Title Identity Field String Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Event Title / Heading <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required 
                       class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400">
            </div>

            <!-- Summary Text Block Document Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Brief Teaser Overview (Grid Feed Display) <span class="text-rose-500">*</span>
                </label>
                <textarea name="short_description" rows="3" required
                          class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"><?php echo htmlspecialchars($event['short_description']); ?></textarea>
            </div>

            <!-- Global Unabridged Longform Content Textarea Block Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Comprehensive Event Details Breakdown <span class="text-rose-500">*</span>
                </label>
                <textarea name="full_description" rows="10" required
                          class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-3 text-sm text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400 font-sans"><?php echo htmlspecialchars($event['full_description']); ?></textarea>
            </div>

            <!-- Dual Column Grid Layout: Date & Structural Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Target Execution Calendar Date -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Target Calendar Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="event_date" value="<?php echo $event['event_date']; ?>" required 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner cursor-pointer text-slate-800">
                </div>

                <!-- Local Structural Execution Time -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Execution Operational Time
                    </label>
                    <input type="text" name="event_time" value="<?php echo htmlspecialchars($event['event_time']); ?>"
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400">
                </div>

            </div>

            <!-- Dual Column Grid Layout: Location & Operational States -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Geographic Hub Assignment Location Reference -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Geographic Hub Location Venue
                    </label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>"
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400">
                </div>

                <!-- Operational Registry Status Select Box Element -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Operational Current Status Rank
                    </label>
                    <div class="relative">
                        <select name="status"
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all appearance-none shadow-inner cursor-pointer">
                            <option value="upcoming" <?php if($event['status']=='upcoming') echo 'selected'; ?>>Upcoming (Active Lineup)</option>
                            <option value="completed" <?php if($event['status']=='completed') echo 'selected'; ?>>Completed (Archived Record)</option>
                            <option value="cancelled" <?php if($event['status']=='cancelled') echo 'selected'; ?>>Cancelled (Void Status)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                            <span class="text-xs">▼</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Asset Review & Replacement Module Row Container -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                
                <!-- Current Active Image Asset Display Box -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Current Active Cover Media
                    </label>
                    <?php if (!empty($event['featured_image'])): ?>
                        <div class="inline-block p-2 bg-slate-50 border border-slate-200 rounded-xl shadow-sm">
                            <img src="../uploads/events/<?php echo $event['featured_image']; ?>" 
                                 alt="Active Event Cover"
                                 class="max-w-xs h-auto max-h-40 object-contain rounded-lg">
                        </div>
                    <?php else: ?>
                        <div class="max-w-xs h-28 rounded-xl bg-slate-50 border border-slate-200/60 flex flex-col items-center justify-center text-center p-4">
                            <span class="text-xs font-bold tracking-wider text-slate-300 uppercase">No Cover Asset Linked</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Binary Media File Drop Zone Replacement Target -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Replace Cover Asset File
                    </label>
                    <div class="flex items-center justify-center border border-dashed border-slate-300 rounded-xl px-6 pt-7 pb-8 bg-slate-50/30 hover:bg-slate-50 transition-colors h-[116px]">
                        <div class="space-y-1 text-center">
                            <label class="relative cursor-pointer bg-white rounded-md font-semibold text-slate-900 hover:text-slate-700 focus-within:outline-none border border-slate-200 px-3 py-1.5 shadow-sm text-xs uppercase tracking-wide">
                                <span>Upload Alternative</span>
                                <input type="file" name="featured_image" accept="image/*" class="sr-only">
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Submission Confirmation Row Panel -->
            <div class="pt-5 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-400 font-mono">
                    Modifying Unique Record Token: #<?php echo $id; ?>
                </span>
                <button type="submit" 
                        class="px-6 py-2.5 text-xs font-bold tracking-wide uppercase text-white bg-slate-900 hover:bg-slate-800 active:bg-black rounded-lg transition-colors duration-150 shadow-md">
                    Apply Updates
                </button>
            </div>

        </form>

    </div>

</div>