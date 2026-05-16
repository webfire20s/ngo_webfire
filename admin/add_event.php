<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/sidebar.php';


if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $title = $_POST['title'];
    $short = $_POST['short_description'];
    $full = $_POST['full_description'];
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];
    $location = $_POST['location'];
    $status = $_POST['status'];

    $image = null;

    /* IMAGE */
    if(isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0){

        if(!is_dir('../uploads/events')){
            mkdir('../uploads/events', 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));

        $image = 'event_' . time() . '.' . $ext;

        move_uploaded_file(
            $_FILES['featured_image']['tmp_name'],
            '../uploads/events/' . $image
        );
    }

    $stmt = $pdo->prepare("
        INSERT INTO events
        (
            title,
            short_description,
            full_description,
            event_date,
            event_time,
            location,
            featured_image,
            status
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $title,
        $short,
        $full,
        $date,
        $time,
        $location,
        $image,
        $status
    ]);

    echo '
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
        <span class="text-base">✓</span> Event asset entry compiled and written to live calendars successfully.
    </div>';
}
?>

<!-- Form Control Dashboard Container -->
<div class="space-y-6 max-w-4xl">

    <!-- Header Block Component -->
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-5 border-b border-slate-200 gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Scheduling Framework</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Add Event</h1>
        </div>
        
        <!-- Quick Back To Directory Navigation Action Link -->
        <a href="events.php" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200/70 border border-slate-200/60 px-3 py-2 rounded-lg transition-colors">
            ← Cancel & Return
        </a>
    </header>

    <!-- Main Schedule Creation Form Panel Layout Block -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden p-6 sm:p-8">
        
        <form method="POST" enctype="multipart/form-data" class="space-y-6">

            <!-- Title Identity Field String Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Event Title / Heading <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" required 
                       class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                       placeholder="e.g., Annual Business Summit 2026">
            </div>

            <!-- Summary Text Block Document Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Brief Teaser Overview (Grid Feed Display) <span class="text-rose-500">*</span>
                </label>
                <textarea name="short_description" rows="2" required
                          class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                          placeholder="Provide a short contextual summary highlighting the core purpose of this agenda event..."></textarea>
            </div>

            <!-- Global Unabridged Longform Content Textarea Block Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Comprehensive Event Details Breakdown <span class="text-rose-500">*</span>
                </label>
                <textarea name="full_description" rows="8" required
                          class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-3 text-sm text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400 font-sans"
                          placeholder="Write out the thorough roadmap details, timelines, guest segments, and operational itineraries here..."></textarea>
            </div>

            <!-- Dual Column Grid Layout: Date & Structural Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Target Execution Calendar Date -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Target Calendar Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="event_date" required 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner cursor-pointer text-slate-800">
                </div>

                <!-- Local Structural Execution Time -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Execution Operational Time
                    </label>
                    <input type="text" name="event_time" 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                           placeholder="e.g., 10:00 AM - 04:00 PM IST">
                </div>

            </div>

            <!-- Dual Column Grid Layout: Location & Operational States -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Geographic Hub Assignment Location Reference -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Geographic Hub Location Venue
                    </label>
                    <input type="text" name="location" 
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                           placeholder="e.g., Main Exhibition Hall, Corporate Hub">
                </div>

                <!-- Operational Registry Status Select Box Element -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Operational Initial Status Rank
                    </label>
                    <div class="relative">
                        <select name="status"
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all appearance-none shadow-inner cursor-pointer">
                            <option value="upcoming">Upcoming (Active Lineup)</option>
                            <option value="completed">Completed (Archived Record)</option>
                            <option value="cancelled">Cancelled (Void Status)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                            <span class="text-xs">▼</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- File Upload Field Control Node Container -->
            <div class="max-w-md">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Media Featured Promotional Image Display
                </label>
                <div class="mt-1 flex items-center justify-center border border-dashed border-slate-300 rounded-xl px-6 pt-5 pb-6 bg-slate-50/30 hover:bg-slate-50 transition-colors">
                    <div class="space-y-1 text-center">
                        <div class="flex text-sm text-slate-600 justify-center">
                            <label class="relative cursor-pointer bg-white rounded-md font-semibold text-slate-900 hover:text-slate-700 focus-within:outline-none border border-slate-200 px-3 py-1.5 shadow-sm text-xs uppercase tracking-wide">
                                <span>Choose Asset File</span>
                                <input type="file" name="featured_image" accept="image/*" class="sr-only">
                            </label>
                        </div>
                        <p class="text-xs text-slate-400 pt-1">Accepts raw standard imagery models up to system resolution bounds</p>
                    </div>
                </div>
            </div>

            <!-- Submission Core Form Execution Action Panel Row -->
            <div class="pt-5 border-t border-slate-100 flex justify-end">
                <button type="submit" 
                        class="px-6 py-2.5 text-xs font-bold tracking-wide uppercase text-white bg-slate-900 hover:bg-slate-800 active:bg-black rounded-lg transition-colors duration-150 shadow-md">
                    Publish Schedule Event
                </button>
            </div>

        </form>

    </div>

</div>