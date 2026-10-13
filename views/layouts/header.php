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
    <title>JobPortal - Modern Tech & Career Platform</title>
    <!-- Fonts -->
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
                            200: '#c7d2fe',
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
<body class="min-h-full font-sans antialiased text-slate-800 flex flex-col justify-between">
    <!-- Modern Header -->
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="index.php" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-brand-600 text-white flex items-center justify-center font-extrabold text-base shadow-xs">
                    J
                </div>
                <div class="flex flex-col">
                    <span class="text-base font-extrabold text-slate-900 tracking-tight leading-none">JobPortal</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">CAREER PLATFORM</span>
                </div>
            </a>

            <!-- Central Pill Nav -->
            <nav class="hidden md:flex items-center bg-slate-100/80 p-1 rounded-full border border-slate-200/80 text-xs font-semibold text-slate-600">
                <a href="jobs.php" class="px-4 py-1.5 rounded-full hover:text-slate-900 transition">Browse Jobs</a>
                <?php if ($isLoggedIn && $userRole === 'job_seeker'): ?>
                    <a href="applications.php" class="px-4 py-1.5 rounded-full hover:text-slate-900 transition">My Applications</a>
                <?php elseif ($isLoggedIn && $userRole === 'employer'): ?>
                    <a href="employer-dashboard.php" class="px-4 py-1.5 rounded-full hover:text-slate-900 transition">Employer Dashboard</a>
                <?php endif; ?>
            </nav>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                <?php if ($isLoggedIn): ?>
                    <a href="profile.php" class="text-xs font-bold text-slate-700 hover:text-brand-600 transition flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-extrabold text-xs">
                            <?= strtoupper(substr($userName, 0, 1)) ?>
                        </span>
                        <span class="hidden sm:inline"><?= htmlspecialchars($userName) ?></span>
                    </a>
                    <a href="logout.php" class="px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold transition">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="login.php" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition px-2">Sign in</a>
                    <a href="login.php" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                        Get Started
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="flex-grow">
