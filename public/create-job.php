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

$db = Database::connect();
$categories = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

$msg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $company     = trim($_POST['company_name'] ?? '');
    $categoryId  = (int)($_POST['category_id'] ?? 0);
    $jobType     = trim($_POST['job_type'] ?? 'full-time');
    $location    = trim($_POST['location'] ?? '');
    $salary      = trim($_POST['salary'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $requirements= trim($_POST['requirements'] ?? '');
    $employerId  = (int)$_SESSION['user_id'];

    if ($title && $company && $description) {
        $stmt = $db->prepare("INSERT INTO jobs (title, company_name, category_id, job_type, location, salary, description, requirements, employer_id, status, created_at) 
                              VALUES (:title, :company, :cat, :type, :loc, :salary, :desc, :req, :eid, 'active', NOW())");
        $stmt->execute([
            ':title'   => $title,
            ':company' => $company,
            ':cat'     => $categoryId ?: null,
            ':type'    => $jobType,
            ':loc'     => $location,
            ':salary'  => $salary,
            ':desc'    => $description,
            ':req'     => $requirements,
            ':eid'     => $employerId
        ]);
        header("Location: employer-dashboard.php");
        exit;
    } else {
        $msg = "Please fill in all required fields.";
    }
}
?>

<div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Create New Job Opening</h1>
            <p class="text-xs text-slate-500 mt-1">Publish a vetted opening to attract top candidates.</p>
        </div>
        <a href="employer-dashboard.php" class="text-xs font-semibold text-slate-500 hover:text-slate-900">
            ← Back to Dashboard
        </a>
    </div>

    <?php if ($msg): ?>
        <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
            ⚠️ <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <form action="create-job.php" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xs space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Job Title *</label>
                <input type="text" name="title" required placeholder="e.g. Senior Full-Stack Engineer" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Company Name *</label>
                <input type="text" name="company_name" required placeholder="e.g. Acme Tech Solutions" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Category</label>
                <select name="category_id" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Job Type</label>
                <select name="job_type" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50">
                    <option value="full-time">Full Time</option>
                    <option value="part-time">Part Time</option>
                    <option value="remote">Remote</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Location</label>
                <input type="text" name="location" placeholder="e.g. Mumbai, India" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Compensation (Salary)</label>
            <input type="text" name="salary" placeholder="e.g. ₹50,000/month or Competitive" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Job Description *</label>
            <textarea name="description" rows="4" required placeholder="Detail the duties, role expectations, and projects..." class="w-full p-3.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50"></textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Key Requirements</label>
            <textarea name="requirements" rows="3" placeholder="Key technical skills, years of experience, credentials..." class="w-full p-3.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50"></textarea>
        </div>

        <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-xs transition shadow-xs">
            Publish Job Listing →
        </button>
    </form>
</div>

<?php require_once dirname(__DIR__) . '/views/layouts/footer.php'; ?>