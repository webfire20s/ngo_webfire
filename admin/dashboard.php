<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

/* BASIC METRICS */
$totalUsers = $pdo->query("
    SELECT COUNT(*) FROM users WHERE deleted_at IS NULL
")->fetchColumn();

$totalDonations = $pdo->query("
    SELECT SUM(amount) FROM donations
")->fetchColumn();

/* NEW METRICS */
$activeMembers = $pdo->query("
    SELECT COUNT(*) FROM memberships WHERE status = 'active'
")->fetchColumn();

$totalRevenue = $pdo->query("
    SELECT SUM(amount) FROM transactions WHERE status = 'success'
")->fetchColumn();

/* PAYMENT METHOD BREAKDOWN */
$methods = $pdo->query("
    SELECT payment_method, COUNT(*) as total
    FROM transactions
    WHERE status = 'success'
    GROUP BY payment_method
")->fetchAll();

/* RECENT TRANSACTIONS */
$transactions = $pdo->query("
    SELECT t.amount, t.payment_method, u.name, t.created_at
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    WHERE t.status = 'success'
    ORDER BY t.id DESC
    LIMIT 5
")->fetchAll();

/* TOP REFERRERS */
$referrers = $pdo->query("
    SELECT u.name, COUNT(m.id) as total
    FROM memberships m
    JOIN users u ON m.referred_by = u.id
    GROUP BY m.referred_by
    ORDER BY total DESC
    LIMIT 5
")->fetchAll();
?>

<h1>Admin Dashboard</h1>

<hr>

<h3>Overview</h3>

<p>Total Users: <b><?php echo $totalUsers; ?></b></p>
<p>Active Members: <b><?php echo $activeMembers; ?></b></p>
<p>Total Donations: <b>₹<?php echo $totalDonations ?? 0; ?></b></p>
<p>Total Revenue (Membership): <b>₹<?php echo $totalRevenue ?? 0; ?></b></p>

<hr>

<h3>Payment Methods</h3>

<table border="1" cellpadding="8">
<tr>
    <th>Method</th>
    <th>Total Transactions</th>
</tr>

<?php foreach ($methods as $m): ?>
<tr>
    <td><?php echo strtoupper($m['payment_method']); ?></td>
    <td><?php echo $m['total']; ?></td>
</tr>
<?php endforeach; ?>
</table>

<hr>

<h3>Recent Transactions</h3>

<table border="1" cellpadding="8">
<tr>
    <th>Name</th>
    <th>Amount</th>
    <th>Method</th>
    <th>Date</th>
</tr>

<?php foreach ($transactions as $t): ?>
<tr>
    <td><?php echo htmlspecialchars($t['name']); ?></td>
    <td>₹<?php echo $t['amount']; ?></td>
    <td><?php echo strtoupper($t['payment_method']); ?></td>
    <td><?php echo $t['created_at']; ?></td>
</tr>
<?php endforeach; ?>
</table>

<hr>

<h3>Top Referrers</h3>

<table border="1" cellpadding="8">
<tr>
    <th>Name</th>
    <th>Total Referrals</th>
</tr>

<?php foreach ($referrers as $r): ?>
<tr>
    <td><?php echo htmlspecialchars($r['name']); ?></td>
    <td><?php echo $r['total']; ?></td>
</tr>
<?php endforeach; ?>
</table>

<?php require '../includes/footer.php'; ?>