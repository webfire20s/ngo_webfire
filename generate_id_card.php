<?php
session_start();
require 'includes/db.php';

require 'includes/fpdf/fpdf.php';
require 'includes/phpqrcode/qrlib.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];

/* FETCH MEMBER DATA */
$stmt = $pdo->prepare("
    SELECT u.name, u.email, u.profile_photo, m.referral_code, d.title, m.status
    FROM users u
    JOIN memberships m ON u.id = m.user_id
    JOIN designations d ON m.designation_id = d.id
    WHERE u.id = ?
    ORDER BY m.id DESC LIMIT 1
");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

if (!$data || $data['status'] !== 'active') {
    die("ID Card not available");
}

/* QR LINK */
$verify_url = "http://localhost/AUTONGO/verify_certificate.php?user=" . $user_id;

$qr_file = "uploads/id_qr_" . $user_id . ".png";
QRcode::png($verify_url, $qr_file);

/* CREATE SMALL ID CARD PDF */
/* CREATE ID CARD */
$pdf = new FPDF('L', 'mm', [86, 54]);
$pdf->AddPage();

/* Disable auto breaks */
$pdf->SetAutoPageBreak(false);

/* BORDER */
$pdf->SetDrawColor(0, 0, 0);
$pdf->Rect(2, 2, 82, 50);

/* ===== HEADER ===== */
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetXY(5, 5);
$pdf->Cell(76, 5, 'YOUR NGO NAME', 0, 1, 'C');

$pdf->SetFont('Arial', '', 7);
$pdf->SetX(5);
$pdf->Cell(76, 4, 'Social Welfare Organization', 0, 1, 'C');

/* ===== LEFT SIDE (PROFILE AREA) ===== */

/* Placeholder box (future photo support) */
$photo = $data['profile_photo'] ?? null;

if ($photo && file_exists("uploads/profile/" . $photo)) {
    $pdf->Image("uploads/profile/" . $photo, 5, 15, 22, 25);
} else {
    /* fallback box */
    $pdf->Rect(5, 15, 22, 25);
}

/* Name */
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(5, 42);
$pdf->MultiCell(22, 4, $data['name'], 0, 'C');

/* ===== RIGHT SIDE (DETAILS) ===== */

$pdf->SetXY(30, 16);

/* NAME LABEL */
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(30, 4, 'NAME', 0, 1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetX(30);
$pdf->Cell(30, 4, $data['name'], 0, 1);

/* DESIGNATION */
$pdf->SetFont('Arial', '', 6);
$pdf->SetX(30);
$pdf->Cell(30, 4, 'DESIGNATION', 0, 1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetX(30);
$pdf->Cell(30, 4, $data['title'], 0, 1);

/* MEMBER ID */
$pdf->SetFont('Arial', '', 6);
$pdf->SetX(30);
$pdf->Cell(30, 4, 'MEMBER ID', 0, 1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetX(30);
$pdf->Cell(30, 4, $data['referral_code'], 0, 1);

/* ===== QR SECTION ===== */

$pdf->SetFont('Arial', 'B', 6);
$pdf->SetXY(58, 16);
$pdf->Cell(24, 4, 'SCAN', 0, 1, 'C');

$pdf->Image($qr_file, 58, 20, 24, 24);

/* ===== FOOTER ===== */

$pdf->SetFont('Arial', 'I', 6);
$pdf->SetXY(5, 48);
$pdf->Cell(76, 3, 'Together We Can Make A Difference', 0, 0, 'C');

$pdf->Output();