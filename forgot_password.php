<?php
require 'includes/db.php';
require 'includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];

    /* CHECK USER */
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $stmt->execute([$email]);

    if (!$stmt->fetch()) {
        echo "Email not found";
        exit;
    }

    /* GENERATE TOKEN */
    $token = bin2hex(random_bytes(32));
    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    /* STORE TOKEN */
    $stmt = $pdo->prepare("
        INSERT INTO password_resets (email, token, expires_at)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$email, $token, $expiry]);

    /* RESET LINK */
    $link = "http://localhost/AUTONGO/reset_password.php?token=$token&email=$email";

    $subject = "Password Reset - Auto NGO";

    $body = "
    <p>Hello,</p>

    <p>Click below link to reset your password:</p>

    <p><a href='$link'>Reset Password</a></p>

    <p>This link will expire in 1 hour.</p>
    ";

    if (sendMail($email, $subject, $body)) {
        echo "Reset link sent to your email.";
    } else {
        echo "Failed to send email.";
    }
}
?>

<h2>Forgot Password</h2>

<form method="POST">
    Email:<br>
    <input type="email" name="email" required><br><br>
    <button type="submit">Send Reset Link</button>
</form>