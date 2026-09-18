<?php
$pageTitle = 'Login - Job Portal';
require_once dirname(__DIR__) . '/layouts/header.php';
require_once dirname(__DIR__, 2) . '/helpers/session.php';
?>

<div class="auth-card">
    <h2>Account Login</h2>

    <?php if (hasFlash('error')): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars(getFlash('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars(getFlash('success')) ?>
        </div>
    <?php endif; ?>

    <form action="/job-portal/public/login.php" method="POST" class="form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" required placeholder="name@example.com">

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required placeholder="••••••••">

        <button type="submit" class="btn-primary">Sign In</button>
    </form>

    <p class="auth-footer">
        Don't have an account? <a href="/job-portal/public/register.php">Create one here</a>.
    </p>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>