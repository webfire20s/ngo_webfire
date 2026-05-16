<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

/* MARK RESOLVED */
if(isset($_GET['resolve'])){

    $id = (int)$_GET['resolve'];

    $pdo->prepare("
        UPDATE enquiries
        SET status='resolved'
        WHERE id=?
    ")->execute([$id]);
}

/* DELETE */
if(isset($_GET['delete'])){

    $id = (int)$_GET['delete'];

    $pdo->prepare("
        DELETE FROM enquiries
        WHERE id=?
    ")->execute([$id]);
}

/* FETCH */
$enquiries = $pdo->query("
    SELECT *
    FROM enquiries
    ORDER BY id DESC
")->fetchAll();
?>

<!-- Main Content Container Block -->
<div class="space-y-6">

    <!-- Header Block Component -->
    <header class="pb-5 border-b border-slate-200">
        <div>
            <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Communications Console</span>
            <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Enquiries Management</h1>
        </div>
    </header>

    <!-- Data Registry View Wrapper -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Incoming Client Correspondence Logs
            </h2>
            <span class="text-xs font-mono px-2 py-0.5 bg-slate-200/60 rounded text-slate-600 font-bold">
                Total: <?php echo count($enquiries); ?>
            </span>
        </div>

        <?php if (empty($enquiries)): ?>
            <div class="p-12 text-center text-slate-400 italic">
                No client inquiries recorded inside active workspace databases.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse m-0">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-[11px] font-bold uppercase tracking-wider bg-slate-50/20">
                            <th class="px-6 py-3.5 font-mono w-16">ID</th>
                            <th class="px-6 py-3.5">Sender Info</th>
                            <th class="px-6 py-3.5">Message Content</th>
                            <th class="px-6 py-3.5 w-32 text-center">Status</th>
                            <th class="px-6 py-3.5 w-40">Timestamp</th>
                            <th class="px-6 py-3.5 w-48 text-right">Operational Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                        <?php foreach($enquiries as $e): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors duration-75">
                            
                            <!-- Token Record ID Placement -->
                            <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                #<?php echo $e['id']; ?>
                            </td>
                            
                            <!-- Combined Contact Metadata Profile Card -->
                            <td class="px-6 py-4 space-y-0.5">
                                <div class="font-bold text-slate-900 tracking-tight">
                                    <?php echo htmlspecialchars($e['name']); ?>
                                </div>
                                <div class="text-xs text-slate-400 flex flex-col gap-0.5">
                                    <span class="hover:text-slate-600 transition-colors font-mono">
                                        <?php echo htmlspecialchars($e['email']); ?>
                                    </span>
                                    <span class="font-mono">
                                        <?php echo htmlspecialchars($e['phone']); ?>
                                    </span>
                                </div>
                            </td>
                            
                            <!-- Formulated Communication Body Context -->
                            <td class="px-6 py-4 max-w-xs xl:max-w-md">
                                <div class="text-xs leading-relaxed text-slate-600 bg-slate-50 border border-slate-100/70 p-3 rounded-lg font-normal max-h-32 overflow-y-auto break-words whitespace-pre-line">
                                    <?php echo nl2br(htmlspecialchars($e['message'])); ?>
                                </div>
                            </td>
                            
                            <!-- Dynamic Structural State Indicators -->
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <?php if($e['status'] === 'resolved'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200/60 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        Resolved
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200/60 rounded-full animate-pulse">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                        Pending
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- Temporal Placement Timestamp Row -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400 font-mono">
                                <?php echo !empty($e['created_at']) ? date('d M Y, H:i', strtotime($e['created_at'])) : 'System Time'; ?>
                            </td>
                            
                            <!-- Structural Operations & Routing Anchors -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs space-x-1">
                                <?php if($e['status'] !== 'resolved'): ?>
                                    <a href="?resolve=<?php echo $e['id']; ?>" 
                                       class="inline-block bg-slate-900 hover:bg-slate-800 text-white border border-slate-900 px-3 py-1.5 rounded font-bold tracking-wide uppercase transition-colors shadow-xs">
                                        Mark Resolved
                                    </a>
                                <?php endif; ?>
                                
                                <a href="?delete=<?php echo $e['id']; ?>" 
                                   onclick="return confirm('Delete enquiry?')" 
                                   class="inline-block text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-100 hover:border-rose-600 px-3 py-1.5 rounded font-bold tracking-wide uppercase transition-all shadow-xs">
                                    Delete
                                </a>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>

</div> <!-- Closes inner sidebar layout padding container -->
</div> <!-- Closes outer fixed sidebar layout grid width container -->

<?php require '../includes/footer.php'; ?>