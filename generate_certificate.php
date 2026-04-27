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
    SELECT u.name, m.referral_code, d.title, m.status
    FROM users u
    JOIN memberships m ON u.id = m.user_id
    JOIN designations d ON m.designation_id = d.id
    WHERE u.id = ?
    ORDER BY m.id DESC LIMIT 1
");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

if (!$data || $data['status'] !== 'active') {
    die("Certificate not available");
}

/* QR LINK */
$verify_url = "http://localhost/AUTONGO/verify_certificate.php?user=" . $user_id;

$qr_file = "uploads/certificates/qr_" . time() . "_" . $user_id . ".png";
QRcode::png($verify_url, $qr_file);
/* CHECK IF CERTIFICATE ALREADY EXISTS */
$stmt = $pdo->prepare("SELECT * FROM certificates WHERE user_id = ? AND type='membership' LIMIT 1");
$stmt->execute([$user_id]);
$existing = $stmt->fetch();

if ($existing && !empty($existing['pdf_path'])) {
    /* DIRECT DOWNLOAD EXISTING */
    header("Location: " . $existing['pdf_path']);
    exit;
}
/* CREATE PDF */
/* CREATE PDF */
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

/* Disable auto page break overflow issues */
$pdf->SetAutoPageBreak(false);

/* BORDER */
$pdf->Rect(10, 10, 277, 190);

/* LOGO */
$pdf->Image('assets/logo.jpg', 20, 15, 25);

/* NGO NAME */
$pdf->SetFont('Arial', 'B', 22);
$pdf->Cell(0, 12, 'YOUR NGO NAME', 0, 1, 'C');

/* SUBTITLE */
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, 'An Initiative Towards Social Welfare', 0, 1, 'C');

$pdf->Ln(5);

/* TITLE */
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(0, 12, 'Certificate of Membership', 0, 1, 'C');

$pdf->Ln(5);

/* TEXT */
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, 'This is to certify that', 0, 1, 'C');

$pdf->Ln(3);

/* NAME */
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 10, $data['name'], 0, 1, 'C');

$pdf->Ln(3);

/* DESCRIPTION */
$pdf->SetFont('Arial', '', 12);

/* Controlled width center block */
$pdf->SetX(40);
$pdf->MultiCell(210, 7,
    "has been officially enrolled as a " . $data['title'] . 
    " in our organization. This certificate is awarded in recognition of their contribution and commitment towards the mission and vision of our NGO.",
    0,
    'C'
);

$pdf->Ln(5);

/* DETAILS */
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, 'Member ID: ' . $data['referral_code'], 0, 1, 'C');
$pdf->Cell(0, 8, 'Date of Issue: ' . date('d M Y'), 0, 1, 'C');

/* SIGNATURE (fixed position) */
$pdf->SetXY(200, 160);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(60, 8, 'Authorized Signatory', 0, 1, 'C');

/* QR (fixed position) */
$pdf->Image($qr_file, 230, 120, 35, 35);

$pdf_path = "uploads/certificates/cert_" . time() . "_" . $user_id . ".pdf";

/* SAVE FILE */
$pdf->Output('F', $pdf_path);
$certificate_no = 'CERT' . time();

$pdo->prepare("
    INSERT INTO certificates 
    (user_id, name, type, template_id, qr_code, pdf_path, certificate_no)
    VALUES (?, ?, 'membership', 1, ?, ?, ?)
")->execute([
    $user_id,
    $data['name'],
    $qr_file,
    $pdf_path,
    $certificate_no
]);

/* ALSO SHOW TO USER */
$pdf->Output('I');