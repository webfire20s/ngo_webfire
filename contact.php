<?php
require 'includes/db.php';

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);

    if (
        !empty($name) &&
        !empty($email) &&
        !empty($phone) &&
        !empty($message)
    ) {

        $stmt = $pdo->prepare("
            INSERT INTO enquiries
            (
                name,
                email,
                phone,
                message
            )
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $name,
            $email,
            $phone,
            $message
        ]);

        $success = true;
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- 1. Editorial Header -->
<section class="pt-32 pb-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div data-aos="fade-right">
            <span class="text-[11px] font-bold uppercase tracking-[0.5em] text-gray-400 mb-4 block">Get in Touch</span>
            <h1 class="text-6xl md:text-7xl font-bold brand-font tracking-tighter mb-8">Contact Us</h1>
            <div class="h-[1px] w-24 bg-black"></div>
        </div>
    </div>
</section>

<!-- 2. Contact Section -->
<section class="py-12 bg-white pb-32">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="flex flex-col lg:flex-row gap-20">
            
            <!-- Left Side: Contact Info -->
            <div class="lg:w-1/3 space-y-12" data-aos="fade-up">
                <div>
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.4em] text-gray-400 mb-6">Office Address</h3>
                    <p class="text-xl font-light leading-relaxed text-gray-800">
                        123 Welfare Plaza, <br>
                        Civil Lines, <br>
                        Uttar Pradesh, India
                    </p>
                </div>

                <div>
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.4em] text-gray-400 mb-6">Direct Contact</h3>
                    <p class="text-xl font-light text-gray-800 underline underline-offset-8 decoration-gray-200">hello@yourngo.org</p>
                    <p class="text-xl font-light text-gray-800 mt-4">+91 98765 43210</p>
                </div>

                <div class="pt-8">
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.4em] text-gray-400 mb-6">Social Presence</h3>
                    <div class="flex gap-6">
                        <a href="#" class="text-gray-400 hover:text-black transition-colors font-bold text-xs uppercase tracking-widest">Instagram</a>
                        <a href="#" class="text-gray-400 hover:text-black transition-colors font-bold text-xs uppercase tracking-widest">Twitter</a>
                    </div>
                </div>
            </div>

            <!-- Right Side: Enquiry Form -->
            <div class="lg:w-2/3" data-aos="fade-left">
                <div class="bg-gray-50 rounded-[3rem] p-8 md:p-16 border border-gray-100">
                    <?php if($success): ?>

                    <div class="mb-8 bg-green-100 text-green-700 px-6 py-4 rounded-2xl">
                        Your enquiry has been submitted successfully.
                    </div>

                    <?php endif; ?>
                    <form method="POST" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Name -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest ml-4">Full Name</label>
                                <input type="text" name="name" required placeholder="full name" 
                                    class="w-full bg-white border-none rounded-full px-8 py-5 focus:ring-2 focus:ring-black transition-all outline-none shadow-sm">
                            </div>
                            <!-- Email -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest ml-4">Email Address</label>
                                <input type="email" name="email" required placeholder="email@example.com" 
                                    class="w-full bg-white border-none rounded-full px-8 py-5 focus:ring-2 focus:ring-black transition-all outline-none shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-8">
                            <!-- Phone -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest ml-4">Phone Number</label>
                                <input type="text" name="phone" required placeholder="+91 00000 00000" 
                                    class="w-full bg-white border-none rounded-full px-8 py-5 focus:ring-2 focus:ring-black transition-all outline-none shadow-sm">
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-widest ml-4">How can we help?</label>
                            <textarea name="message" rows="5" required placeholder="Your message here..." 
                                class="w-full bg-white border-none rounded-[2rem] px-8 py-6 focus:ring-2 focus:ring-black transition-all outline-none shadow-sm resize-none"></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full md:w-auto bg-black text-white px-16 py-5 rounded-full text-[11px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition-all shadow-xl shadow-black/10">
                                Send Enquiry
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 3. Full Width Map Placeholder (Added for visual weight) -->
<section class="h-[500px] w-full bg-gray-100 grayscale hover:grayscale-0 transition-all duration-1000">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d113912.44111394236!2d78.33069152331402!3d27.148154179375107!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3974e64f0f0f0f0f%3A0x0f0f0f0f0f0f0f0f!2sFirozabad%2C%20Uttar%20Pradesh!5e0!3m2!1sen!2sin!4v1650000000000!5m2!1sen!2sin" 
        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
    </iframe>
</section>

<?php include 'includes/web_footer.php'; ?>