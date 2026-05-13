<?php
require 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

/* FETCH ACTIVE CAMPAIGNS */
$campaigns = $pdo->query("
    SELECT * FROM campaigns 
    WHERE status='active'
    ORDER BY id DESC
")->fetchAll();
?>

<!-- 1. Editorial Page Header -->
<section class="pt-32 pb-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center">
        <div data-aos="fade-up">
            <span class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-400 mb-4 block">Active Missions</span>
            <h1 class="text-6xl md:text-7xl font-bold brand-font tracking-tighter mb-8">Our Campaigns</h1>
            <div class="h-[1px] w-24 bg-black mx-auto"></div>
        </div>
    </div>
</section>

<!-- 2. Campaigns Grid -->
<section class="py-12 bg-white pb-32">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

            <?php foreach ($campaigns as $c): 
                $goal = $c['goal_amount'];
                $collected = $c['collected_amount'];
                $percent = $goal > 0 ? min(100, ($collected / $goal) * 100) : 0;
            ?>
            
            <!-- THE ENTIRE CARD IS NOW A LINK TO DETAILS -->
            <div class="relative group" data-aos="fade-up">
                <a href="campaign_detail.php?id=<?php echo $c['id']; ?>" class="block h-full">
                    <div class="bg-gray-50 rounded-[3rem] p-8 border border-gray-100 group-hover:bg-black transition-all duration-700 overflow-hidden flex flex-col justify-between h-full">
                        
                        <!-- Top Info -->
                        <div>
                            <div class="flex justify-between items-start mb-6">
                                <span class="px-4 py-1 bg-white border border-gray-100 rounded-full text-[9px] font-bold uppercase tracking-widest group-hover:bg-white/10 group-hover:border-white/20 group-hover:text-white transition-all">
                                    Live Fundraiser
                                </span>
                                <span class="text-xl font-bold brand-font group-hover:text-white transition-colors"><?php echo round($percent); ?>%</span>
                            </div>

                            <h3 class="text-2xl font-bold brand-font mb-4 group-hover:text-white transition-colors leading-tight">
                                <?php echo htmlspecialchars($c['title']); ?>
                            </h3>

                            <p class="text-gray-500 font-light leading-relaxed mb-8 group-hover:text-gray-400 transition-colors">
                                <?php echo htmlspecialchars(substr($c['description'], 0, 100)); ?>...
                            </p>
                        </div>

                        <!-- Progress & Stats Area -->
                        <div class="mt-auto">
                            <div class="w-full bg-gray-200 group-hover:bg-white/10 h-2 rounded-full overflow-hidden mb-4 transition-colors">
                                <div class="bg-black group-hover:bg-white h-full transition-all duration-1000 ease-out" 
                                     style="width:<?php echo $percent; ?>%;">
                                </div>
                            </div>

                            <div class="flex justify-between items-end">
                                <div class="space-y-1">
                                    <p class="text-[9px] uppercase tracking-widest font-bold text-gray-400 group-hover:text-gray-500 transition-colors">Raised So Far</p>
                                    <p class="text-lg font-bold brand-font group-hover:text-white transition-colors">₹<?php echo number_format($collected); ?> <span class="text-xs font-light text-gray-400 group-hover:text-gray-600">of ₹<?php echo number_format($goal); ?></span></p>
                                </div>
                                
                                <!-- Placeholder for button space -->
                                <div class="w-24 h-10"></div>
                            </div>
                        </div>

                        <!-- Subtle Decorative Background -->
                        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    </div>
                </a>

                <!-- DONATE BUTTON (Placed outside the main <a> tag to fix functionality) -->
                <a href="donate.php?campaign_id=<?php echo $c['id']; ?>" 
                   onclick="event.stopPropagation();"
                   class="absolute bottom-8 right-8 z-20 bg-white text-black px-6 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest hover:scale-110 active:scale-95 transition-all shadow-xl group-hover:bg-white">
                    Donate
                </a>
            </div>

            <?php endforeach; ?>

        </div>

        <?php if (empty($campaigns)): ?>
            <div class="text-center py-32 bg-gray-50 rounded-[3rem] border border-dashed border-gray-200">
                <p class="text-xs font-bold uppercase tracking-[0.4em] text-gray-400">No active campaigns found</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/web_footer.php'; ?>