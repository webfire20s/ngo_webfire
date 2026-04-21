<?php
require 'includes/db.php';

require 'includes/fpdf/fpdf.php';
require 'includes/phpqrcode/qrlib.php';

$receipt_id = $_GET['id'];

/* FETCH RECEIPT DATA */
$stmt = $pdo->prepare("
    SELECT r.*, u.name, u.email, t.amount
    FROM receipts r
    JOIN users u ON r.user_id = u.id
    JOIN transactions t ON t.reference_id = r.reference_id AND t.type='membership'
    WHERE r.id = ?
");
$stmt->execute([$receipt_id]);
$data = $stmt->fetch();

if (!$data) {
    die("Invalid receipt");
}

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

$pdf->Cell(0, 10, "Name: " . $data['name'], 0, 1);
$pdf->Cell(0, 10, "Email: " . $data['email'], 0, 1);
$pdf->Cell(0, 10, "Amount: Rs. " . $data['amount'], 0, 1);
$pdf->Cell(0, 10, "Receipt No: " . $data['receipt_no'], 0, 1);

$pdf->Ln(10);

/* ADD QR */
$pdf->Image($qr_file, 80, 120, 50, 50);

$pdf->Output();