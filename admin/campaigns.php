<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

$campaigns = $pdo->query("SELECT * FROM campaigns ORDER BY id DESC")->fetchAll();
?>

<h2>Campaigns</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Title</th>
    <th>Target</th>
    <th>Collected</th>
    <th>Status</th>
</tr>

<?php foreach ($campaigns as $c): ?>
<tr>
    <td><?php echo $c['title']; ?></td>
    <td>₹<?php echo $c['goal_amount']; ?></td>
    <td>₹<?php echo $c['collected_amount']; ?></td>
    <td><?php echo $c['status']; ?></td>
</tr>
<?php endforeach; ?>
</table>