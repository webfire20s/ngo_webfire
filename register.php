<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';
include 'includes/header.php'; 
include 'includes/navbar.php'; 

/* FETCH DESIGNATIONS */
$designations = $pdo->query("SELECT * FROM designations")->fetchAll();

$msg = "";
$msgClass = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verifyToken($_POST['csrf_token'])) {
        die("Invalid CSRF Token");
    }

    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $designation_id = $_POST['designation_id'];
    $referral = sanitize($_POST['referral'] ?? '');

    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetch()) {
        $msg = "Email already registered";
        $msgClass = "bg-red-50 text-red-600";
    } else {
        $photo_name = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $file = $_FILES['photo'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed)) { die("Only JPG, JPEG, PNG allowed"); }
            if ($file['size'] > 2 * 1024 * 1024) { die("Max file size is 2MB"); }

            if (!is_dir('uploads/profile')) { mkdir('uploads/profile', 0777, true); }

            $temp_name = uniqid() . '.' . $ext;
            $temp_path = "uploads/profile/" . $temp_name;
            move_uploaded_file($file['tmp_name'], $temp_path);
            $photo_name = $temp_name;
        }

        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role, profile_photo) VALUES (?, ?, ?, ?, 'member', ?)");
        $stmt->execute([$name, $email, $phone, $password, $photo_name]);

        $user_id = $pdo->lastInsertId();
        if ($photo_name) {
            $ext = pathinfo($photo_name, PATHINFO_EXTENSION);
            $new_name = "user_" . $user_id . "." . $ext;
            rename("uploads/profile/" . $photo_name, "uploads/profile/" . $new_name);
            $stmt = $pdo->prepare("UPDATE users SET profile_photo=? WHERE id=?");
            $stmt->execute([$new_name, $user_id]);
        }

        $referred_by = null;
        if (!empty($referral)) {
            $refCheck = $pdo->prepare("SELECT user_id FROM memberships WHERE referral_code = ?");
            $refCheck->execute([$referral]);
            $refUser = $refCheck->fetch();
            if ($refUser) { $referred_by = $refUser['user_id']; }
        }

        $referral_code = strtoupper(substr(md5(uniqid()), 0, 8));
        $stmt = $pdo->prepare("INSERT INTO memberships (user_id, designation_id, join_date, expiry_date, status, referral_code, referred_by) VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), 'expired', ?, ?)");
        $stmt->execute([$user_id, $designation_id, $referral_code, $referred_by]);

        $msg = "Registration successful. Please login.";
        $msgClass = "bg-green-50 text-green-600";
    }
}
?>

<section class="min-h-screen bg-white flex flex-col lg:flex-row">
    
    <!-- Left Panel: Branding -->
    <div class="lg:w-1/3 bg-black p-12 lg:p-20 flex flex-col justify-between text-white relative overflow-hidden">
        <div class="relative z-10" data-aos="fade-right">
            <h2 class="text-5xl font-bold brand-font tracking-tighter leading-tight mb-6">Join the <br>Movement.</h2>
            <p class="text-gray-400 font-light max-w-xs leading-relaxed">Become a part of our growing community and help us drive real change.</p>
        </div>
        
        <div class="relative z-10" data-aos="fade-up">
            <div class="flex -space-x-4 mb-6">
                <div class="w-12 h-12 rounded-full border-2 border-black bg-gray-800 flex items-center justify-center text-xs">P</div>
                <div class="w-12 h-12 rounded-full border-2 border-black bg-gray-700 flex items-center justify-center text-xs">B</div>
                <div class="w-12 h-12 rounded-full border-2 border-black bg-gray-600 flex items-center justify-center text-xs">+</div>
            </div>
            <p class="text-[10px] uppercase tracking-[0.3em] font-bold text-gray-500">Trusted by 1000+ members</p>
        </div>

        <!-- Decorative Background Element -->
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Right Panel: Form -->
    <div class="lg:w-2/3 p-6 lg:p-24 bg-white flex items-center justify-center">
        <div class="w-full max-w-2xl" data-aos="fade-left">
            
            <?php if ($msg): ?>
                <div class="p-4 rounded-2xl mb-8 text-sm font-bold uppercase tracking-widest text-center <?php echo $msgClass; ?>">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <div class="mb-12">
                <h1 class="text-4xl font-bold brand-font tracking-tighter mb-2">Create Account</h1>
                <p class="text-gray-400 text-sm">Please fill in your details to apply for membership.</p>
            </div>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo generateToken(); ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 ml-4">Full Name</label>
                        <input type="text" name="name" required placeholder="full name" class="w-full bg-gray-50 border-none rounded-full px-8 py-4 focus:ring-2 focus:ring-black transition-all outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 ml-4">Email</label>
                        <input type="email" name="email" required placeholder="email@example.com" class="w-full bg-gray-50 border-none rounded-full px-8 py-4 focus:ring-2 focus:ring-black transition-all outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 ml-4">Phone</label>
                        <input type="text" name="phone" required placeholder="+91 00000 00000" class="w-full bg-gray-50 border-none rounded-full px-8 py-4 focus:ring-2 focus:ring-black transition-all outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 ml-4">Password</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full bg-gray-50 border-none rounded-full px-8 py-4 focus:ring-2 focus:ring-black transition-all outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 ml-4">Designation</label>
                        <select name="designation_id" required class="w-full bg-gray-50 border-none rounded-full px-8 py-4 focus:ring-2 focus:ring-black transition-all outline-none appearance-none cursor-pointer">
                            <?php foreach ($designations as $d): ?>
                                <option value="<?php echo $d['id']; ?>">
                                    <?php echo $d['title']; ?> (₹<?php echo $d['fee']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 ml-4">Referral (Optional)</label>
                        <input type="text" name="referral" placeholder="ABC12345" class="w-full bg-gray-50 border-none rounded-full px-8 py-4 focus:ring-2 focus:ring-black transition-all outline-none">
                    </div>
                </div>

                <div class="space-y-2 pt-2">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 ml-4 block mb-2">Profile Photo (Max 2MB)</label>
                    <div class="relative group">
                        <input type="file" name="photo" accept="image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="w-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] py-8 text-center group-hover:border-black transition-all">
                            <svg class="w-8 h-8 mx-auto text-gray-300 group-hover:text-black mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 group-hover:text-black transition-colors">Select your photo</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-black text-white px-12 py-5 rounded-full text-[11px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition-all shadow-2xl shadow-black/10">
                        Complete Registration
                    </button>
                    <p class="text-center mt-6 text-xs text-gray-400">Already a member? <a href="login.php" class="text-black font-bold">Sign In</a></p>
                </div>
            </form>

        </div>
    </div>
</section>

<?php include 'includes/web_footer.php'; ?>