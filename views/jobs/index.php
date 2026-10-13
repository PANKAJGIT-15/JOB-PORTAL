<?php
require_once dirname(dirname(__DIR__)) . '/config/database.php';
require_once dirname(dirname(__DIR__)) . '/models/Job.php';
require_once dirname(dirname(__DIR__)) . '/views/layouts/header.php';

$jobModel = new Job();
$keyword = !empty($_GET['q']) ? trim($_GET['q']) : null;
$categoryId = !empty($_GET['category']) ? (int)$_GET['category'] : null;

$jobs = $jobModel->getAllActive($keyword, $categoryId);

$db = Database::connect();
$categories = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <!-- Top Heading -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Available Opportunities</h1>
        <p class="text-sm text-slate-500 mt-1">Explore verified positions from tech teams and fast-growing organizations.</p>
    </div>

    <!-- Search / Filter Bar -->
    <form action="jobs.php" method="GET" class="bg-white p-3.5 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row gap-3 items-center mb-8">
        <div class="flex-1 w-full relative">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input 
                type="text" 
                name="q" 
                placeholder="Search by job title, company, or keywords..." 
                value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" 
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50"
            >
        </div>

        <select 
            name="category" 
            class="w-full md:w-56 px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50 text-slate-700"
        >
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="flex gap-2 w-full md:w-auto">
            <button 
                type="submit" 
                class="flex-1 md:flex-none px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl text-sm transition shadow-xs"
            >
                Filter
            </button>
            <a 
                href="jobs.php" 
                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-sm transition text-center"
            >
                Reset
            </a>
        </div>
    </form>

    <!-- Modern Job Cards Grid -->
    <?php if (empty($jobs)): ?>
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
            <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xl mb-3">🔍</div>
            <p class="font-bold text-slate-800 text-base">No matching positions found</p>
            <p class="text-xs text-slate-500 mt-1">Try resetting your keywords or clearing the category filter.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach ($jobs as $job): ?>
                <?php 
                    $companyInitial = strtoupper(substr($job['company_name'] ?? 'C', 0, 1));
                    $typeBadgeColor = ($job['job_type'] === 'full-time') 
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200' 
                        : 'bg-blue-50 text-blue-700 border-blue-200';
                ?>
                <div class="group bg-white rounded-2xl p-5 border border-slate-200/90 hover:border-brand-300 hover:shadow-lg hover:shadow-brand-500/5 transition duration-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-extrabold text-slate-700 text-base group-hover:bg-brand-50 group-hover:text-brand-600 transition">
                                    <?= htmlspecialchars($companyInitial) ?>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 leading-tight"><?= htmlspecialchars($job['company_name']) ?></p>
                                    <h2 class="text-base font-bold text-slate-900 group-hover:text-brand-600 transition line-clamp-1 mt-0.5">
                                        <?= htmlspecialchars($job['title']) ?>
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <!-- Pills -->
                        <div class="flex flex-wrap items-center gap-1.5 my-3">
                            <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full border <?= $typeBadgeColor ?>">
                                <?= htmlspecialchars(ucfirst(str_replace('-', ' ', $job['job_type']))) ?>
                            </span>
                            <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                                📍 <?= htmlspecialchars($job['location']) ?>
                            </span>
                        </div>

                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed mb-4">
                            <?= htmlspecialchars($job['description']) ?>
                        </p>
                    </div>

                    <!-- Card Bottom -->
                    <div class="pt-3.5 border-t border-slate-100 flex items-center justify-between mt-auto">
                        <div>
                            <span class="text-[10px] text-slate-400 font-medium block">COMPENSATION</span>
                            <span class="text-xs font-bold text-slate-900"><?= htmlspecialchars($job['salary'] ?? 'Not Disclosed') ?></span>
                        </div>
                        <a 
                            href="job-detail.php?id=<?= $job['id'] ?>" 
                            class="px-3.5 py-1.5 rounded-lg bg-slate-100 group-hover:bg-brand-600 group-hover:text-white text-slate-700 text-xs font-bold transition flex items-center gap-1"
                        >
                            Apply →
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once dirname(dirname(__DIR__)) . '/views/layouts/footer.php'; ?>