<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!verifyToken($_POST['csrf_token'])) {
        die("Invalid CSRF Token");
    }

    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("
        SELECT * FROM users 
        WHERE email = ? 
        AND deleted_at IS NULL 
        AND status = 'active'
        LIMIT 1
    ");
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: ../user/dashboard.php");
        }
        exit;

    } else {
        $error = "Invalid credentials";
    }
}
?>

<form method="POST">
    <h2>Admin Login</h2>
    <input type="email" name="email" required placeholder="Email"><br><br>
    <input type="password" name="password" required placeholder="Password"><br><br>
    <input type="hidden" name="csrf_token" value="<?php echo generateToken(); ?>">
    <button type="submit">Login</button>
</form>

<?php if(isset($error)) echo $error; ?>