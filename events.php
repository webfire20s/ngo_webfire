<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- 1. Page Header -->
<section class="pt-32 pb-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div data-aos="fade-right">
            <span class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-400 mb-4 block">Our Calendar</span>
            <h1 class="text-6xl md:text-7xl font-bold brand-font tracking-tighter mb-8">Events</h1>
            <div class="h-[1px] w-24 bg-black"></div>
        </div>
    </div>
</section>

<!-- 2. Events Content Area -->
<section class="py-12 bg-white pb-32">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        
        <!-- Logic for "No Events" - Styled as a Cinematic Empty State -->
        <div class="relative overflow-hidden rounded-[3rem] bg-gray-50 border border-gray-100 p-12 md:p-24 text-center" data-aos="zoom-in">
            <!-- Decorative Background Watermark -->
            <div class="absolute inset-0 flex items-center justify-center opacity-[0.02] select-none pointer-events-none">
                <span class="text-[15rem] font-bold brand-font">SOON</span>
            </div>

            <div class="relative z-10 max-w-md mx-auto">
                <div class="w-20 h-20 bg-white rounded-3xl shadow-sm flex items-center justify-center mx-auto mb-8">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold brand-font mb-4">No events available currently.</h2>
                <p class="text-gray-500 font-light leading-relaxed mb-8">
                    We are currently planning our next phase of community initiatives. Stay tuned for updates on workshops, fundraisers, and social gatherings.
                </p>
                <a href="contact.php" class="inline-block bg-black text-white px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                    Inquire About Hosting
                </a>
            </div>
        </div>

        <!-- 3. "Coming Soon" Categories (Added to fill the page) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-24">
            <div class="group" data-aos="fade-up" data-aos-delay="100">
                <div class="aspect-video rounded-2xl overflow-hidden mb-6 bg-gray-100">
                    <img src="https://images.unsplash.com/photo-1529070538774-1843cb3265df?q=80&w=2070" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                </div>
                <h3 class="text-lg font-bold brand-font">Community Workshops</h3>
                <p class="text-xs text-gray-400 uppercase tracking-widest mt-2">Coming Q3 2026</p>
            </div>
            
            <div class="group" data-aos="fade-up" data-aos-delay="200">
                <div class="aspect-video rounded-2xl overflow-hidden mb-6 bg-gray-100">
                    <img src="https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?q=80&w=2070" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                </div>
                <h3 class="text-lg font-bold brand-font">Annual Fundraisers</h3>
                <p class="text-xs text-gray-400 uppercase tracking-widest mt-2">Coming Q4 2026</p>
            </div>

            <div class="group" data-aos="fade-up" data-aos-delay="300">
                <div class="aspect-video rounded-2xl overflow-hidden mb-6 bg-gray-100">
                    <img src="https://images.unsplash.com/photo-1511632765486-a01980e01a18?q=80&w=2070" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                </div>
                <h3 class="text-lg font-bold brand-font">Volunteer Meetups</h3>
                <p class="text-xs text-gray-400 uppercase tracking-widest mt-2">Monthly Updates</p>
            </div>
        </div>

    </div>
</section>

<!-- 4. Global Invitation CTA -->
<section class="py-24 bg-[#121212] text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center">
        <div data-aos="zoom-out">
            <h2 class="text-4xl md:text-5xl font-bold brand-font mb-8">Never miss an update.</h2>
            <p class="text-gray-400 max-w-xl mx-auto mb-12 font-light">Join our community newsletter to receive first-hand notifications about upcoming events and projects.</p>
            <a href="register.php">
                <button class="bg-white text-black px-12 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-gray-200 transition shadow-2xl">
                    Register as Member
                </button>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/web_footer.php'; ?>