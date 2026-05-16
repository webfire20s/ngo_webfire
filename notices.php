<?php
require 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- 1. Page Header -->
<section class="pt-32 pb-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center md:text-left">
        <div data-aos="fade-right">
            <span class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-400 mb-4 block">Official Updates</span>
            <h1 class="text-6xl md:text-7xl font-bold brand-font tracking-tighter mb-8">Notices</h1>
            <div class="h-[1px] w-24 bg-black mx-auto md:mx-0"></div>
        </div>
    </div>
</section>

<!-- 2. Notices Feed -->
<section class="py-12 bg-white pb-32">
    <div class="max-w-5xl mx-auto px-6 lg:px-12">
        
        <?php
        $notices = $pdo->query("
            SELECT * FROM notices 
            ORDER BY created_at DESC
        ")->fetchAll();
        ?>

        <?php if(count($notices) > 0): ?>

        <?php foreach($notices as $index => $notice): ?>

        <?php
        $modalId = 'notice-' . $notice['id'];

        $day = date('d', strtotime($notice['created_at']));
        $month = date("M 'y", strtotime($notice['created_at']));
        ?>

        <div class="group relative flex flex-col md:flex-row gap-8 md:gap-16 py-12 border-b border-gray-100 items-start transition-all duration-500" data-aos="fade-up">

            <!-- Date Badge -->
            <div class="flex-shrink-0">
                <div class="w-20 h-20 bg-gray-50 rounded-2xl flex flex-col items-center justify-center border border-gray-100 group-hover:bg-black group-hover:text-white transition-all duration-500">
                    <span class="text-2xl font-bold brand-font">
                        <?php echo $day; ?>
                    </span>

                    <span class="text-[9px] uppercase tracking-widest font-bold opacity-60">
                        <?php echo $month; ?>
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-grow">

                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 bg-gray-100 text-[9px] font-bold uppercase tracking-widest rounded-full text-gray-500 tracking-tighter">
                        <?php echo htmlspecialchars($notice['category']); ?>
                    </span>
                </div>

                <h3 class="text-2xl md:text-3xl font-bold brand-font mb-4 group-hover:translate-x-2 transition-transform duration-300">
                    <?php echo htmlspecialchars($notice['title']); ?>
                </h3>

                <p class="text-gray-500 leading-relaxed font-light mb-6 max-w-2xl">
                    <?php echo nl2br(htmlspecialchars($notice['short_description'])); ?>
                </p>

                <!-- Button -->
                <button onclick="toggleModal('<?php echo $modalId; ?>')" class="inline-flex items-center text-[11px] font-bold uppercase tracking-[0.2em] group/btn outline-none">
                    View Details

                    <svg class="ml-2 w-4 h-4 transition-transform group-hover/btn:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </button>

            </div>

            <!-- MODAL -->
            <div id="<?php echo $modalId; ?>" class="fixed inset-0 z-[200] hidden flex items-center justify-center px-4">

                <!-- Backdrop -->
                <div onclick="toggleModal('<?php echo $modalId; ?>')" class="absolute inset-0 bg-black/60 backdrop-blur-md"></div>

                <!-- Modal Content -->
                <div class="relative bg-white w-full max-w-2xl rounded-[3rem] p-8 md:p-12 shadow-2xl overflow-y-auto max-h-[80vh] scale-95 opacity-0 transition-all duration-300 modal-container">

                    <button onclick="toggleModal('<?php echo $modalId; ?>')" class="absolute top-8 right-8 text-gray-400 hover:text-black transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <span class="text-[10px] font-bold uppercase tracking-[0.4em] text-gray-400 mb-4 block">
                        Official Full Detail
                    </span>

                    <h2 class="text-3xl font-bold brand-font mb-6 leading-tight">
                        <?php echo htmlspecialchars($notice['title']); ?>
                    </h2>

                    <div class="prose prose-sm text-gray-600 leading-loose">
                        <?php echo nl2br($notice['full_content']); ?>
                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end">
                        <button onclick="toggleModal('<?php echo $modalId; ?>')" class="bg-black text-white px-8 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                            Close Notice
                        </button>
                    </div>

                </div>
            </div>

        </div>

        <?php endforeach; ?>

        <?php else: ?>

        <!-- Empty State -->
        <div class="mt-12 p-20 bg-gray-50 rounded-[3rem] border border-dashed border-gray-200 text-center" data-aos="fade-up">
            <p class="text-[10px] uppercase tracking-[0.4em] font-bold text-gray-400 italic">
                No new notices at this time.
            </p>
        </div>

        <?php endif; ?>
         <!-- Empty State (Shows if your query returns 0 rows) -->
        <div class="mt-12 p-20 bg-gray-50 rounded-[3rem] border border-dashed border-gray-200 text-center" data-aos="fade-up">
            <p class="text-[10px] uppercase tracking-[0.4em] font-bold text-gray-400 italic">No new notices at this time.</p>
        </div>

    </div>
</section>

<!-- Simple JavaScript to toggle the modal -->
<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        const container = modal.querySelector('.modal-container');
        
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 10);
            document.body.style.overflow = 'hidden'; // Stop scrolling when modal is open
        } else {
            container.classList.remove('scale-100', 'opacity-100');
            container.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
            document.body.style.overflow = 'auto';
        }
    }
</script>

<?php include 'includes/web_footer.php'; ?>