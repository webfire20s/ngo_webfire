<?php
session_start();
require 'includes/db.php';

$user_id = $_SESSION['user_id'];
$membership_id = $_POST['membership_id'];
$utr = $_POST['utr'];


$stmt = $pdo->prepare("
    SELECT d.fee 
    FROM memberships m
    JOIN designations d ON m.designation_id = d.id
    WHERE m.id = ? AND m.user_id = ?
");
$stmt->execute([$membership_id, $user_id]);
$data = $stmt->fetch();

$amount = $data['fee'];
/* SAVE PENDING PAYMENT */
$stmt = $pdo->prepare("
    INSERT INTO transactions 
    (user_id, type, reference_id, amount, payment_method, status, transaction_id)
    VALUES (?, 'membership', ?, ?, 'upi', 'pending', ?)
");
$stmt->execute([$user_id, $membership_id, $amount, $utr]);

echo "Payment submitted. Waiting for admin approval.";