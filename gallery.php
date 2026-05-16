<?php
require 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';

/* FETCH GALLERY */
$stmt = $pdo->query("
    SELECT *
    FROM gallery
    ORDER BY id DESC
");

$gallery = $stmt->fetchAll();
?>

<!-- 1. Editorial Header -->
<section class="pt-32 pb-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center">
        <div data-aos="fade-up">
            <span class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-400 mb-4 block">
                Visual Stories
            </span>

            <h1 class="text-6xl md:text-7xl font-bold brand-font tracking-tighter mb-8">
                Gallery
            </h1>

            <div class="h-[1px] w-24 bg-black mx-auto"></div>
        </div>
    </div>
</section>

<!-- 2. Masonry Gallery Grid -->
<section class="py-12 bg-white pb-32">

    <div class="max-w-7xl mx-auto px-6 lg:px-12">

        <?php if(count($gallery) > 0): ?>

            <div class="columns-1 md:columns-2 lg:columns-3 gap-8 space-y-8">

                <?php foreach($gallery as $index => $g): ?>

                    <div
                        class="relative group overflow-hidden rounded-[2rem] bg-gray-100 break-inside-avoid"
                        data-aos="fade-up"
                        data-aos-delay="<?php echo ($index % 3) * 100; ?>"
                    >

                        <img
                            src="uploads/gallery/<?php echo htmlspecialchars($g['image']); ?>"
                            class="w-full h-auto object-cover transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0"
                            alt="<?php echo htmlspecialchars($g['title']); ?>"
                        >

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">

                            <p class="text-white text-[10px] font-bold uppercase tracking-[0.3em] mb-2">
                                <?php echo htmlspecialchars($g['title']); ?>
                            </p>

                            <div class="h-[1px] w-12 bg-white/50 mb-4"></div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <!-- EMPTY STATE -->

            <div
                class="rounded-[3rem] bg-gray-50 border border-gray-100 p-20 text-center"
                data-aos="zoom-in"
            >

                <h2 class="text-3xl font-bold brand-font mb-6">
                    Gallery Coming Soon
                </h2>

                <p class="text-gray-500 font-light max-w-2xl mx-auto leading-relaxed">
                    Our visual stories and community moments will appear here soon.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>

<!-- 3. Bottom Decorative Section -->
<section class="py-24 bg-[#121212] text-center overflow-hidden">

    <div
        class="max-w-7xl mx-auto px-6 lg:px-12"
        data-aos="zoom-in"
    >

        <h2 class="text-white text-4xl font-bold brand-font mb-6 italic opacity-50 text-outline">
            Capturing moments of change.
        </h2>

        <div class="flex justify-center gap-4">
            <div class="w-2 h-2 rounded-full bg-white/20"></div>
            <div class="w-2 h-2 rounded-full bg-white/60"></div>
            <div class="w-2 h-2 rounded-full bg-white/20"></div>
        </div>

    </div>

</section>

<?php include 'includes/web_footer.php'; ?>