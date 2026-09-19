<?php
$pageTitle = htmlspecialchars($job['title']) . ' - Job Details';
require_once dirname(__DIR__) . '/layouts/header.php';
require_once dirname(__DIR__, 2) . '/helpers/session.php';
?>

<div class="job-detail-card">
    <div class="detail-header">
        <div>
            <h2><?= htmlspecialchars($job['title']) ?></h2>
            <h4 class="company-name"><?= htmlspecialchars($job['company_name']) ?> &bull; <?= htmlspecialchars($job['location']) ?></h4>
        </div>
        <span class="badge badge-type"><?= htmlspecialchars($job['job_type']) ?></span>
    </div>

    <div class="detail-meta-grid">
        <div>
            <strong>Category:</strong> <?= htmlspecialchars($job['category_name']) ?>
        </div>
        <?php if (!empty($job['salary_range'])): ?>
            <div>
                <strong>Salary:</strong> <?= htmlspecialchars($job['salary_range']) ?>
            </div>
        <?php endif; ?>
        <div>
            <strong>Posted On:</strong> <?= date('F d, Y', strtotime($job['created_at'])) ?>
        </div>
    </div>

    <hr class="divider">

    <div class="job-section">
        <h3>Description</h3>
        <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>
    </div>

    <?php if (!empty($job['requirements'])): ?>
        <div class="job-section">
            <h3>Requirements & Qualifications</h3>
            <p><?= nl2br(htmlspecialchars($job['requirements'])) ?></p>
        </div>
    <?php endif; ?>

    <div class="apply-action-area">
        <?php if (isLoggedIn()): ?>
            <?php if (currentUser()['role'] === 'job_seeker'): ?>
                <a href="/job-portal/public/apply.php?job_id=<?= $job['id'] ?>" class="btn-primary">Apply For This Position</a>
            <?php else: ?>
                <p class="role-hint">Logged in as an employer. Switch to a candidate account to apply.</p>
            <?php endif; ?>
        <?php else: ?>
            <a href="/job-portal/public/login.php" class="btn-primary">Login to Apply</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>