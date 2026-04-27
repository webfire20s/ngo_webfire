<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$membership_id = $_POST['membership_id'];
$utr = $_POST['utr'];

/* =========================
   PROOF UPLOAD START
========================= */
$proof_name = null;

if (isset($_FILES['proof']) && $_FILES['proof']['error'] === 0) {

    $file = $_FILES['proof'];

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png'];

    if (!in_array($ext, $allowed)) {
        die("Only JPG, JPEG, PNG allowed");
    }

    if ($file['size'] > 3 * 1024 * 1024) {
        die("Max file size is 3MB");
    }

    /* CREATE FOLDER IF NOT EXISTS */
    if (!is_dir('uploads/payments')) {
        mkdir('uploads/payments', 0777, true);
    }

    /* UNIQUE FILE NAME */
    $proof_name = "txn_" . time() . "_" . rand(1000,9999) . "." . $ext;

    $path = "uploads/payments/" . $proof_name;

    if (!move_uploaded_file($file['tmp_name'], $path)) {
        die("File upload failed");
    }
}
/* =========================
   PROOF UPLOAD END
========================= */


/* FETCH AMOUNT SAFELY */
$stmt = $pdo->prepare("
    SELECT d.fee 
    FROM memberships m
    JOIN designations d ON m.designation_id = d.id
    WHERE m.id = ? AND m.user_id = ?
");
$stmt->execute([$membership_id, $user_id]);
$data = $stmt->fetch();

if (!$data) {
    die("Invalid membership");
}

$amount = $data['fee'];

/* SAVE TRANSACTION WITH PROOF */
$stmt = $pdo->prepare("
    INSERT INTO transactions 
    (user_id, type, reference_id, amount, payment_method, status, transaction_id, proof)
    VALUES (?, 'membership', ?, ?, 'upi', 'pending', ?, ?)
");

$stmt->execute([
    $user_id,
    $membership_id,
    $amount,
    $utr,
    $proof_name
]);

echo "Payment submitted successfully. Waiting for admin approval.";