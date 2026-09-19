<?php
$pageTitle = 'Post a New Job - Job Portal';
require_once dirname(__DIR__) . '/layouts/header.php';
require_once dirname(__DIR__, 2) . '/helpers/session.php';
require_once dirname(__DIR__, 2) . '/models/Category.php';

$categoryModel = new Category();
$categories = $categoryModel->all();
?>

<div class="job-form-card">
    <h2>Post a Job Vacancy</h2>
    <p class="subtitle">Reach qualified candidates by listing your open position.</p>

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

    <form action="/job-portal/public/post-job.php" method="POST" class="form-group">
        <label for="title">Job Title</label>
        <input type="text" name="title" id="title" required placeholder="e.g. Senior Backend Engineer">

        <label for="company_name">Company Name</label>
        <input type="text" name="company_name" id="company_name" required placeholder="e.g. Acme Tech Solutions">

        <label for="category_id">Job Category</label>
        <select name="category_id" id="category_id" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="location">Location</label>
        <input type="text" name="location" id="location" required placeholder="e.g. Mumbai, India or Remote">

        <label for="job_type">Job Type</label>
        <select name="job_type" id="job_type" required>
            <option value="Full-time">Full-time</option>
            <option value="Part-time">Part-time</option>
            <option value="Contract">Contract</option>
            <option value="Remote">Remote</option>
        </select>

        <label for="salary_range">Salary Range (Optional)</label>
        <input type="text" name="salary_range" id="salary_range" placeholder="e.g. ₹8,00,000 - ₹12,00,000 P.A.">

        <label for="description">Job Description</label>
        <textarea name="description" id="description" rows="5" required placeholder="Detailed job overview, responsibilities..."></textarea>

        <label for="requirements">Requirements & Qualifications (Optional)</label>
        <textarea name="requirements" id="requirements" rows="4" placeholder="Skills, years of experience, tools required..."></textarea>

        <button type="submit" class="btn-primary">Publish Job Listing</button>
    </form>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>