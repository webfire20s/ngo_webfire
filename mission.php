<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- 1. Immersive Page Header -->
<section class="pt-32 pb-20 bg-[#121212] text-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div data-aos="fade-down">
            <span class="text-[11px] font-bold uppercase tracking-[0.6em] text-gray-500 mb-4 block">Purpose & Future</span>
            <h1 class="text-6xl md:text-8xl font-bold brand-font tracking-tighter mb-8 text-white">Mission & Vision</h1>
            <div class="h-[2px] w-24 bg-white/20"></div>
        </div>
    </div>
</section>

<!-- 2. Dual-Column Mission & Vision Section -->
<section class="py-24 bg-white relative overflow-hidden">
    <!-- Background Watermark Text for "Fullness" -->
    <div class="absolute top-10 right-0 text-[15rem] font-bold text-gray-50 opacity-[0.03] select-none pointer-events-none brand-font">
        PURPOSE
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            
            <!-- Mission Block -->
            <div class="relative p-12 rounded-[2.5rem] bg-gray-50 border border-gray-100 group hover:bg-black transition-all duration-700" data-aos="fade-up">
                <div class="mb-10 w-16 h-16 bg-black text-white rounded-2xl flex items-center justify-center group-hover:bg-white group-hover:text-black transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-3xl font-bold brand-font mb-6 group-hover:text-white transition-colors">Our Mission</h3>
                <p class="text-xl text-gray-600 leading-relaxed font-light group-hover:text-gray-300 transition-colors">
                    To uplift communities through education, support, and sustainable development.
                </p>
                <div class="mt-10 h-[1px] w-full bg-gray-200 group-hover:bg-white/20"></div>
            </div>

            <!-- Vision Block -->
            <div class="relative p-12 rounded-[2.5rem] bg-gray-50 border border-gray-100 group hover:bg-black transition-all duration-700" data-aos="fade-up" data-aos-delay="200">
                <div class="mb-10 w-16 h-16 bg-black text-white rounded-2xl flex items-center justify-center group-hover:bg-white group-hover:text-black transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
                <h3 class="text-3xl font-bold brand-font mb-6 group-hover:text-white transition-colors">Our Vision</h3>
                <p class="text-xl text-gray-600 leading-relaxed font-light group-hover:text-gray-300 transition-colors">
                    To build a better and inclusive society for everyone.
                </p>
                <div class="mt-10 h-[1px] w-full bg-gray-200 group-hover:bg-white/20"></div>
            </div>

        </div>
    </div>
</section>

<!-- 3. Our Commitment Section (Extra Content to prevent empty page) -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
            <div data-aos="fade-up" data-aos-delay="100">
                <h4 class="text-[10px] uppercase tracking-[0.3em] font-bold text-gray-400 mb-6">Strategy</h4>
                <p class="text-sm leading-loose text-gray-600">Empowering individuals by providing the tools and resources necessary for self-sufficiency and long-term growth.</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="200">
                <h4 class="text-[10px] uppercase tracking-[0.3em] font-bold text-gray-400 mb-6">Partnership</h4>
                <p class="text-sm leading-loose text-gray-600">Collaborating with local leaders and global supporters to ensure our impact reaches the most remote areas.</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="300">
                <h4 class="text-[10px] uppercase tracking-[0.3em] font-bold text-gray-400 mb-6">Sustainability</h4>
                <p class="text-sm leading-loose text-gray-600">Designing initiatives that are environmentally conscious and socially responsible for generations to come.</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. Dynamic Quote Section -->
<section class="py-32 bg-white overflow-hidden">
    <div class="max-w-5xl mx-auto px-6 text-center">
        <div class="relative inline-block" data-aos="zoom-in">
            <!-- Large Quote Icon Mark -->
            <span class="absolute -top-16 -left-16 text-[10rem] text-gray-100 brand-font pointer-events-none">“</span>
            <h2 class="text-3xl md:text-5xl font-bold brand-font leading-tight italic text-gray-900 relative z-10">
                Empowerment is not a gift, <br> it is a fundamental right.
            </h2>
            <div class="mt-12 flex items-center justify-center gap-4">
                <div class="h-[1px] w-8 bg-black"></div>
                <span class="text-[11px] font-bold uppercase tracking-[0.4em] text-gray-400">Our Shared Belief</span>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/web_footer.php'; ?>