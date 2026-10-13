<?php
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/views/layouts/header.php';

$db = Database::connect();
$categories = $db->query("SELECT * FROM categories LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="relative overflow-hidden bg-gradient-to-b from-brand-50/50 to-white py-16 sm:py-24">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-brand-100 text-brand-700 mb-6 border border-brand-200">
            🚀 The #1 Hiring Destination for Tech & Product Roles
        </span>
        <h1 class="text-4xl sm:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight sm:leading-tight mb-6">
            Find Your Next Career <br class="hidden sm:block" />
            <span class="bg-gradient-to-r from-brand-600 to-indigo-600 bg-clip-text text-transparent">Opportunity Today</span>
        </h1>
        <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed font-normal">
            Connect directly with verified hiring teams across high-growth startups and leading enterprises worldwide.
        </p>

        <!-- Search Bar Container -->
        <div class="max-w-2xl mx-auto bg-white p-2.5 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200 mb-8">
            <form action="jobs.php" method="GET" class="flex flex-col sm:flex-row gap-2">
                <div class="flex-1 flex items-center px-3.5 py-1">
                    <svg class="w-5 h-5 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Job title, keywords, or company..." 
                        class="w-full text-sm text-slate-800 placeholder-slate-400 bg-transparent focus:outline-none"
                    >
                </div>
                <button 
                    type="submit" 
                    class="px-7 py-3.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-md transition transform hover:-translate-y-0.5 active:translate-y-0"
                >
                    Search Jobs
                </button>
            </form>
        </div>

        <!-- Quick Filter Tags -->
        <div class="flex flex-wrap items-center justify-center gap-2 text-xs text-slate-500 mb-16">
            <span class="font-medium text-slate-400">Popular:</span>
            <?php foreach ($categories as $cat): ?>
                <a href="jobs.php?category=<?= $cat['id'] ?>" class="px-3 py-1 rounded-full bg-white border border-slate-200 hover:border-brand-500 hover:text-brand-600 transition font-medium">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Value Proposition Feature Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
            <div class="p-6 bg-white rounded-2xl border border-slate-200/70 shadow-sm hover:shadow-md transition">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold mb-4">
                    ⚡
                </div>
                <h2 class="font-bold text-base text-slate-900 mb-2">Verified Roles Only</h2>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Zero spam. Every posting is manually verified with direct hiring teams.
                </p>
            </div>
            <div class="p-6 bg-white rounded-2xl border border-slate-200/70 shadow-sm hover:shadow-md transition">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold mb-4">
                    🎯
                </div>
                <h2 class="font-bold text-base text-slate-900 mb-2">Transparent Compensation</h2>
                <p class="text-xs text-slate-600 leading-relaxed">
                    See salary ranges upfront so you never waste time on low-ball offers.
                </p>
            </div>
            <div class="p-6 bg-white rounded-2xl border border-slate-200/70 shadow-sm hover:shadow-md transition">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold mb-4">
                    📈
                </div>
                <h2 class="font-bold text-base text-slate-900 mb-2">Direct Applications</h2>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Track your status in real-time without getting lost in third-party ATS loops.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/views/layouts/footer.php'; ?>