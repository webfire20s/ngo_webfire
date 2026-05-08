<?php
require 'includes/db.php';

$stmt = $pdo->query("
    SELECT u.name, m.referral_code, d.title
    FROM users u
    JOIN memberships m ON u.id = m.user_id
    JOIN designations d ON m.designation_id = d.id
    WHERE m.status = 'active'
    ORDER BY u.name ASC
");

$members = $stmt->fetchAll();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<h2>Our Members</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Name</th>
    <th>Designation</th>
    <th>Member ID</th>
</tr>

<?php foreach ($members as $m): ?>
<tr>
    <td><?php echo htmlspecialchars($m['name']); ?></td>
    <td><?php echo htmlspecialchars($m['title']); ?></td>
    <td><?php echo $m['referral_code']; ?></td>
</tr>
<?php endforeach; ?>

</table>

<?php include 'includes/web_footer.php'; ?>