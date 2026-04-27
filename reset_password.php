<?php
require 'includes/db.php';
date_default_timezone_set('Asia/Kolkata');

$token = trim($_GET['token'] ?? '');
$email = trim($_GET['email'] ?? '');

if (!$token || !$email) {
    die("Invalid request");
}

if (!$token) {
    die("Invalid request");
}

/* VERIFY TOKEN */
$stmt = $pdo->prepare("
    SELECT * FROM password_resets 
    WHERE token = ? 
    AND email = ?
");
$stmt->execute([$token, $email]);

$data = $stmt->fetch();
echo "TOKEN FROM URL: " . $_GET['token'] . "<br>";
if (!$data) {
    die("Invalid or expired token");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    /* DELETE TOKEN */
    $pdo->prepare("DELETE FROM password_resets WHERE email=?")
        ->execute([$data['email']]);

    /* UPDATE PASSWORD */
    $stmt = $pdo->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->execute([$password, $data['email']]);


    echo "Password reset successful. You can now login.";
    exit;
}
?>

<h2>Reset Password</h2>

<form method="POST">
    New Password:<br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Reset Password</button>
</form>