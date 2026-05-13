<?php
session_start(); 
require 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

$campaigns = $pdo->query("
    SELECT id, title 
    FROM campaigns 
    WHERE status='active'
")->fetchAll();

$selected_campaign = $_GET['campaign_id'] ?? '';
?>

<section class="min-h-screen bg-white pt-32 pb-32">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        
        <div class="flex flex-col lg:flex-row gap-20">
            
            <!-- Left Side: Payment Instructions -->
            <div class="lg:w-1/3" data-aos="fade-right">
                <span class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-400 mb-6 block">Support Us</span>
                <h1 class="text-5xl font-bold brand-font tracking-tighter mb-10 italic">Make a <br>Difference.</h1>
                
                <div class="space-y-12">
                    <!-- Step 1 -->
                    <div class="flex gap-6">
                        <div class="text-2xl font-bold brand-font text-gray-200">01</div>
                        <div>
                            <h4 class="text-[10px] font-bold uppercase tracking-widest mb-2">Scan & Pay</h4>
                            <p class="text-sm text-gray-500 font-light leading-relaxed">Use any UPI app to scan our official QR code and complete your transfer.</p>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="flex gap-6">
                        <div class="text-2xl font-bold brand-font text-gray-200">02</div>
                        <div>
                            <h4 class="text-[10px] font-bold uppercase tracking-widest mb-2">Note UTR</h4>
                            <p class="text-sm text-gray-500 font-light leading-relaxed">Keep the 12-digit UTR/Transaction ID handy after the payment is successful.</p>
                        </div>
                    </div>
                    <!-- Step 3 -->
                    <div class="flex gap-6">
                        <div class="text-2xl font-bold brand-font text-gray-200">03</div>
                        <div>
                            <h4 class="text-[10px] font-bold uppercase tracking-widest mb-2">Verify</h4>
                            <p class="text-sm text-gray-500 font-light leading-relaxed">Fill the form and upload a screenshot. Our team will verify and update your contribution.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-16 p-8 bg-gray-50 rounded-[2rem] border border-gray-100 text-center">
                    <p class="text-[9px] font-bold uppercase tracking-[0.3em] text-gray-400 mb-4">Official QR Code</p>
                    <div class="w-40 h-40 bg-white mx-auto rounded-xl shadow-sm flex items-center justify-center border border-gray-100">
                        <!-- Replace with your actual QR image source -->
                        <span class="text-[10px] text-gray-300 italic">[QR CODE HERE]</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Donation Form -->
            <div class="lg:w-2/3" data-aos="fade-left">
                <div class="bg-white border border-gray-100 rounded-[3rem] p-8 md:p-16 shadow-2xl shadow-gray-200/40">
                    <form method="POST" action="submit_donation.php" enctype="multipart/form-data" class="space-y-8">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Name -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest ml-4 text-gray-400">Your Name</label>
                                <input type="text" name="name" required placeholder="Full Name" 
                                    class="w-full bg-gray-50 border-none rounded-full px-8 py-5 focus:ring-2 focus:ring-black transition-all outline-none">
                            </div>
                            <!-- Email -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest ml-4 text-gray-400">Email Address</label>
                                <input type="email" name="email" placeholder="email@example.com" 
                                    class="w-full bg-gray-50 border-none rounded-full px-8 py-5 focus:ring-2 focus:ring-black transition-all outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Phone -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest ml-4 text-gray-400">Phone Number</label>
                                <input type="text" name="phone" placeholder="+91 00000 00000" 
                                    class="w-full bg-gray-50 border-none rounded-full px-8 py-5 focus:ring-2 focus:ring-black transition-all outline-none">
                            </div>
                            <!-- Amount -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest ml-4 text-gray-400">Amount (₹)</label>
                                <input type="number" name="amount" required placeholder="500" 
                                    class="w-full bg-gray-50 border-none rounded-full px-8 py-5 focus:ring-2 focus:ring-black transition-all outline-none text-xl font-bold brand-font">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- UTR -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest ml-4 text-gray-400">Transaction ID / UTR</label>
                                <input type="text" name="utr" required placeholder="12-digit number" 
                                    class="w-full bg-gray-50 border-none rounded-full px-8 py-5 focus:ring-2 focus:ring-black transition-all outline-none">
                            </div>
                            <!-- Campaign Selection -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest ml-4 text-gray-400">Select Campaign</label>
                                <select name="campaign_id" class="w-full bg-gray-50 border-none rounded-full px-8 py-5 focus:ring-2 focus:ring-black transition-all outline-none appearance-none cursor-pointer">
                                    <option value="">General Donation</option>
                                    <?php foreach ($campaigns as $c): ?>
                                        <option value="<?php echo $c['id']; ?>" 
                                        <?php if ($selected_campaign == $c['id']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($c['title']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Proof Upload -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-widest ml-4 text-gray-400 block mb-4">Payment Proof (Screenshot)</label>
                            <div class="relative group">
                                <input type="file" name="proof" accept="image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] py-12 text-center group-hover:border-black transition-all">
                                    <svg class="w-8 h-8 mx-auto text-gray-300 group-hover:text-black mb-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 group-hover:text-black transition-colors">Click to upload screenshot</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full bg-black text-white px-12 py-5 rounded-full text-[11px] font-bold uppercase tracking-[0.4em] hover:bg-gray-800 transition-all shadow-xl shadow-black/20 active:scale-95">
                                Submit Donation Details
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="mt-12 flex items-center justify-center gap-8 opacity-30 grayscale">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/e/e1/UPI-Logo.png" alt="UPI" class="h-6">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/1200px-PayPal.svg.png" alt="PayPal" class="h-5">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png" alt="Visa" class="h-4">
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/web_footer.php'; ?>