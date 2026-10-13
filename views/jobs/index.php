<?php
require_once dirname(dirname(__DIR__)) . '/config/database.php';
require_once dirname(dirname(__DIR__)) . '/models/Job.php';
require_once dirname(dirname(__DIR__)) . '/views/layouts/header.php';

$jobModel   = new Job();
$keyword    = !empty($_GET['q']) ? trim($_GET['q']) : null;
$categoryId = !empty($_GET['category']) ? (int)$_GET['category'] : null;
$jobType    = !empty($_GET['type']) ? trim($_GET['type']) : null;
$page       = max(1, (int)($_GET['page'] ?? 1));
$perPage    = 4;

$totalJobs  = $jobModel->countActive($keyword, $categoryId, $jobType);
$totalPages = ceil($totalJobs / $perPage);
$jobs       = $jobModel->getPaginatedActive($keyword, $categoryId, $jobType, $page, $perPage);
$categoryCounts = $jobModel->getCategoryCounts();

function buildQuery($overrides = []) {
    $params = array_merge($_GET, $overrides);
    return http_build_query($params);
}
?>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Browse Open Positions</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Discover vetted opportunities tailored to your domain and preferred work model.</p>
        </div>
        <span class="inline-flex items-center text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200 w-fit">
            Total <?= $totalJobs ?> Position<?= $totalJobs === 1 ? '' : 's' ?>
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        <!-- Sidebar Filter -->
        <aside class="lg:col-span-1 lg:sticky lg:top-24 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <span>⚡</span> Filter Results
                </h2>
                <?php if ($keyword || $categoryId || $jobType): ?>
                    <a href="jobs.php" class="text-xs font-semibold text-rose-600 hover:underline">Reset All</a>
                <?php endif; ?>
            </div>

            <form action="jobs.php" method="GET" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Search</label>
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Title, skill, location..." 
                        value="<?= htmlspecialchars($keyword ?? '') ?>" 
                        class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Job Type</label>
                    <div class="space-y-1.5">
                        <?php 
                        $types = ['' => 'All Types', 'full-time' => 'Full Time', 'part-time' => 'Part Time', 'remote' => 'Remote'];
                        foreach ($types as $val => $label): 
                            $isActive = ($jobType === $val) || (empty($jobType) && empty($val));
                        ?>
                            <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer hover:text-slate-900">
                                <input type="radio" name="type" value="<?= $val ?>" <?= $isActive ? 'checked' : '' ?> onchange="this.form.submit()" class="text-brand-600 focus:ring-brand-500">
                                <span><?= $label ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Categories</label>
                    <div class="space-y-1.5 max-h-56 overflow-y-auto pr-1">
                        <label class="flex items-center justify-between text-xs text-slate-600 cursor-pointer hover:text-slate-900">
                            <span class="flex items-center gap-2">
                                <input type="radio" name="category" value="" <?= empty($categoryId) ? 'checked' : '' ?> onchange="this.form.submit()" class="text-brand-600 focus:ring-brand-500">
                                All Categories
                            </span>
                        </label>
                        <?php foreach ($categoryCounts as $cat): ?>
                            <label class="flex items-center justify-between text-xs text-slate-600 cursor-pointer hover:text-slate-900">
                                <span class="flex items-center gap-2">
                                    <input type="radio" name="category" value="<?= $cat['id'] ?>" <?= ($categoryId == $cat['id']) ? 'checked' : '' ?> onchange="this.form.submit()" class="text-brand-600 focus:ring-brand-500">
                                    <span class="truncate"><?= htmlspecialchars($cat['name']) ?></span>
                                </span>
                                <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded-full"><?= $cat['total_jobs'] ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl text-xs transition shadow-xs">
                    Apply Filters
                </button>
            </form>
        </aside>

        <!-- Right Feed -->
        <main class="lg:col-span-3 space-y-6">
            <?php if (empty($jobs)): ?>
                <!-- Clean Modern Empty State -->
                <div class="bg-white rounded-3xl border border-dashed border-slate-300 p-12 text-center shadow-xs">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-2xl mb-4">
                        🔎
                    </div>
                    <h3 class="text-base font-bold text-slate-800">No positions match your search</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        We couldn't find any opportunities matching your active query. Try broadening your keywords or clearing selected filters.
                    </p>
                    <div class="mt-5 flex justify-center gap-3">
                        <a href="jobs.php" class="px-4 py-2 bg-brand-600 text-white text-xs font-semibold rounded-xl hover:bg-brand-700 transition">
                            Reset Filters
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($jobs as $job): ?>
                        <?php 
                            $companyInitial = strtoupper(substr($job['company_name'] ?? 'C', 0, 1));
                            $typeBadgeColor = ($job['job_type'] === 'full-time') ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-blue-50 text-blue-700 border-blue-200';
                        ?>
                        <div class="group bg-white rounded-2xl p-5 border border-slate-200/90 hover:border-brand-300 hover:shadow-lg hover:shadow-brand-500/5 transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-extrabold text-slate-700 text-sm group-hover:bg-brand-50 group-hover:text-brand-600 transition">
                                        <?= htmlspecialchars($companyInitial) ?>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[11px] font-semibold text-slate-500 truncate"><?= htmlspecialchars($job['company_name']) ?></p>
                                        <h2 class="text-sm font-bold text-slate-900 group-hover:text-brand-600 transition truncate">
                                            <?= htmlspecialchars($job['title']) ?>
                                        </h2>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-1.5 my-2.5">
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full border <?= $typeBadgeColor ?>">
                                        <?= htmlspecialchars(ucfirst(str_replace('-', ' ', $job['job_type']))) ?>
                                    </span>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                                        📍 <?= htmlspecialchars($job['location']) ?>
                                    </span>
                                </div>

                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed mb-4">
                                    <?= htmlspecialchars($job['description']) ?>
                                </p>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between mt-auto">
                                <div>
                                    <span class="text-[9px] text-slate-400 font-medium block">COMPENSATION</span>
                                    <span class="text-xs font-bold text-slate-900"><?= htmlspecialchars($job['salary'] ?? 'Not Disclosed') ?></span>
                                </div>
                                <a href="job-detail.php?id=<?= $job['id'] ?>" class="px-3 py-1.5 rounded-lg bg-slate-100 group-hover:bg-brand-600 group-hover:text-white text-slate-700 text-xs font-bold transition">
                                    Apply →
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination Component -->
                <?php if ($totalPages > 1): ?>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                        <p class="text-xs text-slate-500">Page <span class="font-bold text-slate-800"><?= $page ?></span> of <span class="font-bold text-slate-800"><?= $totalPages ?></span></p>
                        <div class="flex items-center gap-1">
                            <?php if ($page > 1): ?>
                                <a href="jobs.php?<?= buildQuery(['page' => $page - 1]) ?>" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">Previous</a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="jobs.php?<?= buildQuery(['page' => $i]) ?>" class="px-3 py-1.5 text-xs font-semibold rounded-lg <?= ($i === $page) ? 'bg-brand-600 text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="jobs.php?<?= buildQuery(['page' => $page + 1]) ?>" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">Next</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php require_once dirname(dirname(__DIR__)) . '/views/layouts/footer.php'; ?>