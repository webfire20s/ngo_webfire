<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!verifyToken($_POST['csrf_token'])) {
        die("Invalid CSRF Token");
    }

    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("
        SELECT * FROM users 
        WHERE email = ? 
        AND deleted_at IS NULL 
        AND status = 'active'
        LIMIT 1
    ");
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: ../user/dashboard.php");
        }
        exit;

    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gateway Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans antialiased text-slate-900">

    <div class="sm:mx-auto w-full max-w-md px-4 sm:px-0">
        
        <!-- Interactive Error Log Banner -->
        <?php if (isset($error)): ?>
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm animate-fade-in">
                <span class="text-base">✕</span> Authentication failed: <?php echo htmlspecialchars($error); ?>. Please verify your entries.
            </div>
        <?php endif; ?>

        <!-- Main Authentication Panel -->
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xl overflow-hidden p-6 sm:p-10">
            
            <!-- Context Brand Branding Header -->
            <header class="mb-8 border-b border-slate-100 pb-6 text-center sm:text-left">
                <span class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Secure Infrastructure</span>
                <h2 class="text-3xl font-light tracking-tight text-slate-900 mt-1">Admin Login</h2>
                <p class="text-xs text-slate-400 mt-1.5">Sign in to access your administrative control console</p>
            </header>

            <!-- Submission Form -->
            <form method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?php echo generateToken(); ?>">
                
                <!-- Email Identity Parameter Entry -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Email Address
                    </label>
                    <input type="email" name="email" required autocomplete="email"
                           class="w-full bg-slate-50/50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                           placeholder="admin@organization.com">
                </div>

                <!-- Protective Password Secret Entry -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Security Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="passwordInput" required autocomplete="current-password"
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-lg pl-4 pr-12 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-slate-400 focus:bg-white transition-all shadow-inner placeholder-slate-400"
                            placeholder="••••••••••••">
                        
                        <!-- Interactive Visibility Toggle Trigger -->
                        <button type="button" onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-slate-700 transition-colors select-none">
                            <span id="toggleText">Show</span>
                        </button>
                    </div>
                </div>

                <script>
                function togglePasswordVisibility() {
                    const passwordInput = document.getElementById('passwordInput');
                    const toggleText = document.getElementById('toggleText');
                    
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        toggleText.textContent = 'Hide';
                    } else {
                        passwordInput.type = 'password';
                        toggleText.textContent = 'Show';
                    }
                }
                </script>

                <!-- Execution Action Bar -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full px-6 py-2.5 text-sm font-bold tracking-wide uppercase text-white bg-slate-900 hover:bg-slate-800 active:bg-black rounded-lg transition-colors duration-150 shadow-md">
                        Authenticate Account
                    </button>
                </div>
            </form>

            <!-- Navigation Outward Footer Routing Link -->
            <footer class="mt-8 pt-6 border-t border-slate-100 flex justify-center">
                <a href="../register.php" class="text-xs font-medium text-slate-400 hover:text-slate-900 transition-colors duration-150 flex items-center gap-1.5">
                    Need an account? <span class="font-bold underline text-slate-600 hover:text-slate-900">Register here ↗</span>
                </a>
            </footer>

        </div>
    </div>

</body>
</html>