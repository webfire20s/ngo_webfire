<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/header.php';
require '../includes/sidebar.php';

/* FILTERS */
$search = $_GET['search'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$query = "
    SELECT r.*, 
           u.name AS user_name,
           d.donor_name
    FROM receipts r
    LEFT JOIN users u ON r.user_id = u.id
    LEFT JOIN donations d 
        ON r.reference_id = d.id AND r.type='donation'
    WHERE 1
";

$params = [];

/* SEARCH */
if (!empty($search)) {
        $query .= " AND (
        u.name LIKE ? 
        OR d.donor_name LIKE ? 
        OR r.receipt_no LIKE ?
    )";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
    $term = "%$search%";
    $params[] = $term;
    $params[] = $term;
}

/* DATE FILTER */
if (!empty($from)) {
    $query .= " AND DATE(r.created_at) >= ?";
    $params[] = $from;
}

if (!empty($to)) {
    $query .= " AND DATE(r.created_at) <= ?";
    $params[] = $to;
}

$query .= " ORDER BY r.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$receipts = $stmt->fetchAll();
?>

<h2>Receipts Management</h2>

<form method="GET">
    Search:
    <input type="text" name="search" placeholder="Name / Receipt No">

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
    <th>Receipt No</th>
    <th>User</th>
    <th>Amount</th>
    <th>Method</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php foreach ($receipts as $r): ?>
<tr>
    <td><?php echo $r['id']; ?></td>
    <td><?php echo htmlspecialchars($r['receipt_no']); ?></td>
    <td>
        <?php 
        echo htmlspecialchars(
            $r['type'] === 'donation' 
            ? ($r['donor_name'] ?? 'Guest') 
            : $r['user_name']
        ); 
        ?>
        </td>

        <td>₹<?php echo $r['amount']; ?></td>

        <td><?php echo strtoupper($r['payment_method'] ?? '-'); ?></td>
    <td><?php echo $r['created_at']; ?></td>

    <td>
        <a href="../generate_receipt.php?id=<?php echo $r['id']; ?>" target="_blank">
            View / Download
        </a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php require '../includes/footer.php'; ?>