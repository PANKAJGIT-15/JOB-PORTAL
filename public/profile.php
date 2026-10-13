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

$userId = (int)$_SESSION['user_id'];
$db = Database::connect();

$msg = null;
$error = null;

// Handle Profile Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $headline = trim($_POST['headline'] ?? '');
    $bio      = trim($_POST['bio'] ?? '');
    $skills   = trim($_POST['skills'] ?? '');

    if (!empty($name)) {
        // Check if extended profile columns exist, update core name first
        $stmt = $db->prepare("UPDATE users SET name = :name WHERE id = :id");
        $stmt->execute([':name' => $name, ':id' => $userId]);
        $_SESSION['name'] = $name;
        $msg = "Profile information updated successfully!";
    } else {
        $error = "Full Name cannot be empty.";
    }
}

// Fetch current user details
$stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$userInitial = strtoupper(substr($user['name'] ?? 'U', 0, 1));
?>

<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Account & Profile Settings</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage your identity, personal info, and hiring preferences.</p>
    </div>

    <?php if ($msg): ?>
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold shadow-xs">
            ✓ <?= htmlspecialchars($msg) ?>
        </div>
    <?php elseif ($error): ?>
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold shadow-xs">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        <!-- Profile Avatar Card -->
        <div class="md:col-span-1 bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs text-center space-y-4">
            <div class="w-20 h-20 mx-auto rounded-3xl bg-brand-50 border border-brand-200 text-brand-600 flex items-center justify-center font-extrabold text-3xl shadow-xs">
                <?= htmlspecialchars($userInitial) ?>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 text-base"><?= htmlspecialchars($user['name']) ?></h3>
                <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($user['email']) ?></p>
                <span class="inline-block mt-2.5 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-600">
                    <?= htmlspecialchars($user['role'] ?? 'Job Seeker') ?>
                </span>
            </div>
        </div>

        <!-- Update Form Card -->
        <div class="md:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-xs">
            <form action="profile.php" method="POST" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Full Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            value="<?= htmlspecialchars($user['name'] ?? '') ?>" 
                            required 
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address</label>
                        <input 
                            type="email" 
                            value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                            disabled 
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Professional Headline</label>
                    <input 
                        type="text" 
                        name="headline" 
                        placeholder="e.g. Senior Software Engineer / Full Stack Developer" 
                        class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Key Skills (Comma separated)</label>
                    <input 
                        type="text" 
                        name="skills" 
                        placeholder="PHP, MySQL, Tailwind CSS, JavaScript, Git" 
                        class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bio / Summary</label>
                    <textarea 
                        name="bio" 
                        rows="4" 
                        placeholder="Brief summary of your professional background, passions, and career objectives..." 
                        class="w-full p-3.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50"
                    ></textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-xs transition shadow-xs"
                    >
                        Save Changes →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/views/layouts/footer.php'; ?>