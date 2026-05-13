<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

if (!isset($_SESSION['user_id'])) {
    die("<div class='h-screen flex items-center justify-center font-bold uppercase tracking-widest text-red-500'>Unauthorized Access</div>");
}

$user_id = $_SESSION['user_id'];
$membership_id = $_POST['membership_id'];
$utr = $_POST['utr'];

/* =========================
   PROOF UPLOAD START
========================= */
$proof_name = null;
if (isset($_FILES['proof']) && $_FILES['proof']['error'] === 0) {
    $file = $_FILES['proof'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png'];

    if (!in_array($ext, $allowed)) {
        die("<div class='h-screen flex items-center justify-center font-bold uppercase tracking-widest text-red-500'>Invalid format. Only JPG, PNG allowed.</div>");
    }

    if ($file['size'] > 3 * 1024 * 1024) {
        die("<div class='h-screen flex items-center justify-center font-bold uppercase tracking-widest text-red-500'>File too large (Max 3MB)</div>");
    }

    if (!is_dir('uploads/payments')) {
        mkdir('uploads/payments', 0777, true);
    }

    $proof_name = "txn_" . time() . "_" . rand(1000,9999) . "." . $ext;
    $path = "uploads/payments/" . $proof_name;
    move_uploaded_file($file['tmp_name'], $path);
}

/* FETCH AMOUNT SAFELY */
$stmt = $pdo->prepare("
    SELECT d.fee, d.title as rank
    FROM memberships m
    JOIN designations d ON m.designation_id = d.id
    WHERE m.id = ? AND m.user_id = ?
");
$stmt->execute([$membership_id, $user_id]);
$data = $stmt->fetch();

if (!$data) {
    die("<div class='h-screen flex items-center justify-center font-bold uppercase tracking-widest text-red-500'>Invalid membership session</div>");
}

$amount = $data['fee'];
$rank = $data['rank'];

/* SAVE TRANSACTION WITH PROOF */
$stmt = $pdo->prepare("
    INSERT INTO transactions 
    (user_id, type, reference_id, amount, payment_method, status, transaction_id, proof)
    VALUES (?, 'membership', ?, ?, 'upi', 'pending', ?, ?)
");

$stmt->execute([
    $user_id,
    $membership_id,
    $amount,
    $utr,
    $proof_name
]);
?>

<section class="min-h-screen flex items-center justify-center bg-white px-6">
    <div class="max-w-2xl w-full" data-aos="zoom-in">
        
        <!-- Premium Receipt Card -->
        <div class="bg-gray-50 rounded-[3rem] p-10 md:p-16 border border-gray-100 shadow-2xl shadow-gray-200/50 relative overflow-hidden">
            
            <!-- Floating Rank Badge -->
            <div class="absolute top-10 right-10 bg-black text-white text-[9px] font-bold uppercase tracking-[0.2em] px-4 py-2 rounded-full">
                <?php echo htmlspecialchars($rank); ?>
            </div>

            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-black rounded-full flex items-center justify-center text-white mx-auto mb-8">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-[0.4em] text-gray-400 block mb-2">Application Filed</span>
                <h1 class="text-4xl font-bold brand-font tracking-tighter italic">Payment Pending Verification.</h1>
            </div>

            <!-- Transaction Summary -->
            <div class="space-y-4 border-t border-b border-gray-200 py-8 mb-8">
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Transaction ID (UTR)</span>
                    <span class="text-sm font-mono font-bold text-black"><?php echo htmlspecialchars($utr); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Membership Fee</span>
                    <span class="text-xl font-bold brand-font text-black">₹<?php echo number_format($amount); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Process Time</span>
                    <span class="text-xs font-medium text-gray-600">24 - 48 Hours</span>
                </div>
            </div>

            <p class="text-center text-gray-400 text-sm font-light leading-relaxed mb-10">
                Our administration is currently reviewing your payment proof. Once verified, your membership will be activated and you will gain full access to the portal.
            </p>

            <!-- Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="dashboard.php" class="bg-black text-white text-center px-8 py-5 rounded-full text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition-all shadow-xl active:scale-95">
                    Go to Dashboard
                </a>
                <a href="index.php" class="bg-white border border-gray-200 text-black text-center px-8 py-5 rounded-full text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-gray-50 transition-all active:scale-95">
                    Back to Website
                </a>
            </div>

            <!-- Bottom Note -->
            <p class="text-center mt-8 text-[9px] text-gray-300 uppercase tracking-widest">A confirmation email will be sent to your registered address.</p>
        </div>
    </div>
</section>

<?php include 'includes/web_footer.php'; ?>