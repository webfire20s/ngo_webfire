<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="members.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Name', 'Email', 'Phone', 'Designation', 'Status', 'Join Date']);

$stmt = $pdo->query("
    SELECT u.name, u.email, u.phone, d.title, m.status, m.join_date
    FROM users u
    JOIN memberships m ON u.id = m.user_id
    JOIN designations d ON m.designation_id = d.id
    ORDER BY m.id DESC
");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
exit;