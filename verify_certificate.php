<?php
require 'includes/db.php';

$user_id = $_GET['user'] ?? null;

if (!$user_id) {
    die("Invalid certificate");
}

$stmt = $pdo->prepare("
    SELECT u.name, d.title, m.status
    FROM users u
    JOIN memberships m ON u.id = m.user_id
    JOIN designations d ON m.designation_id = d.id
    WHERE u.id = ?
    ORDER BY m.id DESC LIMIT 1
");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

if (!$data || $data['status'] !== 'active') {
    die("Certificate invalid or inactive");
}
?>

<h2>Certificate Verification</h2>

<p><b>Name:</b> <?php echo $data['name']; ?></p>
<p><b>Designation:</b> <?php echo $data['title']; ?></p>

<p style="color:green;"><b>✔ Valid Active Member</b></p>