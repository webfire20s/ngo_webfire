<?php
session_start(); 
require 'includes/db.php';
$campaigns = $pdo->query("
    SELECT id, title 
    FROM campaigns 
    WHERE status='active'
")->fetchAll();

$selected_campaign = $_GET['campaign_id'] ?? '';
?>
<h2>Donate</h2>

<form method="POST" action="submit_donation.php" enctype="multipart/form-data">

    Name:<br>
    <input type="text" name="name" required><br><br>

    Email:<br>
    <input type="email" name="email"><br><br>

    Phone:<br>
    <input type="text" name="phone"><br><br>

    Amount:<br>
    <input type="number" name="amount" required><br><br>

    UTR:<br>
    <input type="text" name="utr" required><br><br>

    Upload Proof:<br>
    <input type="file" name="proof" accept="image/*" required><br><br>
    Campaign (optional):<br>
    <select name="campaign_id">
        <option value="">General Donation</option>
        <?php foreach ($campaigns as $c): ?>
            <option value="<?php echo $c['id']; ?>" 
            <?php if ($selected_campaign == $c['id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($c['title']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Donate</button>
</form>