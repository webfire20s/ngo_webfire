<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- 1. Editorial Header -->
<section class="pt-32 pb-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center">
        <div data-aos="fade-up">
            <span class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-400 mb-4 block">Visual Stories</span>
            <h1 class="text-6xl md:text-7xl font-bold brand-font tracking-tighter mb-8">Gallery</h1>
            <div class="h-[1px] w-24 bg-black mx-auto"></div>
        </div>
    </div>
</section>

<!-- 2. Masonry Gallery Grid -->
<section class="py-12 bg-white pb-32">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        
        <!-- Grid Layout -->
        <div class="columns-1 md:columns-2 lg:columns-3 gap-8 space-y-8">
            
            <!-- Image Item 01 -->
            <div class="relative group overflow-hidden rounded-[2rem] bg-gray-100 break-inside-avoid" data-aos="fade-up">
                <img src="assets/sample1.jpg" class="w-full h-auto object-cover transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0" alt="Gallery Image">
                
                <!-- Hover Overlay -->
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                    <p class="text-white text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Community Impact</p>
                    <div class="h-[1px] w-12 bg-white/50 mb-4"></div>
                </div>
            </div>

            <!-- Image Item 02 -->
            <div class="relative group overflow-hidden rounded-[2rem] bg-gray-100 break-inside-avoid" data-aos="fade-up" data-aos-delay="100">
                <img src="assets/sample2.jpg" class="w-full h-auto object-cover transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0" alt="Gallery Image">
                
                <!-- Hover Overlay -->
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                    <p class="text-white text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Field Mission</p>
                    <div class="h-[1px] w-12 bg-white/50 mb-4"></div>
                </div>
            </div>

            <!-- Add more items as needed following the same structure -->

        </div>
    </div>
</section>

<!-- 3. Bottom Decorative Section -->
<section class="py-24 bg-[#121212] text-center overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-12" data-aos="zoom-in">
        <h2 class="text-white text-4xl font-bold brand-font mb-6 italic opacity-50 text-outline">Capturing moments of change.</h2>
        <div class="flex justify-center gap-4">
            <div class="w-2 h-2 rounded-full bg-white/20"></div>
            <div class="w-2 h-2 rounded-full bg-white/60"></div>
            <div class="w-2 h-2 rounded-full bg-white/20"></div>
        </div>
    </div>
</section>

<?php include 'includes/web_footer.php'; ?>