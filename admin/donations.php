<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/sidebar.php';

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

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}

/* REJECT */
if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];

    $pdo->prepare("UPDATE donations SET status='failed' WHERE id=?")
        ->execute([$id]);
}

/* FETCH */
$donations = $pdo->query("
    SELECT d.*, c.title AS campaign_title
    FROM donations d
    LEFT JOIN campaigns c ON d.campaign_id = c.id
    ORDER BY d.id DESC
")->fetchAll();
?>

<h2>Donations</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Name</th>
    <th>Amount</th>
    <th>UTR</th>
    <th>Status</th>
    <th>Proof</th>
    <th>Action</th>
    <th>Campaign</th>
</tr>

<?php foreach ($donations as $d): ?>
<tr>
    <td><?php echo $d['donor_name']; ?></td>
    <td>₹<?php echo $d['amount']; ?></td>
    <td><?php echo $d['transaction_id']; ?></td>
    <td><?php echo $d['status']; ?></td>

    <td>
        <?php if ($d['proof']): ?>
            <a href="../uploads/donations/<?php echo $d['proof']; ?>" target="_blank">View</a>
        <?php endif; ?>
    </td>

    <td>
        <?php if ($d['status'] === 'pending'): ?>
            <a href="?approve=<?php echo $d['id']; ?>">Approve</a> |
            <a href="?reject=<?php echo $d['id']; ?>">Reject</a>
        <?php else: ?>
            <span style="color:green;">Completed</span>
        <?php endif; ?>
    </td>
    <td><?php echo $d['campaign_title'] ?? 'General'; ?></td>
</tr>
<?php endforeach; ?>
</table>