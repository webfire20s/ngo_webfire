<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

/* SEARCH */
$search = $_GET['search'] ?? '';

$query = "
    SELECT * FROM users 
    WHERE deleted_at IS NULL
";

$params = [];

if (!empty($search)) {
    $query .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $searchTerm = "%$search%";
    $params = [$searchTerm, $searchTerm, $searchTerm];
}

$query .= " ORDER BY id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll();

/* TOGGLE STATUS */
if (isset($_GET['toggle'])) {
    $id = (int) $_GET['toggle'];

    $pdo->prepare("
        UPDATE users 
        SET status = IF(status='active','inactive','active')
        WHERE id=?
    ")->execute([$id]);

    header("Location: users.php");
    exit;
}

/* SOFT DELETE */
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    $pdo->prepare("
        UPDATE users 
        SET deleted_at = NOW() 
        WHERE id=?
    ")->execute([$id]);

    header("Location: users.php");
    exit;
}
?>

<h2>User Management</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<br>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php foreach ($users as $u): ?>
<tr>
    <td><?php echo $u['id']; ?></td>
    <td><?php echo htmlspecialchars($u['name']); ?></td>
    <td><?php echo htmlspecialchars($u['email']); ?></td>
    <td><?php echo htmlspecialchars($u['phone']); ?></td>

    <td>
        <?php if ($u['status'] === 'active'): ?>
            <span style="color:green;">Active</span>
        <?php else: ?>
            <span style="color:red;">Inactive</span>
        <?php endif; ?>
    </td>

    <td>
        <a href="?toggle=<?php echo $u['id']; ?>">
            <?php echo $u['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>
        </a>
        |
        <a href="?delete=<?php echo $u['id']; ?>" 
           onclick="return confirm('Delete this user?')">
           Delete
        </a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php require '../includes/footer.php'; ?>