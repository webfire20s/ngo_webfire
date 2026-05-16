<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $desc = $_POST['description'];
    $target = $_POST['target'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    $stmt = $pdo->prepare("
        INSERT INTO campaigns 
        (title, description, goal_amount, start_date, end_date)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$title, $desc, $target, $start, $end]);

    echo '
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
        <span class="text-base">✓</span> Campaign initiative registered and initialized successfully.
    </div>';
}
?>

<!-- Main Content Area Container -->
<div class="space-y-6 max-w-4xl">

    <!-- Header Block -->
    <header class="border-b border-slate-200 pb-5">
        <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Resource Mobilization</span>
        <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Create Campaign</h1>
    </header>

    <!-- Interactive Creation Form Interface Card -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm p-6 sm:p-8">
        <form method="POST" class="space-y-6">
            
            <!-- Initiative Title Entry -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Campaign Title <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" required 
                       class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                       placeholder="e.g., Annual Monsoon Relief Drive">
            </div>

            <!-- Conceptual Project Description -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                    Description / Initiative Overview
                </label>
                <textarea name="description" rows="5"
                          class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400 resize-y"
                          placeholder="Provide the operational breakdown and strategic objectives for this fundraising initiative..."></textarea>
            </div>

            <!-- Quantitative Funding Configuration Metric Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Financial Goal Target Input -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Target Goal Amount (INR) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative rounded-lg shadow-inner">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="text-slate-400 text-sm font-medium">₹</span>
                        </div>
                        <input type="number" name="target" required min="1" step="any"
                               class="w-full bg-slate-50/50 border border-slate-200 rounded-lg pl-8 pr-4 py-2.5 text-sm font-semibold text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all"
                               placeholder="0.00">
                    </div>
                </div>

                <!-- Timeline Parameter Configuration: Start -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Deployment Start Date
                    </label>
                    <input type="date" name="start"
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner">
                </div>

                <!-- Timeline Parameter Configuration: End -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Target Expiration Date
                    </label>
                    <input type="date" name="end"
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner">
                </div>

            </div>

            <!-- Execution Actions Footbar -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" 
                        class="px-6 py-2.5 text-sm font-bold tracking-wide uppercase text-white bg-slate-900 hover:bg-slate-800 active:bg-black rounded-lg transition-colors duration-150 shadow-md">
                    Create Campaign
                </button>
            </div>

        </form>
    </div>

</div>

</div> <!-- Closes inner sidebar layout padding container -->
</div> <!-- Closes outer fixed sidebar layout grid width container -->

<?php require '../includes/footer.php'; ?>