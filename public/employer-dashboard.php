<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/views/layouts/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$employerId = (int)$_SESSION['user_id'];
$db = Database::connect();

// Fetch Employer Jobs
$stmt = $db->prepare("SELECT j.*, c.name AS category_name, 
                      (SELECT COUNT(*) FROM applications WHERE job_id = j.id) AS total_applicants
                      FROM jobs j
                      LEFT JOIN categories c ON j.category_id = c.id
                      WHERE j.employer_id = :eid
                      ORDER BY j.created_at DESC");
$stmt->execute([':eid' => $employerId]);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPosted = count($jobs);
$totalApplicants = array_sum(array_column($jobs, 'total_applicants'));
?>

<div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Employer Dashboard</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage your active job postings and candidate pipelines.</p>
        </div>
        <a href="create-job.php" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-xs transition shadow-sm w-fit">
            + Post New Opening
        </a>
    </div>

    <!-- Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Active Openings</span>
            <span class="text-2xl font-extrabold text-slate-900 mt-1 block"><?= $totalPosted ?></span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
            <span class="text-[11px] font-bold text-brand-600 uppercase tracking-wider block">Total Received Applicants</span>
            <span class="text-2xl font-extrabold text-brand-600 mt-1 block"><?= $totalApplicants ?></span>
        </div>
    </div>

    <!-- Listings Table -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xs overflow-hidden">
        <?php if (empty($jobs)): ?>
            <div class="p-12 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mb-3">💼</div>
                <h3 class="text-sm font-bold text-slate-800">No active positions posted</h3>
                <p class="text-xs text-slate-500 mt-1">Get started by posting your company's first vacancy.</p>
                <a href="create-job.php" class="inline-block mt-4 px-4 py-2 bg-brand-600 text-white font-semibold text-xs rounded-xl hover:bg-brand-700 transition">
                    Create Listing →
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-4 px-6">Job Title</th>
                            <th class="py-4 px-6">Category / Type</th>
                            <th class="py-4 px-6">Applicants</th>
                            <th class="py-4 px-6">Date Added</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        <?php foreach ($jobs as $job): ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-900 block text-sm"><?= htmlspecialchars($job['title']) ?></span>
                                    <span class="text-slate-400 text-[11px]">📍 <?= htmlspecialchars($job['location']) ?></span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-slate-700 block"><?= htmlspecialchars($job['category_name'] ?? 'General') ?></span>
                                    <span class="text-slate-400 text-[10px] capitalize"><?= htmlspecialchars($job['job_type']) ?></span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-full bg-brand-50 text-brand-700 border border-brand-200 text-[11px] font-bold">
                                        <?= (int)$job['total_applicants'] ?> Candidate<?= $job['total_applicants'] == 1 ? '' : 's' ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-medium text-slate-500">
                                    <?= date('M d, Y', strtotime($job['created_at'])) ?>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="job-detail.php?id=<?= $job['id'] ?>" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-brand-600 hover:text-white font-bold transition">
                                        Preview →
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/views/layouts/footer.php'; ?>