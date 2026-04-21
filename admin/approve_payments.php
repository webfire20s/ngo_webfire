<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/functions.php';
require '../includes/header.php';
require '../includes/sidebar.php';

/* FETCH PENDING PAYMENTS */
$payments = $pdo->query("
    SELECT t.*, u.name, m.status as membership_status
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    JOIN memberships m ON t.reference_id = m.id
    WHERE t.status = 'pending'
")->fetchAll();

/* APPROVAL LOGIC */
if (isset($_GET['approve'])) {

    $id = (int) $_GET['approve'];

    try {
        $pdo->beginTransaction();

        /* LOCK TRANSACTION ROW */
        $stmt = $pdo->prepare("SELECT * FROM transactions WHERE id=? FOR UPDATE");
        $stmt->execute([$id]);
        $txn = $stmt->fetch();

        if (!$txn) {
            throw new Exception("Transaction not found");
        }

        /* VALIDATION */
        if ($txn['status'] !== 'pending') {
            throw new Exception("Already processed");
        }

        if ($txn['amount'] <= 0) {
            throw new Exception("Invalid amount");
        }

        /* CHECK MEMBERSHIP */
        $stmt = $pdo->prepare("SELECT * FROM memberships WHERE id=? FOR UPDATE");
        $stmt->execute([$txn['reference_id']]);
        $membership = $stmt->fetch();

        if (!$membership) {
            throw new Exception("Membership not found");
        }

        if ($membership['status'] === 'active') {
            throw new Exception("Membership already active");
        }

        /* UPDATE TRANSACTION */
        $pdo->prepare("
            UPDATE transactions 
            SET status='success' 
            WHERE id=?
        ")->execute([$id]);

        /* ACTIVATE MEMBERSHIP */
        $pdo->prepare("
            UPDATE memberships 
            SET status='active' 
            WHERE id=?
        ")->execute([$txn['reference_id']]);

        /* CREATE RECEIPT */
        $receipt_no = 'RCPT' . time();

        $pdo->prepare("
            INSERT INTO receipts (user_id, type, reference_id, receipt_no)
            VALUES (?, 'membership', ?, ?)
        ")->execute([$txn['user_id'], $txn['reference_id'], $receipt_no]);

        /* LOG ACTION */
        logAdminAction($pdo, $_SESSION['user_id'], "Approved payment TXN ID: " . $txn['id']);

        $pdo->commit();

        echo "<p style='color:green;'>Payment Approved Successfully</p>";

    } catch (Exception $e) {

        $pdo->rollBack();
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<h2>Pending Payments</h2>

<table border="1" cellpadding="10">
<tr>
    <th>User</th>
    <th>UTR</th>
    <th>Amount</th>
    <th>Membership Status</th>
    <th>Action</th>
</tr>

<?php foreach ($payments as $p): ?>
<tr>
    <td><?php echo htmlspecialchars($p['name']); ?></td>
    <td><?php echo htmlspecialchars($p['transaction_id']); ?></td>
    <td>₹<?php echo $p['amount']; ?></td>
    <td><?php echo $p['membership_status']; ?></td>
    <td>
        <a href="?approve=<?php echo $p['id']; ?>" 
           onclick="return confirm('Approve this payment?')">
           Approve
        </a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php require '../includes/footer.php'; ?>