<?php
require 'includes/db.php';

$receipt_id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT r.*, u.name, t.amount
    FROM receipts r
    JOIN users u ON r.user_id = u.id
    JOIN transactions t ON r.reference_id = t.reference_id
    WHERE r.id = ?
");
$stmt->execute([$receipt_id]);
$data = $stmt->fetch();

if (!$data) {
    die("Invalid receipt");
}
?>

<h2>Receipt Verification</h2>

<p><b>Name:</b> <?php echo $data['name']; ?></p>
<p><b>Amount:</b> ₹<?php echo $data['amount']; ?></p>
<p><b>Receipt No:</b> <?php echo $data['receipt_no']; ?></p>

<p style="color:green;"><b>✔ Verified Payment</b></p>