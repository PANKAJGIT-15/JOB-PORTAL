<?php
require_once dirname(__DIR__, 2) . '/helpers/session.php';
$appName = $appName ?? 'Job Portal';
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? $appName) ?></title>
    <link rel="stylesheet" href="/job-portal/public/css/style.css">
</head>
<body>
<header class="navbar">
    <div class="container nav-container">
        <a href="/job-portal/public/" class="logo"><?= htmlspecialchars($appName) ?></a>
        <nav class="nav-links">
            <a href="/job-portal/public/">Browse Jobs</a>
            <?php if (isLoggedIn()): ?>
                <span class="user-greeting">Hi, <?= htmlspecialchars($user['name'] ?? 'User') ?> (<?= htmlspecialchars($user['role'] ?? '') ?>)</span>
                <a href="/job-portal/public/logout.php" class="btn-nav">Logout</a>
            <?php else: ?>
                <a href="/job-portal/public/login.php">Login</a>
                <a href="/job-portal/public/register.php" class="btn-nav">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container main-content">