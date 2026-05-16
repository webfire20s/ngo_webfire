<?php
require 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';
?>
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

<?php
$events = $pdo->query("
    SELECT * FROM events
    WHERE status='upcoming'
    ORDER BY event_date ASC
")->fetchAll();
?>

<section class="py-12 bg-white pb-32">

    <div class="max-w-7xl mx-auto px-6 lg:px-12">

        <?php if(count($events) > 0): ?>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10">

            <?php foreach($events as $event): ?>

            <div class="group" data-aos="fade-up">

                <!-- IMAGE -->
                <div class="aspect-video rounded-3xl overflow-hidden mb-6 bg-gray-100 relative">

                    <?php if(!empty($event['featured_image'])): ?>

                        <img 
                            src="uploads/events/<?php echo $event['featured_image']; ?>"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                        >

                    <?php else: ?>

                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                            No Image
                        </div>

                    <?php endif; ?>

                </div>

                <!-- DATE -->
                <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-3 font-bold">
                    <?php echo date('d M Y', strtotime($event['event_date'])); ?>
                </p>

                <!-- TITLE -->
                <h3 class="text-2xl font-bold brand-font mb-4 leading-tight">
                    <?php echo htmlspecialchars($event['title']); ?>
                </h3>

                <!-- SHORT DESC -->
                <p class="text-gray-500 leading-relaxed font-light mb-6">
                    <?php echo htmlspecialchars($event['short_description']); ?>
                </p>

                <!-- DETAILS -->
                <div class="space-y-2 mb-6">

                    <?php if(!empty($event['event_time'])): ?>
                    <p class="text-sm text-gray-600">
                        <strong>Time:</strong>
                        <?php echo htmlspecialchars($event['event_time']); ?>
                    </p>
                    <?php endif; ?>

                    <?php if(!empty($event['location'])): ?>
                    <p class="text-sm text-gray-600">
                        <strong>Location:</strong>
                        <?php echo htmlspecialchars($event['location']); ?>
                    </p>
                    <?php endif; ?>

                </div>

                <!-- FULL DETAILS -->
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">

                    <p class="text-sm text-gray-600 leading-loose">
                        <?php echo nl2br(htmlspecialchars($event['full_description'])); ?>
                    </p>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

        <?php else: ?>

        <!-- EMPTY STATE -->

        <div class="relative overflow-hidden rounded-[3rem] bg-gray-50 border border-gray-100 p-12 md:p-24 text-center" data-aos="zoom-in">

            <div class="absolute inset-0 flex items-center justify-center opacity-[0.02] select-none pointer-events-none">
                <span class="text-[15rem] font-bold brand-font">SOON</span>
            </div>

            <div class="relative z-10 max-w-md mx-auto">

                <div class="w-20 h-20 bg-white rounded-3xl shadow-sm flex items-center justify-center mx-auto mb-8">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold brand-font mb-4">
                    No events available currently.
                </h2>

                <p class="text-gray-500 font-light leading-relaxed mb-8">
                    We are currently planning our next phase of community initiatives.
                </p>

                <a href="contact.php" class="inline-block bg-black text-white px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                    Inquire About Hosting
                </a>

            </div>

        </div>

        <?php endif; ?>

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