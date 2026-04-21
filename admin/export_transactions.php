<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';

/* HEADERS FOR DOWNLOAD */
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="transactions.csv"');

/* OUTPUT STREAM */
$output = fopen('php://output', 'w');

/* CSV HEADER */
fputcsv($output, ['Name', 'Amount', 'Method', 'Status', 'Date']);

/* OPTIONAL FILTER */
$start = $_GET['start'] ?? null;
$end = $_GET['end'] ?? null;

$query = "
    SELECT u.name, t.amount, t.payment_method, t.status, t.created_at
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    WHERE t.status = 'success'
";

$params = [];

if ($start && $end) {
    $query .= " AND DATE(t.created_at) BETWEEN ? AND ?";
    $params = [$start, $end];
}

$query .= " ORDER BY t.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);

/* WRITE DATA */
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
exit;