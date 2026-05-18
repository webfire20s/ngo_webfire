<?php

require 'includes/db.php';

/* MAIN MENUS */
$stmt = $pdo->prepare("
    SELECT *
    FROM menus
    WHERE menu_type='main'
    AND status=1
    ORDER BY sort_order ASC
");

$stmt->execute();

$nav_items = [];

while($row = $stmt->fetch()){

    $nav_items[$row['menu_link']] = $row['menu_name'];
}

/* DROPDOWN MENUS */
$stmt = $pdo->prepare("
    SELECT *
    FROM menus
    WHERE menu_type='dropdown'
    AND status=1
    ORDER BY sort_order ASC
");

$stmt->execute();

$dropdown_items = [];

while($row = $stmt->fetch()){

    $dropdown_items[$row['menu_link']] = $row['menu_name'];
}
?>

<nav class="fixed top-0 w-full z-[100] bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <!-- Branding / Logo Area -->
            <div class="flex-shrink-0 flex items-center">
                <a href="index.php" class="text-2xl font-bold tracking-tighter brand-font">
                    Your LOGO<span class="text-gray-400">.</span>
                </a>
            </div>

            <!-- Desktop Navigation Links (Large Screens Only) -->
            <div class="hidden lg:flex items-center space-x-1">
                
                <!-- Home Link -->
                <a href="index.php" class="px-3 py-2 text-[10px] uppercase tracking-[0.2em] font-semibold text-gray-600 hover:text-black transition-colors duration-300 relative group">
                    Home
                    <span class="absolute bottom-0 left-3 right-3 h-[1.5px] bg-black scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a>

                <!-- About Dropdown Menu Trigger Block -->
                <div class="relative group/dropdown py-2">
                    <button type="button" class="px-3 py-2 text-[10px] uppercase tracking-[0.2em] font-semibold text-gray-600 hover:text-black transition-colors duration-300 flex items-center gap-1.5 focus:outline-none">
                        <span>About Us</span>
                        <svg class="w-2.5 h-2.5 text-gray-400 group-hover/dropdown:text-black transition-transform duration-300 group-hover/dropdown:rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Panel Drawer -->
                    <div class="absolute left-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl opacity-0 invisible translate-y-2 group-hover/dropdown:opacity-100 group-hover/dropdown:visible group-hover/dropdown:translate-y-0 transition-all duration-200 overflow-hidden py-1.5 z-50">
                        <?php foreach($dropdown_items as $file => $name): ?>
                        <a href="<?php echo $file; ?>" class="block px-4 py-2.5 text-[11px] font-medium text-gray-600 hover:text-black hover:bg-gray-50 transition-colors">
                            <?php echo $name; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Remaining Core Items Loop -->
                <?php 
                    foreach($nav_items as $file => $name): 
                        if ($file === 'index.php') continue; // Handled explicitly first to balance layout hierarchy
                ?>
                <a href="<?php echo $file; ?>" 
                   class="px-3 py-2 text-[10px] uppercase tracking-[0.2em] font-semibold text-gray-600 hover:text-black transition-colors duration-300 relative group">
                    <?php echo $name; ?>
                    <span class="absolute bottom-0 left-3 right-3 h-[1.5px] bg-black scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                </a>
                <?php endforeach; ?>

                <!-- "Join Us" Highlighted Action -->
                <a href="register.php" 
                   class="ml-4 bg-black text-white px-6 py-2.5 rounded-full text-[11px] uppercase tracking-widest font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                    Join Us
                </a>
            </div>

            <!-- Mobile Menu Toggle Button UI (Visible on screens smaller than lg) -->
            <div class="lg:hidden flex items-center">
                <button id="mobile-menu-toggle" type="button" class="p-2 text-gray-600 focus:outline-none" aria-label="Toggle Navigation Menu">
                    <svg class="w-6 h-6 transition-transform duration-300" id="hamburger-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8h16M4 16h16"></path>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Drawer Content Container (Hidden natively on start, toggled dynamically) -->
    <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-100 bg-white max-h-[calc(100vh-5rem)] overflow-y-auto">
        <div class="px-4 pt-4 pb-8 space-y-2">
            
            <!-- Base Mobile Links -->
            <a href="index.php" class="block px-4 py-3 rounded-lg text-xs font-semibold uppercase tracking-wider text-gray-700 hover:bg-gray-50 hover:text-black transition-colors">Home</a>
            
            <!-- Mobile Interactive Dropdown Collapsible Header -->
            <div class="space-y-1">
                <button id="mobile-dropdown-toggle" type="button" class="w-full flex justify-between items-center px-4 py-3 rounded-lg text-xs font-semibold uppercase tracking-wider text-gray-700 hover:bg-gray-50 hover:text-black transition-colors focus:outline-none">
                    <span>About Us</span>
                    <svg id="mobile-dropdown-arrow" class="w-3 h-3 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                    </svg>
                </button>
                <div id="mobile-dropdown-container" class="hidden pl-4 bg-gray-50/50 rounded-lg space-y-1 py-1">
                    <?php foreach($dropdown_items as $file => $name): ?>
                    <a href="<?php echo $file; ?>" class="block px-4 py-2.5 text-xs font-medium text-gray-600 hover:text-black transition-colors">
                        <?php echo $name; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Rest of Mobile Items Loop -->
            <?php 
                foreach($nav_items as $file => $name): 
                    if ($file === 'index.php') continue;
            ?>
            <a href="<?php echo $file; ?>" class="block px-4 py-3 rounded-lg text-xs font-semibold uppercase tracking-wider text-gray-700 hover:bg-gray-50 hover:text-black transition-colors">
                <?php echo $name; ?>
            </a>
            <?php endforeach; ?>

            <!-- Mobile Join Us Action Call -->
            <div class="pt-4 px-4">
                <a href="register.php" class="block w-full bg-black text-white text-center py-3.5 rounded-full text-xs uppercase tracking-widest font-bold hover:bg-gray-800 transition shadow-md">
                    Join Us
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Spacer to prevent content from starting behind the fixed navbar -->
<div class="h-20"></div>

<!-- Mobile Drawer and Dropdown Controller Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    
    const dropdownToggle = document.getElementById('mobile-dropdown-toggle');
    const dropdownContainer = document.getElementById('mobile-dropdown-container');
    const dropdownArrow = document.getElementById('mobile-dropdown-arrow');

    // Toggle Mobile Main Menu Drawer Panel
    menuToggle.addEventListener('click', () => {
        const isHidden = mobileMenu.classList.contains('hidden');
        if (isHidden) {
            mobileMenu.classList.remove('hidden');
            // Animate SVG path to an Close (X) Shape icon configuration
            hamburgerIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>';
        } else {
            mobileMenu.classList.add('hidden');
            // Revert back to Hamburger Line layout
            hamburgerIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8h16M4 16h16"></path>';
        }
    });

    // Toggle Inner Nested Mobile Dropdown Layout
    dropdownToggle.addEventListener('click', () => {
        const isDropdownHidden = dropdownContainer.classList.contains('hidden');
        if (isDropdownHidden) {
            dropdownContainer.classList.remove('hidden');
            dropdownArrow.classList.add('rotate-180');
        } else {
            dropdownContainer.classList.add('hidden');
            dropdownArrow.classList.remove('rotate-180');
        }
    });
});
</script>