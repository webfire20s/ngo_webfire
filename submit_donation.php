<?php
session_start();
require 'includes/db.php';

$user_id = $_SESSION['user_id'] ?? null;

$name = $_POST['name'];
$email = $_POST['email'] ?? null;
$phone = $_POST['phone'] ?? null;
$amount = $_POST['amount'];
$utr = $_POST['utr'];
$campaign_id = !empty($_POST['campaign_id']) ? $_POST['campaign_id'] : null;
$payment_method = 'upi'; // since you're using UPI proof system
/* FILE UPLOAD */
$proof_name = null;

if (isset($_FILES['proof']) && $_FILES['proof']['error'] === 0) {

    $ext = strtolower(pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png'];

    if (!in_array($ext, $allowed)) {
        die("Invalid file type");
    }

    if (!is_dir('uploads/donations')) {
        mkdir('uploads/donations', 0777, true);
    }

    $proof_name = "don_" . time() . "." . $ext;

    move_uploaded_file($_FILES['proof']['tmp_name'], "uploads/donations/" . $proof_name);
}

/* INSERT */
$stmt = $pdo->prepare("
    INSERT INTO donations 
    (user_id, donor_name, donor_email, donor_phone, amount, transaction_id, proof, payment_method, status, campaign_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)
");

$stmt->execute([
    $user_id,
    $name,
    $email,
    $phone,
    $amount,
    $utr,
    $proof_name,
    $payment_method,
    $campaign_id
]);

echo "Donation submitted. Awaiting approval.";