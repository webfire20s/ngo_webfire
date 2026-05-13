<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

$user_id = $_SESSION['user_id'] ?? null;

$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$phone = $_POST['phone'] ?? null;
$amount = $_POST['amount'] ?? null;
$utr = $_POST['utr'] ?? null;
$campaign_id = !empty($_POST['campaign_id']) ? $_POST['campaign_id'] : null;
$payment_method = 'upi'; 

/* FILE UPLOAD */
$proof_name = null;
if (isset($_FILES['proof']) && $_FILES['proof']['error'] === 0) {
    $ext = strtolower(pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png'];

    if (!in_array($ext, $allowed)) {
        die("<div class='h-screen flex items-center justify-center font-bold uppercase tracking-widest text-red-500'>Invalid file type</div>");
    }

    if (!is_dir('uploads/donations')) {
        mkdir('uploads/donations', 0777, true);
    }

    $proof_name = "don_" . time() . "." . $ext;
    move_uploaded_file($_FILES['proof']['tmp_name'], "uploads/donations/" . $proof_name);
}

/* INSERT */
$stmt = $pdo->prepare("
    INSERT INTO donations 
    (user_id, donor_name, donor_email, donor_phone, amount, transaction_id, proof, payment_method, status, campaign_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)
");

$success = $stmt->execute([
    $user_id,
    $name,
    $email,
    $phone,
    $amount,
    $utr,
    $proof_name,
    $payment_method,
    $campaign_id
]);
?>

<section class="min-h-screen flex items-center justify-center bg-white px-6">
    <div class="max-w-xl w-full text-center" data-aos="zoom-in">
        
        <!-- Animated Icon Container -->
        <div class="relative w-32 h-32 mx-auto mb-12">
            <div class="absolute inset-0 bg-green-50 rounded-full animate-ping opacity-20"></div>
            <div class="relative w-32 h-32 bg-black rounded-full flex items-center justify-center text-white shadow-2xl">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <span class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-400 mb-6 block">Transaction Received</span>
        <h1 class="text-5xl font-bold brand-font tracking-tighter mb-6 italic">Thank You, <br><?php echo htmlspecialchars($name); ?>.</h1>
        
        <p class="text-gray-500 font-light leading-relaxed mb-12 max-w-sm mx-auto">
            Your donation of <span class="text-black font-bold">₹<?php echo number_format($amount); ?></span> has been submitted. Our team is verifying the UTR <span class="text-black font-mono text-xs underline"><?php echo htmlspecialchars($utr); ?></span>. You will receive an update shortly.
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-col md:flex-row gap-4 justify-center">
            <a href="index.php" class="bg-black text-white px-10 py-5 rounded-full text-[11px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition-all shadow-xl active:scale-95">
                Return Home
            </a>
            <a href="campaigns.php" class="bg-gray-50 text-gray-400 px-10 py-5 rounded-full text-[11px] font-bold uppercase tracking-[0.3em] hover:text-black transition-all active:scale-95">
                View Other Campaigns
            </a>
        </div>

        <!-- Verification Timeline -->
        <div class="mt-20 pt-12 border-t border-gray-50 grid grid-cols-3 gap-4">
            <div class="space-y-2">
                <div class="h-1 bg-black rounded-full w-full"></div>
                <p class="text-[8px] font-bold uppercase tracking-widest text-black">Submitted</p>
            </div>
            <div class="space-y-2">
                <div class="h-1 bg-gray-100 rounded-full w-full relative">
                    <div class="absolute inset-0 bg-black/20 animate-pulse rounded-full"></div>
                </div>
                <p class="text-[8px] font-bold uppercase tracking-widest text-gray-300">Verifying</p>
            </div>
            <div class="space-y-2">
                <div class="h-1 bg-gray-100 rounded-full w-full"></div>
                <p class="text-[8px] font-bold uppercase tracking-widest text-gray-300">Approved</p>
            </div>
        </div>

    </div>
</section>

<?php include 'includes/web_footer.php'; ?>