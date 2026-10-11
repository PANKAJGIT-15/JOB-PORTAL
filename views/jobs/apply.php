<?php
$pageTitle = 'Apply for ' . htmlspecialchars($job['title']) . ' - Job Portal';
require_once dirname(__DIR__) . '/layouts/header.php';
require_once dirname(__DIR__, 2) . '/helpers/session.php';
?>

<div class="job-form-card">
    <h2>Apply for Position</h2>
    <p class="subtitle">Submitting application for <strong><?= htmlspecialchars($job['title']) ?></strong> at <strong><?= htmlspecialchars($job['company_name']) ?></strong></p>

    <?php if (hasFlash('error')): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars(getFlash('error')) ?>
        </div>
    <?php endif; ?>

    <form action="/job-portal/public/apply.php?job_id=<?= $job['id'] ?>" method="POST" enctype="multipart/form-data" class="form-group">
        <label for="cover_letter">Cover Letter / Note (Optional)</label>
        <textarea name="cover_letter" id="cover_letter" rows="5" placeholder="Highlight relevant skills or experience for this role..."></textarea>

        <label for="resume">Upload Resume (PDF, DOC, DOCX - Max 5MB)</label>
        <input type="file" name="resume" id="resume" accept=".pdf,.doc,.docx" required>

        <button type="submit" class="btn-primary">Submit Application</button>
        <a href="/job-portal/public/job-detail.php?id=<?= $job['id'] ?>" class="btn-secondary" style="margin-left: 10px;">Back to Job</a>
    </form>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>