<!-- Tailwind CSS Lightweight CDN Link -->
<script src="https://cdn.tailwindcss.com"></script>

<div class="fixed top-0 left-0 h-screen w-64 bg-white border-r border-slate-200/80 flex flex-col justify-between p-6 font-sans antialiased selection:bg-slate-100 z-50">
    <div class="space-y-7 overflow-y-auto pr-1">
        
        <!-- Sidebar Branding Header -->
        <div>
            <span class="text-[10px] font-bold tracking-widest text-slate-400 uppercase block">Management Portal</span>
            <h3 class="text-lg font-semibold tracking-tight text-slate-900 mt-1">Admin Panel</h3>
        </div>

        <!-- Navigation Menu -->
        <nav class="space-y-6">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2.5 px-2">Core Menu</span>
                <div class="space-y-1">
                    <a href="../admin/dashboard.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        Dashboard
                    </a>
                    <a href="../admin/users.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        Members
                    </a>
                    <a href="../admin/notices.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        Notices
                    </a>
                    <a href="../admin/events.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        Events
                    </a>
                    <a href="../admin/gallery.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        Gallery
                    </a>
                    <a href="../admin/enquiries.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        Enquiries
                    </a>
                </div>
            </div>

            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2.5 px-2">Financials</span>
                <div class="space-y-1">
                    <a href="approve_payments.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        UPI Payments
                    </a>
                    <a href="pay_membership.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        CASH Payments
                    </a>
                    <a href="transactions.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        Transactions
                    </a>
                    <a href="receipts.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        Receipts
                    </a>
                </div>
            </div>

            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2.5 px-2">Outreach</span>
                <div class="space-y-1">
                    <a href="donations.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        Donations
                    </a>
                    <a href="campaigns.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        Campaigns
                    </a>
                    <a href="create_campaign.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        Create Campaigns
                    </a>
                </div>
            </div>

            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2.5 px-2">Documents</span>
                <div class="space-y-1">
                    <a href="id_cards.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        ID Cards
                    </a>
                    <a href="certificates.php" class="flex items-center text-sm text-slate-600 hover:text-slate-900 font-medium hover:bg-slate-50 px-3 py-2 rounded-lg transition-all duration-200">
                        Certificates
                    </a>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2.5 px-2">Export Reports</span>
                <div class="space-y-1.5">
                    <a href="export_transactions.php" class="flex items-center justify-between text-xs text-slate-700 hover:text-slate-900 font-medium bg-slate-50 hover:bg-slate-100/80 border border-slate-200/60 px-3 py-2 rounded-lg transition-all duration-200 shadow-sm">
                        <span>Transactions CSV</span>
                        <span class="text-slate-400 text-[10px]">↓</span>
                    </a>
                    <a href="export_members.php" class="flex items-center justify-between text-xs text-slate-700 hover:text-slate-900 font-medium bg-slate-50 hover:bg-slate-100/80 border border-slate-200/60 px-3 py-2 rounded-lg transition-all duration-200 shadow-sm">
                        <span>Members CSV</span>
                        <span class="text-slate-400 text-[10px]">↓</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Bottom Actions Area -->
    <div class="pt-4 border-t border-slate-100">
        <a href="../admin/logout.php" class="flex items-center justify-center w-full text-xs font-semibold text-rose-600 hover:text-white bg-white hover:bg-rose-600 border border-rose-200 hover:border-rose-600 py-2.5 px-4 rounded-xl shadow-sm transition-all duration-200">
            Logout
        </a>
    </div>
</div>

<!-- Main Content Wrapper (Offsets the fixed 64rem sidebar beautifully on desktops) -->
<div class="pl-0 md:pl-64 min-h-screen bg-[#f8fafc]">
    <div class="p-4 md:p-8 text-[#334155]">