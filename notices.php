<?php
require 'includes/db.php';

$notices = $pdo->query("SELECT * FROM notices ORDER BY id DESC")->fetchAll();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<h2>Notices</h2>

<?php foreach ($notices as $n): ?>
<div style="border:1px solid #ccc; margin:10px; padding:10px;">
    <h3><?php echo htmlspecialchars($n['title']); ?></h3>
    <p><?php echo nl2br($n['description']); ?></p>
</div>
<?php endforeach; ?>

<?php include 'includes/web_footer.php'; ?>