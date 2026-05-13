<footer class="bg-[#121212] text-white pt-20 pb-10">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <!-- Top Section: Brand and Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-16" data-aos="fade-up">
            
            <!-- Column 1: Identity -->
            <div class="space-y-6">
                <h3 class="brand-font text-3xl font-bold tracking-tighter">NGO<span class="text-gray-500">.</span></h3>
                <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                    Dedicated to creating sustainable change and empowering communities through collective action and transparency.
                </p>
            </div>

            <!-- Column 2: Quick Access (Keeping your names/logic) -->
            <div>
                <h4 class="text-[11px] uppercase tracking-[0.3em] font-bold mb-8 text-gray-500">Navigation</h4>
                <ul class="grid grid-cols-2 gap-4 text-sm text-gray-300">
                    <li><a href="index.php" class="hover:text-white transition">Home</a></li>
                    <li><a href="campaigns.php" class="hover:text-white transition">Campaigns</a></li>
                    <li><a href="donate.php" class="hover:text-white transition">Donate</a></li>
                    <li><a href="about.php" class="hover:text-white transition">About</a></li>
                    <li><a href="events.php" class="hover:text-white transition">Events</a></li>
                    <li><a href="contact.php" class="hover:text-white transition">Contact</a></li>
                </ul>
            </div>

            <!-- Column 3: Impact -->
            <div class="bg-white/5 p-8 rounded-2xl border border-white/10">
                <h4 class="text-[11px] uppercase tracking-[0.3em] font-bold mb-4 text-gray-400">Join the Mission</h4>
                <p class="text-sm text-gray-300 mb-6">Support our cause and stay updated with our latest initiatives.</p>
                <a href="register.php" class="inline-block w-full text-center bg-white text-black py-3 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-gray-200 transition">
                    Get Involved
                </a>
            </div>
        </div>

        <!-- Bottom Section: Copyright -->
        <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-xs text-gray-500 tracking-widest uppercase">
                © <?php echo date('Y'); ?> Your NGO Name. All Rights Reserved.
            </p>
            
            <div class="flex space-x-6">
                <!-- Placeholder Social Icons - Replace # with your links -->
                <a href="#" class="text-gray-500 hover:text-white transition"><small>Instagram</small></a>
                <a href="#" class="text-gray-500 hover:text-white transition"><small>Twitter</small></a>
                <a href="#" class="text-gray-500 hover:text-white transition"><small>Facebook</small></a>
            </div>
        </div>
    </div>
</footer>

<!-- 1. AOS (Animate On Scroll) Script -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- 2. Swiper JS Script -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    // Initialize Scroll Animations
    AOS.init({
        duration: 1000,
        once: true,
        offset: 50,
        easing: 'ease-out-quart'
    });

    // Initialize Swiper (For your sliders in index.php)
    const swiper = new Swiper('.swiper', {
        loop: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        effect: 'fade',
        fadeEffect: { crossFade: true },
        pagination: { el: '.swiper-pagination', clickable: true },
    });
</script>

</body>
</html>