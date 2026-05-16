<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

/* DELETE */
if(isset($_GET['delete'])){

    $id = (int)$_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM events WHERE id=?");
    $stmt->execute([$id]);

    echo '
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
        <span class="text-base">✓</span> Event schedule entry successfully dropped from calendar registries.
    </div>';
}

/* FETCH */
$events = $pdo->query("
    SELECT * FROM events
    ORDER BY event_date DESC
")->fetchAll();
?>

<!-- Main Content Area Container -->
<div class="space-y-6">

    <!-- Header Block & Calendar Action Toolbar -->
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-5 border-b border-slate-200 gap-4">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Operational Calendars</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Manage Events</h1>
        </div>
        
        <!-- Add Event Navigation Redirect Trigger -->
        <a href="add_event.php" 
           class="inline-flex items-center justify-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold tracking-wide uppercase px-4 py-2.5 rounded-lg transition-colors shadow-md whitespace-nowrap border border-slate-900">
            <span class="text-sm font-normal leading-none">+</span> Add Event
        </a>
    </header>

    <!-- Calendar Management Ledger Table Block -->
    <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-400 font-bold">
                        <th class="py-3.5 px-6 w-24">Media Preview</th>
                        <th class="py-3.5 px-6">Event Title Heading</th>
                        <th class="py-3.5 px-6 w-44">Scheduled Date</th>
                        <th class="py-3.5 px-6 w-36">Registry Status</th>
                        <th class="py-3.5 px-6 text-right w-44">Administrative Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="5" class="py-10 px-6 text-center text-slate-400 italic">
                                No system events scheduled inside active database memory.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($events as $e): ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            
                            <!-- Featured Image Asset Render Container -->
                            <td class="py-4 px-6 vertical-middle">
                                <?php if(!empty($e['featured_image'])): ?>
                                    <img src="../uploads/events/<?php echo $e['featured_image']; ?>" 
                                         alt="Event Thumbnail"
                                         class="w-16 h-12 object-cover rounded-lg bg-slate-100 border border-slate-200 shadow-sm">
                                <?php else: ?>
                                    <div class="w-16 h-12 rounded-lg bg-slate-50 border border-slate-200/60 flex items-center justify-center text-[10px] uppercase font-bold text-slate-300 tracking-wider select-none">
                                        No Image
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Clean Escaped Event Title Heading Text -->
                            <td class="py-4 px-6 font-medium text-slate-900 max-w-sm break-words">
                                <?php echo htmlspecialchars($e['title']); ?>
                            </td>
                            
                            <!-- Formatted Local Calendar Date Notation -->
                            <td class="py-4 px-6 text-slate-500 font-mono text-xs">
                                <?php echo date('d M Y', strtotime($e['event_date'])); ?>
                            </td>

                            <!-- Dynamic Context-Driven Status Badge Mapping -->
                            <td class="py-4 px-6">
                                <?php 
                                $statusLower = strtolower($e['status']);
                                $badgeStyle = "bg-slate-100 text-slate-700 border-slate-200/60";
                                if ($statusLower === 'active' || $statusLower === 'upcoming') {
                                    $badgeStyle = "bg-emerald-50 text-emerald-700 border-emerald-200/60";
                                } elseif ($statusLower === 'completed' || $statusLower === 'expired') {
                                    $badgeStyle = "bg-slate-100 text-slate-400 border-slate-200/40";
                                }
                                ?>
                                <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-md border <?php echo $badgeStyle; ?>">
                                    <?php echo htmlspecialchars(ucfirst($e['status'])); ?>
                                </span>
                            </td>

                            <!-- Action Modifications Control Stack Links -->
                            <td class="py-4 px-6 text-right whitespace-nowrap text-xs font-semibold">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="edit_event.php?id=<?php echo $e['id']; ?>" 
                                       class="text-slate-700 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-2.5 py-1.5 rounded transition-colors shadow-sm">
                                        Edit
                                    </a>
                                    <a href="?delete=<?php echo $e['id']; ?>" 
                                       onclick="return confirm('Delete event?')" 
                                       class="text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-100 hover:border-rose-600 px-2.5 py-1.5 rounded transition-all shadow-sm">
                                        Delete
                                    </a>
                                </div>
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