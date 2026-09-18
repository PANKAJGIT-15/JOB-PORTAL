<?php
$appName = $appName ?? 'Job Portal';
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
            <a href="/job-portal/public/">Jobs</a>
            <a href="/job-portal/public/login.php">Login</a>
            <a href="/job-portal/public/register.php" class="btn-nav">Register</a>
        </nav>
    </div>
</header>
<main class="container main-content">