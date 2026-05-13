<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- 1. Page Header -->
<section class="pt-32 pb-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center">
        <div data-aos="fade-up">
            <span class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-400 mb-4 block">Proven Impact</span>
            <h1 class="text-6xl md:text-7xl font-bold brand-font tracking-tighter mb-8">Achievements</h1>
            <div class="h-[1px] w-24 bg-black mx-auto"></div>
        </div>
    </div>
</section>

<!-- 2. Hero Statistics Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
            
            <!-- Stat 01 -->
            <div class="p-12 bg-gray-50 rounded-[3rem] border border-gray-100 hover:shadow-2xl hover:shadow-gray-200/50 transition-all duration-700" data-aos="fade-up" data-aos-delay="100">
                <span class="text-6xl md:text-7xl font-bold brand-font tracking-tighter mb-4 block">1000+</span>
                <p class="text-[11px] font-bold uppercase tracking-[0.4em] text-gray-400">Members Joined</p>
                <div class="mt-6 h-[2px] w-8 bg-black mx-auto"></div>
            </div>

            <!-- Stat 02 -->
            <div class="p-12 bg-black text-white rounded-[3rem] shadow-2xl shadow-gray-400/20 transition-all duration-700" data-aos="fade-up" data-aos-delay="200">
                <span class="text-6xl md:text-7xl font-bold brand-font tracking-tighter mb-4 block">50+</span>
                <p class="text-[11px] font-bold uppercase tracking-[0.4em] text-gray-500">Campaigns Completed</p>
                <div class="mt-6 h-[2px] w-8 bg-white mx-auto"></div>
            </div>

            <!-- Stat 03 -->
            <div class="p-12 bg-gray-50 rounded-[3rem] border border-gray-100 hover:shadow-2xl hover:shadow-gray-200/50 transition-all duration-700" data-aos="fade-up" data-aos-delay="300">
                <span class="text-6xl md:text-7xl font-bold brand-font tracking-tighter mb-4 block">5000+</span>
                <p class="text-[11px] font-bold uppercase tracking-[0.4em] text-gray-400">Lives Impacted</p>
                <div class="mt-6 h-[2px] w-8 bg-black mx-auto"></div>
            </div>

        </div>
    </div>
</section>

<!-- 3. Qualitative Milestone Timeline (Added for depth) -->
<section class="py-32 bg-white">
    <div class="max-w-5xl mx-auto px-6 lg:px-12">
        <div class="relative border-l border-gray-100 pl-8 md:pl-16 ml-4 md:ml-0">
            
            <!-- Milestone Item -->
            <div class="mb-20 relative" data-aos="fade-left">
                <div class="absolute -left-[41px] md:-left-[73px] top-0 w-4 h-4 rounded-full bg-black border-4 border-white ring-4 ring-gray-50"></div>
                <h3 class="text-2xl font-bold brand-font mb-4 italic">Recognized Excellence</h3>
                <p class="text-gray-500 leading-relaxed font-light">Successfully scaled our operations to cover 10+ districts, providing essential support to underprivileged communities and setting new standards in social welfare.</p>
            </div>

            <!-- Milestone Item -->
            <div class="relative" data-aos="fade-left" data-aos-delay="200">
                <div class="absolute -left-[41px] md:-left-[73px] top-0 w-4 h-4 rounded-full bg-gray-200 border-4 border-white"></div>
                <h3 class="text-2xl font-bold brand-font mb-4 italic">Strategic Partnerships</h3>
                <p class="text-gray-500 leading-relaxed font-light">Collaborated with over 20 corporate partners to fund large-scale social campaigns, effectively doubling our reach in less than 12 months.</p>
            </div>

        </div>
    </div>
</section>

<!-- 4. Global Action Footer -->
<section class="py-24 bg-[#121212] text-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="text-center md:text-left" data-aos="fade-right">
            <h2 class="text-4xl font-bold brand-font mb-4">Be part of our next milestone.</h2>
            <p class="text-gray-400 font-light">Your support translates directly into these numbers.</p>
        </div>
        <div data-aos="fade-left">
            <a href="register.php" class="bg-white text-black px-12 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-gray-200 transition inline-block">Become a Member</a>
        </div>
    </div>
</section>

<?php include 'includes/web_footer.php'; ?>