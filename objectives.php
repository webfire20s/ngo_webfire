<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- 1. Minimalist Page Header -->
<section class="pt-32 pb-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center">
        <div data-aos="fade-up">
            <span class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-400 mb-4 block">Our Roadmap</span>
            <h1 class="text-6xl md:text-7xl font-bold brand-font tracking-tighter mb-8">Aim & Objectives</h1>
            <div class="h-[1px] w-24 bg-black mx-auto"></div>
        </div>
    </div>
</section>

<!-- 2. Objectives Grid -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            
            <!-- Objective 01 -->
            <div class="group relative p-12 bg-gray-50 rounded-[3rem] border border-gray-100 hover:bg-black transition-all duration-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <span class="absolute -top-4 -right-4 text-9xl font-bold text-gray-200/40 group-hover:text-white/5 transition-colors brand-font">01</span>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold brand-font mb-4 group-hover:text-white transition-colors">Promote Education & Awareness</h3>
                    <p class="text-gray-500 leading-relaxed group-hover:text-gray-400 transition-colors">
                        Empowering the next generation through accessible learning resources and community-wide awareness programs.
                    </p>
                </div>
            </div>

            <!-- Objective 02 -->
            <div class="group relative p-12 bg-gray-50 rounded-[3rem] border border-gray-100 hover:bg-black transition-all duration-700 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                <span class="absolute -top-4 -right-4 text-9xl font-bold text-gray-200/40 group-hover:text-white/5 transition-colors brand-font">02</span>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold brand-font mb-4 group-hover:text-white transition-colors">Support Underprivileged Communities</h3>
                    <p class="text-gray-500 leading-relaxed group-hover:text-gray-400 transition-colors">
                        Providing direct aid and sustainable infrastructure to uplift those in marginalized social sectors.
                    </p>
                </div>
            </div>

            <!-- Objective 03 -->
            <div class="group relative p-12 bg-gray-50 rounded-[3rem] border border-gray-100 hover:bg-black transition-all duration-700 overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                <span class="absolute -top-4 -right-4 text-9xl font-bold text-gray-200/40 group-hover:text-white/5 transition-colors brand-font">03</span>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold brand-font mb-4 group-hover:text-white transition-colors">Organize Social Campaigns</h3>
                    <p class="text-gray-500 leading-relaxed group-hover:text-gray-400 transition-colors">
                        Leading impactful movements to drive legislative and social change for a better tomorrow.
                    </p>
                </div>
            </div>

            <!-- Objective 04 -->
            <div class="group relative p-12 bg-gray-50 rounded-[3rem] border border-gray-100 hover:bg-black transition-all duration-700 overflow-hidden" data-aos="fade-up" data-aos-delay="400">
                <span class="absolute -top-4 -right-4 text-9xl font-bold text-gray-200/40 group-hover:text-white/5 transition-colors brand-font">04</span>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold brand-font mb-4 group-hover:text-white transition-colors">Encourage Volunteer Participation</h3>
                    <p class="text-gray-500 leading-relaxed group-hover:text-gray-400 transition-colors">
                        Building a global network of dedicated individuals committed to hands-on social service.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 3. Full-Width Impact Section (Prevents page from ending abruptly) -->
<section class="py-24 bg-[#121212] text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="max-w-xl" data-aos="fade-right">
            <h2 class="text-4xl md:text-5xl font-bold brand-font leading-tight mb-6">Ready to see our objectives in action?</h2>
            <p class="text-gray-400 text-lg font-light">Join us in the field or support our ongoing campaigns.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-6" data-aos="fade-left">
            <a href="register.php" class="bg-white text-black px-12 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-gray-200 transition">Volunteer Now</a>
            <a href="campaigns.php" class="border border-white/20 text-white px-12 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-white hover:text-black transition">View Campaigns</a>
        </div>
    </div>
</section>

<?php include 'includes/web_footer.php'; ?>