<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE deleted_at IS NULL")->fetchColumn();
$totalDonations = $pdo->query("SELECT SUM(amount) FROM donations")->fetchColumn();
?>

<h1>Dashboard</h1>

<p>Total Users: <?php echo $totalUsers; ?></p>
<p>Total Donations: ₹<?php echo $totalDonations ?? 0; ?></p>

<?php require '../includes/footer.php'; ?>