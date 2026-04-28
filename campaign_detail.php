<?php
require 'includes/db.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM campaigns WHERE id=?");
$stmt->execute([$id]);
$c = $stmt->fetch();

if (!$c) {
    die("Campaign not found");
}

$percent = $c['goal_amount'] > 0 
    ? min(100, ($c['collected_amount'] / $c['goal_amount']) * 100) 
    : 0;
?>

<h2><?php echo $c['title']; ?></h2>

<p><?php echo nl2br($c['description']); ?></p>

<div style="background:#eee; height:15px;">
    <div style="width:<?php echo $percent; ?>%; background:green; height:15px;"></div>
</div>

<p>
₹<?php echo $c['collected_amount']; ?> / ₹<?php echo $c['goal_amount']; ?>
</p>

<a href="donate.php?campaign_id=<?php echo $c['id']; ?>">
    <button>Donate to this Campaign</button>
</a>