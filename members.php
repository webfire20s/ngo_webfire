<?php
require 'includes/db.php';

$stmt = $pdo->query("
    SELECT u.name, m.referral_code, d.title
    FROM users u
    JOIN memberships m ON u.id = m.user_id
    JOIN designations d ON m.designation_id = d.id
    WHERE m.status = 'active'
    ORDER BY u.name ASC
");

$members = $stmt->fetchAll();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- 1. Page Header -->
<section class="pt-32 pb-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center">
        <div data-aos="fade-up">
            <span class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-400 mb-4 block">The Community</span>
            <h1 class="text-6xl md:text-7xl font-bold brand-font tracking-tighter mb-8">Our Members</h1>
            <div class="h-[1px] w-24 bg-black mx-auto"></div>
        </div>
    </div>
</section>

<!-- 2. Members Directory Grid -->
<section class="py-12 bg-white pb-32">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        
        <!-- Grid Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php foreach ($members as $m): ?>
            <div class="group bg-gray-50 p-8 rounded-[2.5rem] border border-gray-100 hover:bg-black transition-all duration-500" data-aos="fade-up">
                
                <div class="flex items-start justify-between mb-8">
                    <!-- Member Avatar Placeholder (Cinematic Initial) -->
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-xl font-bold brand-font shadow-sm group-hover:bg-white/10 group-hover:text-white transition-colors">
                        <?php echo substr(htmlspecialchars($m['name']), 0, 1); ?>
                    </div>
                    
                    <!-- Member ID Badge -->
                    <span class="text-[9px] uppercase tracking-widest font-bold px-3 py-1 bg-white border border-gray-100 rounded-full group-hover:bg-white/10 group-hover:border-white/20 group-hover:text-white transition-all">
                        ID: <?php echo $m['referral_code']; ?>
                    </span>
                </div>

                <!-- Member Info -->
                <div class="space-y-2">
                    <h3 class="text-2xl font-bold brand-font group-hover:text-white transition-colors">
                        <?php echo htmlspecialchars($m['name']); ?>
                    </h3>
                    <div class="flex items-center gap-3">
                        <div class="h-[1px] w-4 bg-gray-300 group-hover:bg-white/30 transition-colors"></div>
                        <p class="text-xs uppercase tracking-widest font-bold text-gray-400 group-hover:text-gray-500 transition-colors">
                            <?php echo htmlspecialchars($m['title']); ?>
                        </p>
                    </div>
                </div>

                <!-- Hover Decorative Element -->
                <div class="mt-8 pt-6 border-t border-gray-200 group-hover:border-white/10 flex justify-end transition-all">
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

        <!-- Empty State Logic (If no members found) -->
        <?php if (empty($members)): ?>
        <div class="text-center py-20 bg-gray-50 rounded-[3rem] border border-dashed border-gray-200">
            <p class="text-gray-400 uppercase tracking-widest text-xs font-bold">No active members found</p>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- 3. Join the Ranks CTA -->
<section class="py-24 bg-[#121212] text-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="flex flex-col md:flex-row justify-between items-center gap-10">
            <div data-aos="fade-right">
                <h2 class="text-4xl font-bold brand-font mb-4">Want to be featured here?</h2>
                <p class="text-gray-400 font-light">Join our membership program and start contributing today.</p>
            </div>
            <a href="register.php" data-aos="fade-left">
                <button class="bg-white text-black px-12 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-gray-200 transition shadow-2xl">
                    Apply for Membership
                </button>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/web_footer.php'; ?>