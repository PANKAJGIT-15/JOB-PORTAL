<?php
$pageTitle = 'Register - Job Portal';
require_once dirname(__DIR__) . '/layouts/header.php';
require_once dirname(__DIR__, 2) . '/helpers/session.php';
?>

<div class="auth-card">
    <h2>Create an Account</h2>

    <?php if (hasFlash('error')): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars(getFlash('error')) ?>
        </div>
    <?php endif; ?>

    <form action="/job-portal/public/register.php" method="POST" class="form-group">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" required placeholder="John Doe">

        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" required placeholder="john@example.com">

        <label for="role">Register As</label>
        <select name="role" id="role" required>
            <option value="job_seeker">Job Seeker (Candidate)</option>
            <option value="employer">Employer (Company)</option>
        </select>

        <label for="phone">Phone Number</label>
        <input type="tel" name="phone" id="phone" placeholder="+1234567890">

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required placeholder="••••••••">

        <button type="submit" class="btn-primary">Sign Up</button>
    </form>

    <p class="auth-footer">
        Already have an account? <a href="/job-portal/public/login.php">Sign in here</a>.
    </p>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>