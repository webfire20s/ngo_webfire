<?php
require 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("
    SELECT *
    FROM pages
    WHERE slug=?
    LIMIT 1
");

$stmt->execute([$slug]);

$page = $stmt->fetch();

if(!$page){
    echo "<h2 style='padding:40px;'>Page not found</h2>";
    include 'includes/web_footer.php';
    exit;
}
?>

<section class="pt-32 pb-20 bg-white">
    <div class="max-w-5xl mx-auto px-6">

        <h1 class="text-5xl font-bold mb-10">
            <?php echo htmlspecialchars($page['title']); ?>
        </h1>

        <div class="prose max-w-none">
            <?php echo nl2br($page['content']); ?>
        </div>

    </div>
</section>

<?php include 'includes/web_footer.php'; ?>