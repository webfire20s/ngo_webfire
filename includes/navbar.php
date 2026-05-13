<nav class="fixed top-0 w-full z-[100] bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <!-- Branding / Logo Area -->
            <div class="flex-shrink-0 flex items-center">
                <a href="index.php" class="text-2xl font-bold tracking-tighter brand-font">
                    NGO<span class="text-gray-400">.</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden lg:flex items-center space-x-1">
                <?php 
                    // Keeping your exact file names and order
                    $nav_items = [
                        'index.php' => 'Home',
                        'campaigns.php' => 'Campaigns',
                        'donate.php' => 'Donate',
                        'about.php' => 'About',
                        'mission.php' => 'Mission',
                        'objectives.php' => 'Objectives',
                        'members.php' => 'Members',
                        'notices.php' => 'Notices',
                        'events.php' => 'Events',
                        'gallery.php' => 'Gallery',
                        'achievements.php' => 'Achievements',
                        'contact.php' => 'Contact'
                    ];

                    foreach($nav_items as $file => $name): 
                ?>
                    <a href="<?php echo $file; ?>" 
                       class="px-3 py-2 text-[10px] uppercase tracking-[0.2em] font-semibold text-gray-600 hover:text-black transition-colors duration-300 relative group">
                        <?php echo $name; ?>
                        <span class="absolute bottom-0 left-3 right-3 h-[1.5px] bg-black scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                    </a>
                <?php endforeach; ?>

                <!-- "Join Us" Highlighted Button -->
                <a href="register.php" 
                   class="ml-4 bg-black text-white px-6 py-2.5 rounded-full text-[11px] uppercase tracking-widest font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                    Join Us
                </a>
            </div>

            <!-- Mobile Menu Button (Icon only) -->
            <div class="lg:hidden flex items-center">
                <button class="p-2 text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8h16M4 16h16"></path>
                    </svg>
                </button>
            </div>

        </div>
    </div>
</nav>

<!-- Spacer to prevent content from starting behind the fixed navbar -->
<div class="h-20"></div>