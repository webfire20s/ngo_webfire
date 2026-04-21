<?php
require '../includes/middleware_user.php';
require '../includes/db.php';
require '../includes/header.php';

$user_id = $_SESSION['user_id'];

/* GET USER INFO */
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

/* GET MEMBERSHIP */
$stmt = $pdo->prepare("
    SELECT m.*, d.title 
    FROM memberships m
    JOIN designations d ON m.designation_id = d.id
    WHERE m.user_id = ?
    ORDER BY m.id DESC LIMIT 1
");
$stmt->execute([$user_id]);
$membership = $stmt->fetch();

/* COUNT REFERRALS */
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM memberships 
    WHERE referred_by = ?
");
$stmt->execute([$user_id]);
$total_referrals = $stmt->fetchColumn();

/* GET TRANSACTIONS */
$stmt = $pdo->prepare("
    SELECT amount, payment_method, created_at 
    FROM transactions 
    WHERE user_id = ? AND type = 'membership'
");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>

<h1>Welcome, <?php echo $user['name']; ?></h1>

<hr>

<h3>Membership Details</h3>

<p>Status: <b><?php echo $membership['status']; ?></b></p>
<p>Designation: <?php echo $membership['title']; ?></p>
<p>Referral Code: <b><?php echo $membership['referral_code']; ?></b></p>

<?php if ($membership['status'] != 'active'): ?>

    <hr>
    <h3>Activate Membership</h3>
    <p>Your membership is inactive. Please complete payment.</p>

    <form method="POST" action="../pay.php">
        <input type="hidden" name="membership_id" value="<?php echo $membership['id']; ?>">
        <a href="../upi_payment.php?membership_id=<?php echo $membership['id']; ?>">
            Pay via UPI / QR
        </a>
    </form>

<?php endif; ?>
<hr>


<h3>Referral Stats</h3>
<p>Total Members Referred: <?php echo $total_referrals; ?></p>
<?php if ($membership['status'] == 'active'): ?>

    <hr>
    <a href="../generate_certificate.php" target="_blank">
        Download Certificate
    </a>

<?php endif; ?>
<?php if ($membership['status'] == 'active'): ?>

<hr>
<a href="../generate_id_card.php" target="_blank">
    Download ID Card
</a>

<?php endif; ?>

<hr>

<h3>Payment History</h3>

<table border="1" cellpadding="8">
<tr>
    <th>Amount</th>
    <th>Method</th>
    <th>Date</th>
</tr>

<?php foreach ($transactions as $t): ?>
<tr>
    <td>₹<?php echo $t['amount']; ?></td>
    <td>
    <?php 
        echo $t['payment_method'] 
            ? strtoupper($t['payment_method']) 
            : 'N/A'; 
    ?>
    </td>
    <td><?php echo $t['created_at']; ?></td>
</tr>
<?php endforeach; ?>

</table>
<a href="../generate_receipt.php?id=<?php echo $membership['id']; ?>">
    Download Receipt
</a>

<br><br>
<a href="../admin/logout.php">Logout</a>

<?php require '../includes/footer.php'; ?>