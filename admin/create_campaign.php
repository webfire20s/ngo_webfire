<?php
require '../includes/middleware_admin.php';
require '../includes/db.php';
require '../includes/admin_header.php';
require '../includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $desc = $_POST['description'];
    $target = $_POST['target'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    $stmt = $pdo->prepare("
        INSERT INTO campaigns 
        (title, description, goal_amount, start_date, end_date)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$title, $desc, $target, $start, $end]);

    echo "Campaign Created!";
}
?>

<h2>Create Campaign</h2>

<form method="POST">
    Title:<br>
    <input type="text" name="title" required><br><br>

    Description:<br>
    <textarea name="description"></textarea><br><br>

    Target Amount:<br>
    <input type="number" name="target" required><br><br>

    Start Date:<br>
    <input type="date" name="start"><br><br>

    End Date:<br>
    <input type="date" name="end"><br><br>

    <button type="submit">Create</button>
</form>