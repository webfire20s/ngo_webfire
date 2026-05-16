<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

$campaigns = $pdo->query("SELECT * FROM campaigns ORDER BY id DESC")->fetchAll();
?>

<!-- Main Content Area Container -->
<div class="space-y-6">

    <!-- Header Block -->
    <header class="border-b border-slate-200 pb-5">
        <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Fundraising Drivers</span>
        <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Campaigns Management</h1>
    </header>

    <!-- Financial Performance Ledger Card -->
    <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-400 font-bold">
                        <th class="py-3.5 px-6">Campaign Initiative Title</th>
                        <th class="py-3.5 px-6">Funding Target Goal</th>
                        <th class="py-3.5 px-6">Total Amount Collected</th>
                        <th class="py-3.5 px-6">Progress Metric</th>
                        <th class="py-3.5 px-6 text-right">Operational Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    <?php if (empty($campaigns)): ?>
                        <tr>
                            <td colspan="5" class="py-10 px-6 text-center text-slate-400 italic">
                                No historical fundraising campaigns available or recorded.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($campaigns as $c): ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            
                            <!-- Campaign Title -->
                            <td class="py-4 px-6 font-medium text-slate-900 max-w-sm break-words">
                                <?php echo htmlspecialchars($c['title']); ?>
                            </td>
                            
                            <!-- Goal Financial Target -->
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                ₹<?php echo number_format($c['goal_amount'], 2); ?>
                            </td>
                            
                            <!-- Realtime Inbound Volume Collected -->
                            <td class="py-4 px-6 font-semibold text-emerald-700">
                                ₹<?php echo number_format($c['collected_amount'], 2); ?>
                            </td>

                            <!-- Dynamic Calculated Progress Bar View -->
                            <td class="py-4 px-6 min-w-[140px]">
                                <?php 
                                $percent = $c['goal_amount'] > 0 ? min(100, round(($c['collected_amount'] / $c['goal_amount']) * 100)) : 0;
                                ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-24 bg-slate-100 border border-slate-200/60 rounded-full h-2 overflow-hidden shadow-inner">
                                        <div class="bg-slate-900 h-full rounded-full transition-all duration-500" style="width: <?php echo $percent; ?>%;"></div>
                                    </div>
                                    <span class="text-xs font-mono font-bold text-slate-500"><?php echo $percent; ?>%</span>
                                </div>
                            </td>

                            <!-- Workflow Status Flag -->
                            <td class="py-4 px-6 text-right">
                                <?php if ($c['status'] === 'active'): ?>
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        Active
                                    </span>
                                <?php elseif ($c['status'] === 'completed'): ?>
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200/60">
                                        Completed
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200/60">
                                        <?php echo htmlspecialchars($c['status']); ?>
                                    </span>
                                <?php endif; ?>
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