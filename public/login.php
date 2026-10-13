<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/views/layouts/header.php';

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $db = Database::connect();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        header("Location: " . ($user['role'] === 'employer' ? 'employer-dashboard.php' : 'jobs.php'));
        exit;
    } else {
        $error = "Invalid email address or password.";
    }
}
?>

<div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-slate-200/90 shadow-xl shadow-slate-200/50">
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 border border-brand-100 flex items-center justify-center font-extrabold text-xl mx-auto mb-3">
                🔐
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Welcome Back</h2>
            <p class="text-xs text-slate-500 mt-1">Sign in to manage your jobs or applications</p>
        </div>

        <?php if ($error): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    required 
                    placeholder="name@example.com" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50"
                >
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                </div>
                <input 
                    type="password" 
                    name="password" 
                    required 
                    placeholder="••••••••" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50/50"
                >
            </div>

            <button 
                type="submit" 
                class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-xs transition shadow-sm mt-2"
            >
                Sign In →
            </button>
        </form>

        <p class="text-center text-xs text-slate-500 mt-6">
            Don't have an account? 
            <a href="register.php" class="font-bold text-brand-600 hover:underline">Create one here</a>
        </p>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/views/layouts/footer.php'; ?>