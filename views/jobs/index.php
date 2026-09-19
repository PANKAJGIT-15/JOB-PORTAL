<?php
$pageTitle = 'Browse Job Vacancies - Job Portal';
require_once dirname(__DIR__) . '/layouts/header.php';
require_once dirname(__DIR__, 2) . '/models/Job.php';
require_once dirname(__DIR__, 2) . '/models/Category.php';

$jobModel = new Job();
$categoryModel = new Category();

$keyword = $_GET['q'] ?? null;
$categoryId = !empty($_GET['category']) ? (int)$_GET['category'] : null;

$jobs = $jobModel->getAllActive($keyword, $categoryId);
$categories = $categoryModel->all();
?>

<div class="jobs-browser-header">
    <h2>Available Opportunities</h2>
    <p>Find and apply for active positions from verified companies.</p>

    <form action="/job-portal/public/jobs.php" method="GET" class="jobs-filter-form">
        <input type="text" name="q" placeholder="Job title, company, or city..." value="<?= htmlspecialchars($keyword ?? '') ?>">
        <select name="category">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($categoryId === (int)$cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-primary">Filter</button>
        <?php if ($keyword || $categoryId): ?>
            <a href="/job-portal/public/jobs.php" class="btn-reset">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="jobs-container">
    <?php if (empty($jobs)): ?>
        <div class="empty-state">
            <p>No job postings found matching your query criteria.</p>
        </div>
    <?php else: ?>
        <div class="job-card-list">
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <div class="job-card-header">
                        <h3><a href="/job-portal/public/job-detail.php?id=<?= $job['id'] ?>"><?= htmlspecialchars($job['title']) ?></a></h3>
                        <span class="badge badge-type"><?= htmlspecialchars($job['job_type']) ?></span>
                    </div>
                    <p class="company-subtext"><strong><?= htmlspecialchars($job['company_name']) ?></strong> • <?= htmlspecialchars($job['location']) ?></p>
                    <p class="category-tag">Category: <?= htmlspecialchars($job['category_name']) ?></p>
                    <?php if (!empty($job['salary_range'])): ?>
                        <p class="salary-tag">Salary: <?= htmlspecialchars($job['salary_range']) ?></p>
                    <?php endif; ?>
                    <div class="job-card-footer">
                        <span class="posted-date">Posted <?= date('M d, Y', strtotime($job['created_at'])) ?></span>
                        <a href="/job-portal/public/job-detail.php?id=<?= $job['id'] ?>" class="btn-view">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>