<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- 1. Cinematic Hero Slider -->
<section class="relative h-[90vh] overflow-hidden bg-black">
    <div class="swiper mySwiper h-full">
        <div class="swiper-wrapper">
            <div class="swiper-slide relative">
                <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070" class="w-full h-full object-cover opacity-50">
                <div class="absolute inset-0 flex items-center justify-center text-center px-6">
                    <div data-aos="zoom-out" data-aos-duration="1500">
                        <h1 class="text-6xl md:text-8xl font-bold text-white mb-6 brand-font tracking-tighter">
                            Welcome to Our NGO
                        </h1>
                        <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto font-light leading-relaxed">
                            We are working towards social welfare and community development. 
                            Join us in creating a future built on compassion and action.
                        </p>
                        <div class="flex flex-col md:flex-row gap-6 justify-center">
                            <a href="campaigns.php">
                                <button class="px-12 py-4 bg-white text-black font-bold uppercase text-[11px] tracking-[0.2em] rounded-full hover:bg-gray-200 transition-all duration-300">
                                    View Campaigns
                                </button>
                            </a>
                            <a href="donate.php">
                                <button class="px-12 py-4 border border-white/30 text-white font-bold uppercase text-[11px] tracking-[0.2em] rounded-full hover:bg-white hover:text-black transition-all duration-300">
                                    Donate Now
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Impact Statistics (NEW SECTION) -->
<section class="py-20 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-4xl font-bold brand-font mb-1">120+</h3>
                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Active Projects</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-4xl font-bold brand-font mb-1">15k</h3>
                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Volunteers</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-4xl font-bold brand-font mb-1">50+</h3>
                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Communities</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="400">
                <h3 class="text-4xl font-bold brand-font mb-1">M+</h3>
                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Funds Raised</p>
            </div>
        </div>
    </div>
</section>

<!-- 3. Our Strategic Objectives (NEW SECTION) -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16" data-aos="fade-right">
            <div class="max-w-lg">
                <span class="text-[11px] font-bold uppercase tracking-[0.4em] text-gray-400 mb-4 block">How we work</span>
                <h2 class="text-4xl font-bold leading-tight">Our Core Objectives</h2>
            </div>
            <a href="objectives.php" class="text-xs font-bold uppercase tracking-widest border-b border-black pb-1 hover:text-gray-500 hover:border-gray-500 transition-all mt-6 md:mt-0">View All Objectives</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Objective Card 1 -->
            <div class="bg-white p-10 rounded-3xl border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500" data-aos="fade-up" data-aos-delay="100">
                <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center mb-8 font-bold">01</div>
                <h4 class="text-xl font-bold mb-4">Education for All</h4>
                <p class="text-gray-500 text-sm leading-relaxed">Providing resources and schooling to underprivileged children to secure their future.</p>
            </div>
            <!-- Objective Card 2 -->
            <div class="bg-white p-10 rounded-3xl border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500" data-aos="fade-up" data-aos-delay="200">
                <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center mb-8 font-bold">02</div>
                <h4 class="text-xl font-bold mb-4">Health & Wellness</h4>
                <p class="text-gray-500 text-sm leading-relaxed">Organizing medical camps and providing essential healthcare services in rural areas.</p>
            </div>
            <!-- Objective Card 3 -->
            <div class="bg-white p-10 rounded-3xl border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500" data-aos="fade-up" data-aos-delay="300">
                <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center mb-8 font-bold">03</div>
                <h4 class="text-xl font-bold mb-4">Social Justice</h4>
                <p class="text-gray-500 text-sm leading-relaxed">Advocating for equality and protecting the rights of marginalized communities.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Featured Notice/Announcement (NEW SECTION) -->
<section class="py-24 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="bg-black rounded-[3rem] p-8 md:p-16 relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
            
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <span class="inline-block px-4 py-1 rounded-full border border-white/20 text-[10px] text-white uppercase tracking-widest mb-6">Latest Notice</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 brand-font leading-tight">Annual General Meeting & <br>Volunteer Recognition 2026</h2>
                    <p class="text-gray-400 mb-8 max-w-sm">Check the latest notices for updates on our upcoming events and administrative changes.</p>
                    <a href="notices.php">
                        <button class="bg-white text-black px-8 py-3 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-gray-200 transition">View All Notices</button>
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-4" data-aos="fade-left">
                    <div class="aspect-square bg-white/10 rounded-2xl backdrop-blur-sm p-6 flex flex-col justify-center text-center">
                        <span class="text-white text-3xl font-bold block">15</span>
                        <span class="text-gray-500 text-[10px] uppercase tracking-widest mt-2">May 2026</span>
                    </div>
                    <div class="aspect-square bg-white/10 rounded-2xl backdrop-blur-sm p-6 flex flex-col justify-center text-center">
                        <span class="text-white text-3xl font-bold block">10</span>
                        <span class="text-gray-500 text-[10px] uppercase tracking-widest mt-2">AM - Start</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. Minimalist Call to Action (Stayed from previous) -->
<section class="py-20 bg-white text-center" data-aos="fade-up">
    <div class="max-w-4xl mx-auto px-6">
        <h3 class="text-3xl font-medium mb-6 italic leading-relaxed text-gray-800">"We make a living by what we get, but we make a life by what we give."</h3>
        <p class="text-gray-400 uppercase tracking-[0.5em] text-[10px] font-bold">— Winston Churchill</p>
    </div>
</section>

<?php include 'includes/web_footer.php'; ?>