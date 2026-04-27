<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

$membership_id = $_GET['membership_id'];
?>

<h2>UPI Payment</h2>

<p>Scan QR or pay via UPI ID:</p>

<img src="assets/qr.png" width="200"><br><br>

<p>UPI ID: <b>yourngo@upi</b></p>

<hr>

<form method="POST" action="submit_payment.php" enctype="multipart/form-data">
    
    <input type="hidden" name="membership_id" value="<?php echo $membership_id; ?>">

    Enter UTR / Transaction ID:<br>
    <input type="text" name="utr" required><br><br>

    Upload Payment Screenshot:<br>
    <input type="file" name="proof" accept="image/*" required><br><br>

    <button type="submit">Submit Payment</button>
</form>