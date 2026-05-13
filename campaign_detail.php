<?php
require 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM campaigns WHERE id=?");
$stmt->execute([$id]);
$c = $stmt->fetch();

if (!$c) {
    die("<div class='h-screen flex items-center justify-center font-bold uppercase tracking-widest'>Campaign not found</div>");
}

$percent = $c['goal_amount'] > 0 
    ? min(100, ($c['collected_amount'] / $c['goal_amount']) * 100) 
    : 0;
?>

<article class="bg-white min-h-screen pb-32">
    <!-- 1. Navigation & Back Action -->
    <nav class="pt-32 pb-12 max-w-7xl mx-auto px-6 lg:px-12">
        <a href="campaigns.php" class="group inline-flex items-center gap-4">
            <div class="w-12 h-12 rounded-full border border-gray-100 flex items-center justify-center group-hover:bg-black group-hover:text-white transition-all duration-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400 group-hover:text-black transition-colors">Back to Campaigns</span>
        </a>
    </nav>

    <!-- 2. Hero Content Section -->
    <header class="max-w-7xl mx-auto px-6 lg:px-12 mb-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-end">
            <div data-aos="fade-right">
                <span class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-400 mb-6 block">Featured Mission</span>
                <h1 class="text-5xl md:text-7xl font-bold brand-font tracking-tighter leading-[0.9] mb-8">
                    <?php echo htmlspecialchars($c['title']); ?>
                </h1>
                <div class="h-[1px] w-32 bg-black"></div>
            </div>
            
            <div class="space-y-8" data-aos="fade-left">
                <div class="flex justify-between items-end mb-2">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Funding Progress</p>
                    <p class="text-4xl font-bold brand-font italic"><?php echo round($percent); ?>%</p>
                </div>
                <!-- LARGE PROGRESS BAR -->
                <div class="w-full bg-gray-100 h-4 rounded-full overflow-hidden">
                    <div class="bg-black h-full transition-all duration-1000 ease-out shadow-[0_0_20px_rgba(0,0,0,0.1)]" 
                         style="width:<?php echo $percent; ?>%;">
                    </div>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <p class="text-2xl font-bold brand-font">₹<?php echo number_format($c['collected_amount']); ?> <span class="text-sm font-light text-gray-400">raised</span></p>
                    <p class="text-sm font-bold text-gray-300 uppercase tracking-widest">Goal: ₹<?php echo number_format($c['goal_amount']); ?></p>
                </div>
            </div>
        </div>
    </header>

    <!-- 3. Main Narrative & Call to Action -->
    <section class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="flex flex-col lg:flex-row gap-20">
            
            <!-- Description Text -->
            <div class="lg:w-2/3" data-aos="fade-up">
                <div class="prose prose-xl font-light text-gray-600 leading-relaxed space-y-6">
                    <?php echo nl2br(htmlspecialchars($c['description'])); ?>
                </div>
                
                <!-- Trust Badges -->
                <div class="mt-16 grid grid-cols-2 md:grid-cols-3 gap-8 pt-16 border-t border-gray-50">
                    <div>
                        <h4 class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-2">Transparency</h4>
                        <p class="text-xs text-gray-600">Direct-to-cause funding</p>
                    </div>
                    <div>
                        <h4 class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-2">Security</h4>
                        <p class="text-xs text-gray-600">Encrypted Transactions</p>
                    </div>
                    <div>
                        <h4 class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-2">Impact</h4>
                        <p class="text-xs text-gray-600">Real-time updates</p>
                    </div>
                </div>
            </div>

            <!-- Sticky Donation Card -->
            <div class="lg:w-1/3" data-aos="fade-left">
                <div class="sticky top-32 bg-gray-50 rounded-[3rem] p-10 text-center border border-gray-100">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-8 shadow-sm">
                        <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold brand-font mb-4 italic">Support this Cause</h3>
                    <p class="text-sm text-gray-500 font-light mb-8 leading-relaxed">Your contribution, no matter the size, helps us reach our goal faster.</p>
                    
                    <a href="donate.php?campaign_id=<?php echo $c['id']; ?>" class="block w-full bg-black text-white py-5 rounded-full text-[11px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition-all shadow-xl shadow-black/10 active:scale-95">
                        Donate Now
                    </a>
                    
                    <p class="mt-6 text-[9px] text-gray-400 uppercase tracking-widest italic">Secure Payment via Razorpay</p>
                </div>
            </div>

        </div>
    </section>
</article>

<?php include 'includes/web_footer.php'; ?>