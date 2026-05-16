<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';
?>

<!-- Main Content Area Container -->
<div class="space-y-6">

    <!-- Header Block -->
    <header class="border-b border-slate-200 pb-5">
        <span class="text-xs font-bold tracking-widest text-slate-400 uppercase">Contribution Audit</span>
        <h1 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Donations</h1>
    </header>

    <!-- Action Log Status Banners -->
    <?php
    /* APPROVE */
    if (isset($_GET['approve'])) {
        $id = (int)$_GET['approve'];

        try {
            $pdo->beginTransaction();

            /* LOCK ROW */
            $stmt = $pdo->prepare("SELECT * FROM donations WHERE id=? FOR UPDATE");
            $stmt->execute([$id]);
            $donation = $stmt->fetch();

            if (!$donation) {
                throw new Exception("Donation not found");
            }

            if ($donation['status'] !== 'pending') {
                throw new Exception("Already processed");
            }

            /* UPDATE STATUS */
            $pdo->prepare("UPDATE donations SET status='success' WHERE id=?")
                ->execute([$id]);

            /* PREVENT DUPLICATE RECEIPT */
            $check = $pdo->prepare("
                SELECT id FROM receipts 
                WHERE reference_id=? AND type='donation'
            ");
            $check->execute([$id]);

            if ($check->fetch()) {
                throw new Exception("Receipt already exists");
            }

            /* CREATE RECEIPT */
            $receipt_no = 'RCPT' . time();

            $pdo->prepare("
                INSERT INTO receipts 
                (user_id, type, reference_id, receipt_no, amount, payment_method)
                VALUES (?, 'donation', ?, ?, ?, ?)
            ")->execute([
                $donation['user_id'],
                $donation['id'],
                $receipt_no,
                $donation['amount'],
                $donation['payment_method']
            ]);

            /* UPDATE CAMPAIGN COLLECTION */
            if (!empty($donation['campaign_id'])) {

                $pdo->prepare("
                    UPDATE campaigns 
                    SET collected_amount = collected_amount + ?
                    WHERE id = ?
                ")->execute([
                    $donation['amount'],
                    $donation['campaign_id']
                ]);

                /* AUTO COMPLETE */
                $pdo->prepare("
                    UPDATE campaigns 
                    SET status='completed'
                    WHERE id=? AND collected_amount >= goal_amount
                ")->execute([$donation['campaign_id']]);
            }

            $pdo->commit();

            echo '
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
                <span class="text-base">✓</span> Donation approved and contribution ledger updated successfully.
            </div>';

        } catch (Exception $e) {
            $pdo->rollBack();
            echo '
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
                <span class="text-base">✕</span> Error: ' . htmlspecialchars($e->getMessage()) . '
            </div>';
        }
    }

    /* REJECT */
    if (isset($_GET['reject'])) {
        $id = (int)$_GET['reject'];

        $pdo->prepare("UPDATE donations SET status='failed' WHERE id=?")
            ->execute([$id]);

        echo '
        <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm">
            <span class="text-base">⚠</span> Donation transaction marked as failed.
        </div>';
    }

    /* FETCH */
    $donations = $pdo->query("
        SELECT d.*, c.title AS campaign_title
        FROM donations d
        LEFT JOIN campaigns c ON d.campaign_id = c.id
        ORDER BY d.id DESC
    ")->fetchAll();
    ?>

    <!-- Financial Ledger Table Wrapper -->
    <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-400 font-bold">
                        <th class="py-3.5 px-6">Donor Name</th>
                        <th class="py-3.5 px-6">Campaign Target</th>
                        <th class="py-3.5 px-6">Amount</th>
                        <th class="py-3.5 px-6">UTR / Reference</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6">Proof Attachment</th>
                        <th class="py-3.5 px-6 text-right">Administrative Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    <?php if (empty($donations)): ?>
                        <tr>
                            <td colspan="7" class="py-10 px-6 text-center text-slate-400 italic">
                                No donation entries recorded in the system.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($donations as $d): ?>
                        <tr class="hover:bg-slate-50/40 transition-colors duration-150">
                            
                            <!-- Donor Identity -->
                            <td class="py-4 px-6 font-medium text-slate-900">
                                <?php echo htmlspecialchars($d['donor_name']); ?>
                            </td>

                            <!-- Targeted Campaign Mapping -->
                            <td class="py-4 px-6">
                                <span class="text-xs font-medium text-slate-600">
                                    <?php echo htmlspecialchars($d['campaign_title'] ?? 'General Fund'); ?>
                                </span>
                            </td>
                            
                            <!-- Contribution Amount -->
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                ₹<?php echo $d['amount']; ?>
                            </td>
                            
                            <!-- Unique Reference UTR -->
                            <td class="py-4 px-6 font-mono text-xs text-slate-700 select-all">
                                <?php echo htmlspecialchars($d['transaction_id'] ?: '—'); ?>
                            </td>

                            <!-- Database Field Processing Status Badge -->
                            <td class="py-4 px-6">
                                <?php if ($d['status'] === 'success'): ?>
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        Success
                                    </span>
                                <?php elseif ($d['status'] === 'pending'): ?>
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200/60">
                                        Pending
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200/60">
                                        Failed
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Proof Validation Link -->
                            <td class="py-4 px-6">
                                <?php if ($d['proof']): ?>
                                    <a href="../uploads/donations/<?php echo $d['proof']; ?>" target="_blank" class="inline-flex items-center gap-1 text-xs text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-2 py-1 rounded transition-colors shadow-sm font-medium">
                                        View Attachment ↗
                                    </a>
                                <?php else: ?>
                                    <span class="text-xs text-slate-300 italic">No File</span>
                                <?php endif; ?>
                            </td>

                            <!-- Contextual Flow Actions Block -->
                            <td class="py-4 px-6 text-right text-xs font-semibold whitespace-nowrap">
                                <?php if ($d['status'] === 'pending'): ?>
                                    <div class="inline-flex space-x-2">
                                        <a href="?approve=<?php echo $d['id']; ?>" onclick="return confirm('Approve this donation entry?')" class="text-emerald-700 hover:text-white hover:bg-emerald-600 border border-emerald-200 hover:border-emerald-600 px-2.5 py-1.5 rounded transition-all shadow-sm">
                                            Approve
                                        </a>
                                        <a href="?reject=<?php echo $d['id']; ?>" onclick="return confirm('Reject this donation entry?')" class="text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-200 hover:border-rose-600 px-2.5 py-1.5 rounded transition-all shadow-sm">
                                            Reject
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <span class="inline-flex items-center text-[11px] font-medium text-slate-400 bg-slate-50 border border-slate-100 px-2.5 py-1 rounded">
                                        ✓ Completed
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