<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- 1. Minimalist Page Header -->
<section class="pt-32 pb-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div data-aos="fade-right">
            <span class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-400 mb-4 block">Who We Are</span>
            <h1 class="text-6xl md:text-8xl font-bold brand-font tracking-tighter mb-8">About Us</h1>
            <div class="h-[2px] w-24 bg-black"></div>
        </div>
    </div>
</section>

<!-- 2. The Core Story (Split Layout) -->
<section class="py-24 bg-gray-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-start">
            
            <!-- Image Side -->
            <div class="relative" data-aos="fade-up">
                <div class="aspect-[3/4] rounded-3xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1521791136064-7986c29596ad?q=80&w=2070" class="w-full h-full object-cover">
                </div>
                <!-- Decorative element to fill space -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-black rounded-full flex items-center justify-center border-8 border-gray-50" data-aos="rotate-in" data-aos-delay="400">
                    <span class="text-white text-[10px] font-bold uppercase tracking-widest text-center">Est. <br> 2024</span>
                </div>
            </div>

            <!-- Text Side -->
            <div class="space-y-12" data-aos="fade-left">
                <div class="space-y-6">
                    <h3 class="text-3xl font-bold brand-font">Dedicated to Welfare</h3>
                    <p class="text-xl text-gray-600 leading-relaxed font-light">
                        We are a non-profit organization dedicated to social welfare, community development, and empowerment.
                    </p>
                </div>

                <div class="space-y-6 pt-12 border-t border-gray-200">
                    <h3 class="text-3xl font-bold brand-font">Impact Through Action</h3>
                    <p class="text-xl text-gray-600 leading-relaxed font-light">
                        Our mission is to create positive change through collective efforts and impactful initiatives.
                    </p>
                </div>

                <!-- Call to Action -->
                <div class="pt-8">
                    <a href="mission.php" class="inline-flex items-center group">
                        <span class="bg-black text-white w-12 h-12 rounded-full flex items-center justify-center mr-4 group-hover:w-16 transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </span>
                        <span class="text-xs font-bold uppercase tracking-widest">Our Full Mission</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 3. Transparency & Values Section (To prevent empty look) -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center">
        <div class="mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold brand-font mb-4">Our Core Values</h2>
            <p class="text-gray-400 uppercase tracking-widest text-[10px]">What drives our decisions every day</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="group p-8 hover:bg-gray-50 rounded-3xl transition-all duration-500" data-aos="fade-up" data-aos-delay="100">
                <div class="text-4xl mb-6">🤝</div>
                <h4 class="text-xl font-bold mb-4">Integrity</h4>
                <p class="text-sm text-gray-500 leading-relaxed">Maintaining the highest standards of transparency in every project we undertake.</p>
            </div>
            <div class="group p-8 hover:bg-gray-50 rounded-3xl transition-all duration-500" data-aos="fade-up" data-aos-delay="200">
                <div class="text-4xl mb-6">🌍</div>
                <h4 class="text-xl font-bold mb-4">Inclusivity</h4>
                <p class="text-sm text-gray-500 leading-relaxed">Ensuring our initiatives reach every corner of society, leaving no one behind.</p>
            </div>
            <div class="group p-8 hover:bg-gray-50 rounded-3xl transition-all duration-500" data-aos="fade-up" data-aos-delay="300">
                <div class="text-4xl mb-6">⚡</div>
                <h4 class="text-xl font-bold mb-4">Innovation</h4>
                <p class="text-sm text-gray-500 leading-relaxed">Applying modern solutions and technology to solve age-old community challenges.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Community CTA Section -->
<section class="py-20 bg-black">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="flex flex-col md:flex-row justify-between items-center gap-12">
            <div data-aos="fade-right">
                <h2 class="text-3xl md:text-4xl font-bold text-white brand-font leading-tight">Want to be part of <br>our journey?</h2>
            </div>
            <div class="flex gap-4" data-aos="fade-left">
                <a href="register.php" class="bg-white text-black px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-gray-200 transition">Become a Member</a>
                <a href="contact.php" class="border border-white/20 text-white px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-white hover:text-black transition">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/web_footer.php'; ?>