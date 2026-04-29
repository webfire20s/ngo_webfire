<?php
require 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

/* FETCH ACTIVE CAMPAIGNS */
$campaigns = $pdo->query("
    SELECT * FROM campaigns 
    WHERE status='active'
    ORDER BY id DESC
")->fetchAll();
?>

<h2 style="text-align:center;">Our Campaigns</h2>

<div style="display:flex; flex-wrap:wrap; gap:20px; justify-content:center;">

<?php foreach ($campaigns as $c): 

    $goal = $c['goal_amount'];
    $collected = $c['collected_amount'];
    $percent = $goal > 0 ? min(100, ($collected / $goal) * 100) : 0;
?>
<a href="campaign_detail.php?id=<?php echo $c['id']; ?>" 
   style="text-decoration:none; color:inherit;">
    <div style="width:300px; border:1px solid #ccc; padding:15px; border-radius:10px; cursor:pointer;">

        <h3><?php echo htmlspecialchars($c['title']); ?></h3>

        <p style="height:60px; overflow:hidden;">
            <?php echo htmlspecialchars(substr($c['description'], 0, 100)); ?>...
        </p>

        <!-- PROGRESS BAR -->
        <div style="background:#eee; border-radius:5px; height:10px;">
            <div style="
                width:<?php echo $percent; ?>%;
                background:green;
                height:10px;
                border-radius:5px;">
            </div>
        </div>

        <p>
            ₹<?php echo $collected; ?> raised of ₹<?php echo $goal; ?>
        </p>

        <!-- DONATE BUTTON -->
        <a href="donate.php?campaign_id=<?php echo $c['id']; ?>" 
            onclick="event.stopPropagation();">
            <button type="button">Donate</button>
        </a>
        
    </div>
</a>

<?php endforeach; ?>

</div>

<?php include 'includes/web_footer.php'; ?>