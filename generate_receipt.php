<?php
require 'includes/db.php';

require 'includes/fpdf/fpdf.php';
require 'includes/phpqrcode/qrlib.php';

$receipt_id = $_GET['id'] ?? null;

if (!$receipt_id) {
    die("Invalid request");
}

/* FETCH RECEIPT DATA */
$stmt = $pdo->prepare("
    SELECT r.*, 
           u.name AS user_name,
           u.email,
           d.donor_name,
           d.donor_email
    FROM receipts r
    LEFT JOIN users u ON r.user_id = u.id
    LEFT JOIN donations d 
        ON r.reference_id = d.id AND r.type='donation'
    WHERE r.id = ?
");
$stmt->execute([$receipt_id]);
$data = $stmt->fetch();

if (!$data) {
    die("Invalid receipt");
}

/* DECIDE VALUES */
$name = ($data['type'] === 'donation') 
        ? ($data['donor_name'] ?? 'Guest') 
        : $data['user_name'];

$email = ($data['type'] === 'donation') 
        ? ($data['donor_email'] ?? '-') 
        : $data['email'];

$amount = $data['amount'];
$method = $data['payment_method'];

/* QR CONTENT (verification link) */
$verify_url = "http://localhost/AUTONGO/verify_receipt.php?id=" . $receipt_id;

/* GENERATE QR IMAGE */
$qr_file = "uploads/qr_" . $receipt_id . ".png";
QRcode::png($verify_url, $qr_file);

/* CREATE PDF */
$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'NGO Payment Receipt', 0, 1, 'C');

$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);

$pdf->Cell(0, 10, "Name: " . $name, 0, 1);
$pdf->Cell(0, 10, "Email: " . $email, 0, 1);
$pdf->Cell(0, 10, "Amount: Rs. " . $amount, 0, 1);
$pdf->Cell(0, 10, "Method: " . strtoupper($method), 0, 1);
$pdf->Cell(0, 10, "Receipt No: " . $data['receipt_no'], 0, 1);

$pdf->Ln(10);

/* ADD QR */
$pdf->Image($qr_file, 80, 120, 50, 50);

$pdf->Output();