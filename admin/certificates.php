<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

/* SEARCH */
$search = $_GET['search'] ?? '';

$query = "
    SELECT c.*, u.name 
    FROM certificates c
    JOIN users u ON c.user_id = u.id
    WHERE c.type='membership'
";

$params = [];

if (!empty($search)) {
    $query .= " AND u.name LIKE ?";
    $params[] = "%$search%";
}

$query .= " ORDER BY c.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$certificates = $stmt->fetchAll();
?>

<h2>Certificates Management</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Search user">
    <button type="submit">Search</button>
</form>

<br>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Certificate No</th>
    <th>User</th>
    <th>Issued At</th>
    <th>Action</th>
</tr>

<?php foreach ($certificates as $c): ?>
<tr>
    <td><?php echo $c['id']; ?></td>
    <td><?php echo $c['certificate_no']; ?></td>
    <td><?php echo htmlspecialchars($c['name']); ?></td>
    <td><?php echo $c['issued_at']; ?></td>

    <td>
        <a href="../<?php echo $c['pdf_path']; ?>" target="_blank">Download</a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php require '../includes/footer.php'; ?>