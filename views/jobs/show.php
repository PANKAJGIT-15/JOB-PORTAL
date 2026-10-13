<?php
require_once dirname(dirname(__DIR__)) . '/config/database.php';
require_once dirname(dirname(__DIR__)) . '/models/Job.php';
require_once dirname(dirname(__DIR__)) . '/views/layouts/header.php';

$jobId = (int)($_GET['id'] ?? 0);
if (!$jobId) {
    header("Location: jobs.php");
    exit;
}

$db = Database::connect();
$stmt = $db->prepare("SELECT j.*, c.name AS category_name, u.name AS employer_name 
                      FROM jobs j 
                      LEFT JOIN categories c ON j.category_id = c.id 
                      LEFT JOIN users u ON j.employer_id = u.id 
                      WHERE j.id = :id AND j.status = 'active'");
$stmt->execute([':id' => $jobId]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    header("Location: jobs.php");
    exit;
}

$companyInitial = strtoupper(substr($job['company_name'] ?? 'C', 0, 1));
$typeBadgeColor = ($job['job_type'] === 'full-time') ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-blue-50 text-blue-700 border-blue-200';

$isLoggedIn = isset($_SESSION['user_id']);
$userRole   = $_SESSION['role'] ?? null;
$userId     = $_SESSION['user_id'] ?? null;

$hasApplied = false;
if ($isLoggedIn && $userRole === 'job_seeker') {
    $chk = $db->prepare("SELECT id FROM applications WHERE job_id = :jid AND user_id = :uid");
    $chk->execute([':jid' => $jobId, ':uid' => $userId]);
    $hasApplied = (bool)$chk->fetch();
}

$statusAlert = $_GET['status'] ?? null;
?>

<div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <?php if ($statusAlert === 'success'): ?>
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
            <span class="flex items-center gap-2">🎉 Application submitted successfully! The recruiter will review your profile.</span>
            <a href="applications.php" class="underline hover:text-emerald-950">View Applications →</a>
        </div>
    <?php elseif ($statusAlert === 'already_applied'): ?>
        <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold shadow-xs">
            ℹ️ You have already submitted an application for this role.
        </div>
    <?php endif; ?>

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6">
        <a href="jobs.php" class="hover:text-brand-600 transition">Jobs</a>
        <span>/</span>
        <span class="text-slate-400"><?= htmlspecialchars($job['category_name'] ?? 'General') ?></span>
        <span>/</span>
        <span class="text-slate-800 truncate"><?= htmlspecialchars($job['title']) ?></span>
    </nav>

    <!-- Header Banner Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xs mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-start sm:items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center font-extrabold text-brand-700 text-2xl shrink-0 shadow-xs">
                <?= htmlspecialchars($companyInitial) ?>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider"><?= htmlspecialchars($job['company_name']) ?></span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                    <?= htmlspecialchars($job['title']) ?>
                </h1>
                <div class="flex flex-wrap items-center gap-2 mt-2.5">
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full border <?= $typeBadgeColor ?>">
                        <?= htmlspecialchars(ucfirst(str_replace('-', ' ', $job['job_type']))) ?>
                    </span>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                        📍 <?= htmlspecialchars($job['location']) ?>
                    </span>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                        💼 <?= htmlspecialchars($job['category_name'] ?? 'General') ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="flex sm:flex-col items-center sm:items-end justify-between border-t sm:border-t-0 pt-4 sm:pt-0 border-slate-100">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block text-left sm:text-right">Offered Compensation</span>
                <span class="text-xl font-extrabold text-slate-900"><?= htmlspecialchars($job['salary'] ?? 'Competitive') ?></span>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-6">
                <div>
                    <h2 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span>📝</span> Role Overview & Scope
                    </h2>
                    <div class="text-sm text-slate-600 leading-relaxed space-y-3 whitespace-pre-line">
                        <?= htmlspecialchars($job['description']) ?>
                    </div>
                </div>

                <?php if (!empty($job['requirements'])): ?>
                    <div class="pt-6 border-t border-slate-100">
                        <h2 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2">
                            <span>🎯</span> Key Requirements & Skills
                        </h2>
                        <div class="text-sm text-slate-600 leading-relaxed space-y-2 whitespace-pre-line">
                            <?= htmlspecialchars($job['requirements']) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <aside class="lg:col-span-1 lg:sticky lg:top-24 space-y-5">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs space-y-5">
                <h3 class="text-sm font-bold text-slate-900">Application Summary</h3>

                <ul class="space-y-3 text-xs border-y border-slate-100 py-4">
                    <li class="flex items-center justify-between text-slate-600">
                        <span class="text-slate-400">Company</span>
                        <span class="font-bold text-slate-800"><?= htmlspecialchars($job['company_name']) ?></span>
                    </li>
                    <li class="flex items-center justify-between text-slate-600">
                        <span class="text-slate-400">Location</span>
                        <span class="font-bold text-slate-800"><?= htmlspecialchars($job['location']) ?></span>
                    </li>
                    <li class="flex items-center justify-between text-slate-600">
                        <span class="text-slate-400">Job Type</span>
                        <span class="font-bold text-slate-800"><?= htmlspecialchars(ucfirst($job['job_type'])) ?></span>
                    </li>
                    <li class="flex items-center justify-between text-slate-600">
                        <span class="text-slate-400">Date Posted</span>
                        <span class="font-bold text-slate-800"><?= date('M d, Y', strtotime($job['created_at'])) ?></span>
                    </li>
                </ul>

                <?php if ($hasApplied): ?>
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-center text-xs text-emerald-700 font-bold">
                        ✓ Applied
                    </div>
                <?php elseif ($isLoggedIn && $userRole === 'job_seeker'): ?>
                    <button type="button" onclick="document.getElementById('applyModal').classList.remove('hidden')" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-sm transition shadow-sm">
                        Apply for this Position →
                    </button>
                <?php elseif ($isLoggedIn && $userRole === 'employer'): ?>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-xs text-slate-500 font-medium">
                        Employer accounts cannot apply to listings.
                    </div>
                <?php else: ?>
                    <a href="login.php" class="block w-full py-3 bg-brand-600 hover:bg-brand-700 text-white text-center font-bold rounded-xl text-sm transition shadow-sm">
                        Sign in to Apply →
                    </a>
                <?php endif; ?>
            </div>
        </aside>
    </div>
</div>

<!-- Application Modal Dialog -->
<div id="applyModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 relative">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-900">Submit Your Application</h3>
                <p class="text-xs text-slate-500 mt-0.5">Applying for <?= htmlspecialchars($job['title']) ?></p>
            </div>
            <button onclick="document.getElementById('applyModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center text-sm font-bold">
                ✕
            </button>
        </div>

        <form action="apply.php" method="POST" class="space-y-4">
            <input type="hidden" name="job_id" value="<?= $job['id'] ?>">

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Short Cover Note</label>
                <textarea 
                    name="cover_note" 
                    rows="4" 
                    placeholder="Briefly explain why you're a great fit for this position..."
                    class="w-full p-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50"
                ></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('applyModal').classList.add('hidden')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-xs transition shadow-xs">
                    Confirm & Submit →
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once dirname(dirname(__DIR__)) . '/views/layouts/footer.php'; ?>