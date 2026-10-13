<?php
require_once dirname(dirname(__DIR__)) . '/config/database.php';
require_once dirname(dirname(__DIR__)) . '/views/layouts/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = (int)$_SESSION['user_id'];
$db = Database::connect();

$stmt = $db->prepare("SELECT a.*, j.title, j.company_name, j.location, j.job_type, j.salary 
                      FROM applications a 
                      JOIN jobs j ON a.job_id = j.id 
                      WHERE a.user_id = :uid 
                      ORDER BY a.applied_at DESC");
$stmt->execute([':uid' => $userId]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalApplied = count($applications);
$pendingCount = count(array_filter($applications, fn($a) => $a['status'] === 'pending'));
$reviewedCount = count(array_filter($applications, fn($a) => in_array($a['status'], ['reviewed', 'shortlisted'])));
?>

<div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">My Job Applications</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Track and manage your submitted applications and hiring progress in real-time.</p>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Submissions</span>
            <span class="text-2xl font-extrabold text-slate-900 mt-1 block"><?= $totalApplied ?></span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
            <span class="text-[11px] font-bold text-amber-500 uppercase tracking-wider block">Under Review</span>
            <span class="text-2xl font-extrabold text-amber-600 mt-1 block"><?= $pendingCount ?></span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
            <span class="text-[11px] font-bold text-emerald-500 uppercase tracking-wider block">Active / Shortlisted</span>
            <span class="text-2xl font-extrabold text-emerald-600 mt-1 block"><?= $reviewedCount ?></span>
        </div>
    </div>

    <!-- Applications List / Table -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xs overflow-hidden">
        <?php if (empty($applications)): ?>
            <div class="p-12 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mb-3">📁</div>
                <h3 class="text-sm font-bold text-slate-800">No applications found</h3>
                <p class="text-xs text-slate-500 mt-1">You haven't submitted any job applications yet.</p>
                <a href="jobs.php" class="inline-block mt-4 px-4 py-2 bg-brand-600 text-white font-semibold text-xs rounded-xl hover:bg-brand-700 transition">
                    Browse Opportunities →
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-4 px-6">Role & Company</th>
                            <th class="py-4 px-6">Location / Type</th>
                            <th class="py-4 px-6">Applied Date</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        <?php foreach ($applications as $app): ?>
                            <?php
                                $badgeStyle = match($app['status']) {
                                    'shortlisted' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'reviewed'    => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'rejected'    => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default       => 'bg-amber-50 text-amber-700 border-amber-200',
                                };
                            ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-900 block text-sm"><?= htmlspecialchars($app['title']) ?></span>
                                    <span class="text-slate-400 text-[11px]"><?= htmlspecialchars($app['company_name']) ?></span>
                                </td>
                                <td class="py-4 px-6">
                                    <span>📍 <?= htmlspecialchars($app['location']) ?></span>
                                    <span class="block text-slate-400 text-[10px] mt-0.5 capitalize"><?= htmlspecialchars($app['job_type']) ?></span>
                                </td>
                                <td class="py-4 px-6 font-medium text-slate-500">
                                    <?= date('M d, Y', strtotime($app['applied_at'])) ?>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-full border text-[10px] font-bold uppercase tracking-wider <?= $badgeStyle ?>">
                                        <?= htmlspecialchars($app['status']) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="job-detail.php?id=<?= $app['job_id'] ?>" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-brand-600 hover:text-white font-bold transition">
                                        View Job →
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

<?php require_once dirname(dirname(__DIR__)) . '/views/layouts/footer.php'; ?>