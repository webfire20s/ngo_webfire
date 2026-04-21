<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/functions.php';
require '../includes/header.php';
require '../includes/sidebar.php';

/* FETCH DESIGNATIONS */
$designations = $pdo->query("SELECT * FROM designations")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!verifyToken($_POST['csrf_token'])) {
        die("Invalid CSRF Token");
    }

    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $designation_id = $_POST['designation_id'];
    $referral = sanitize($_POST['referral'] ?? '');

    /* CHECK DUPLICATE EMAIL */
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetch()) {
        echo "<p style='color:red;'>Email already exists</p>";
    } else {

        /* CREATE USER */
        $password = password_hash("123456", PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, phone, password, role)
            VALUES (?, ?, ?, ?, 'member')
        ");
        $stmt->execute([$name, $email, $phone, $password]);

        $user_id = $pdo->lastInsertId();

        /* HANDLE REFERRAL */
        $referred_by = null;
        if (!empty($referral)) {
            $refCheck = $pdo->prepare("SELECT user_id FROM memberships WHERE referral_code = ?");
            $refCheck->execute([$referral]);
            $refUser = $refCheck->fetch();
            if ($refUser) {
                $referred_by = $refUser['user_id'];
            }
        }

        /* GENERATE REFERRAL CODE */
        $referral_code = strtoupper(substr(md5(uniqid()), 0, 8));

        /* CREATE MEMBERSHIP (PENDING) */
        $stmt = $pdo->prepare("
            INSERT INTO memberships 
            (user_id, designation_id, join_date, expiry_date, status, referral_code, referred_by)
            VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 'expired', ?, ?)
        ");
        $stmt->execute([
            $user_id,
            $designation_id,
            $referral_code,
            $referred_by
        ]);

        logAdminAction($pdo, $_SESSION['user_id'], "Created member: $email");

        echo "<p style='color:green;'>Member created successfully (Pending Payment)</p>";
    }
}
?>

<h2>Create Member</h2>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo generateToken(); ?>">

    Name:<br>
    <input type="text" name="name" required><br><br>

    Email:<br>
    <input type="email" name="email" required><br><br>

    Phone:<br>
    <input type="text" name="phone" required><br><br>

    Designation:<br>
    <select name="designation_id" required>
        <?php foreach ($designations as $d): ?>
            <option value="<?php echo $d['id']; ?>">
                <?php echo $d['title']; ?> (₹<?php echo $d['fee']; ?>)
            </option>
        <?php endforeach; ?>
    </select><br><br>

    Referral Code (optional):<br>
    <input type="text" name="referral"><br><br>

    <button type="submit">Create Member</button>
</form>

<?php require '../includes/footer.php'; ?>