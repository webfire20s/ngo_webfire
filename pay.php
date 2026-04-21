
session_start();
require 'includes/db.php';
require 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$membership_id = $_POST['membership_id'] ?? null;

if (!$membership_id) {
    die("Invalid request");
}

/* FETCH MEMBERSHIP */
$stmt = $pdo->prepare("
    SELECT m.*, d.fee 
    FROM memberships m
    JOIN designations d ON m.designation_id = d.id
    WHERE m.id = ? AND m.user_id = ?
");
$stmt->execute([$membership_id, $user_id]);
$data = $stmt->fetch();

if (!$data) {
    die("Membership not found");
}

$amount = $data['fee'];

/* TEMP: SIMULATE SUCCESS PAYMENT */

$txn_id = 'TXN' . time();

/* INSERT TRANSACTION */
$stmt = $pdo->prepare("
    INSERT INTO transactions 
    (user_id, type, reference_id, amount, payment_method, status, transaction_id)
    VALUES (?, 'membership', ?, ?, 'online', 'success', ?)
");
$stmt->execute([$user_id, $membership_id, $amount, $txn_id]);

/* ACTIVATE MEMBERSHIP */
$stmt = $pdo->prepare("
    UPDATE memberships 
    SET status = 'active'
    WHERE id = ?
");
$stmt->execute([$membership_id]);

/* CREATE RECEIPT */
$receipt_no = 'RCPT' . time();

$stmt = $pdo->prepare("
    INSERT INTO receipts 
    (user_id, type, reference_id, receipt_no, qr_code, pdf_path)
    VALUES (?, 'membership', ?, ?, '', '')
");
$stmt->execute([$user_id, $membership_id, $receipt_no]);

header("Location: user/dashboard.php?payment=success");
exit;