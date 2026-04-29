<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/functions.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

/* FETCH MEMBERS WITH PENDING MEMBERSHIP */
$members = $pdo->query("
    SELECT m.id as membership_id, u.name, u.email, d.title, d.fee
    FROM memberships m
    JOIN users u ON m.user_id = u.id
    JOIN designations d ON m.designation_id = d.id
    WHERE m.status = 'expired'
")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!verifyToken($_POST['csrf_token'])) {
        die("Invalid CSRF Token");
    }

    $membership_id = $_POST['membership_id'];

    /* GET MEMBERSHIP DETAILS */
    $stmt = $pdo->prepare("
        SELECT m.*, u.id as user_id, d.fee 
        FROM memberships m
        JOIN users u ON m.user_id = u.id
        JOIN designations d ON m.designation_id = d.id
        WHERE m.id = ?
    ");
    $stmt->execute([$membership_id]);
    $data = $stmt->fetch();

    if ($data) {

        $amount = $data['fee'];
        $user_id = $data['user_id'];

        /* 1. CREATE TRANSACTION */
        $txn_id = 'TXN' . time();

        $stmt = $pdo->prepare("
            INSERT INTO transactions 
            (user_id, type, reference_id, amount, payment_method, status, transaction_id)
            VALUES (?, 'membership', ?, ?, 'cash', 'success', ?)
        ");
        $stmt->execute([$user_id, $membership_id, $amount, $txn_id]);

        /* 2. ACTIVATE MEMBERSHIP */
        $stmt = $pdo->prepare("
            UPDATE memberships 
            SET status = 'active'
            WHERE id = ?
        ");
        $stmt->execute([$membership_id]);

        /* 3. CREATE RECEIPT */
        $receipt_no = 'RCPT' . time();

        $stmt = $pdo->prepare("
            INSERT INTO receipts 
            (user_id, type, reference_id, receipt_no, qr_code, pdf_path)
            VALUES (?, 'membership', ?, ?, '', '')
        ");
        $stmt->execute([$user_id, $membership_id, $receipt_no]);

        logAdminAction($pdo, $_SESSION['user_id'], "Membership payment done for user ID: $user_id");

        echo "<p style='color:green;'>Payment Successful & Membership Activated</p>";
    }
}
?>

<h2>Membership Payments</h2>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo generateToken(); ?>">

    Select Member:<br>
    <select name="membership_id" required>
        <?php foreach ($members as $m): ?>
            <option value="<?php echo $m['membership_id']; ?>">
                <?php echo $m['name']; ?> (<?php echo $m['title']; ?> - ₹<?php echo $m['fee']; ?>)
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Mark as Paid</button>
</form>

<?php require '../includes/footer.php'; ?>