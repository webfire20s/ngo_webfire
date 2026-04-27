<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

/* FILTERS */
$status = $_GET['status'] ?? '';
$method = $_GET['method'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$query = "
    SELECT t.*, u.name 
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    WHERE 1
";

$params = [];

/* APPLY FILTERS */
if (!empty($status)) {
    $query .= " AND t.status = ?";
    $params[] = $status;
}

if (!empty($method)) {
    $query .= " AND t.payment_method = ?";
    $params[] = $method;
}

if (!empty($from)) {
    $query .= " AND DATE(t.created_at) >= ?";
    $params[] = $from;
}

if (!empty($to)) {
    $query .= " AND DATE(t.created_at) <= ?";
    $params[] = $to;
}

$query .= " ORDER BY t.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$transactions = $stmt->fetchAll();
?>

<h2>Transactions Management</h2>

<form method="GET">
    Status:
    <select name="status">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="success">Success</option>
        <option value="failed">Failed</option>
    </select>

    Method:
    <select name="method">
        <option value="">All</option>
        <option value="upi">UPI</option>
        <option value="cash">Cash</option>
        <option value="bank">Bank</option>
    </select>

    From:
    <input type="date" name="from">

    To:
    <input type="date" name="to">

    <button type="submit">Filter</button>
</form>

<br>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Amount</th>
    <th>Method</th>
    <th>UTR</th>
    <th>Status</th>
    <th>Proof</th>
    <th>Date</th>
</tr>

<?php foreach ($transactions as $t): ?>
<tr>
    <td><?php echo $t['id']; ?></td>
    <td><?php echo htmlspecialchars($t['name']); ?></td>
    <td>₹<?php echo $t['amount']; ?></td>
    <td><?php echo strtoupper($t['payment_method']); ?></td>
    <td><?php echo htmlspecialchars($t['transaction_id']); ?></td>

    <td>
        <?php if ($t['status'] == 'success'): ?>
            <span style="color:green;">Success</span>
        <?php elseif ($t['status'] == 'pending'): ?>
            <span style="color:orange;">Pending</span>
        <?php else: ?>
            <span style="color:red;">Failed</span>
        <?php endif; ?>
    </td>

    <td>
        <?php if (!empty($t['proof'])): ?>
            <a href="../uploads/payments/<?php echo $t['proof']; ?>" target="_blank">
                View
            </a>
        <?php else: ?>
            —
        <?php endif; ?>
    </td>

    <td><?php echo $t['created_at']; ?></td>
</tr>
<?php endforeach; ?>
</table>

<?php require '../includes/footer.php'; ?>