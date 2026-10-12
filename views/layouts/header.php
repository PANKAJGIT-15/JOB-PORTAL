<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$userRole   = $_SESSION['role'] ?? null;
$userName   = $_SESSION['name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'JobPortal - Find Your Career' ?></title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-full flex flex-col font-sans text-slate-800 antialiased selection:bg-brand-500 selection:text-white">
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-2 group">
                <div class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center text-white font-extrabold text-lg shadow-sm group-hover:scale-105 transition">
                    J
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-900">Job<span class="text-brand-600">Portal</span></span>
            </a>

            <nav class="flex items-center gap-6">
                <a href="jobs.php" class="text-sm font-semibold text-slate-600 hover:text-brand-600 transition">Browse Jobs</a>
                
                <?php if ($isLoggedIn): ?>
                    <a href="applications.php" class="text-sm font-semibold text-slate-600 hover:text-brand-600 transition">Applications</a>
                    <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-900"><?= htmlspecialchars($userName) ?></p>
                            <span class="text-[10px] uppercase tracking-wider font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                <?= htmlspecialchars(str_replace('_', ' ', $userRole)) ?>
                            </span>
                        </div>
                        <a href="logout.php" class="text-xs font-semibold px-3 py-2 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition">
                            Logout
                        </a>
                    </div>
                <?php else: ?>
                    <div class="flex items-center gap-3">
                        <a href="login.php" class="text-sm font-semibold text-slate-700 hover:text-brand-600 transition">Sign in</a>
                        <a href="register.php" class="text-sm font-semibold px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white shadow-sm transition">Get Started</a>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="flex-1">