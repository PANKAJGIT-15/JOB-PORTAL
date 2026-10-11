<?php
$pageTitle = 'Applications Dashboard - Job Portal';
require_once dirname(__DIR__) . '/layouts/header.php';
require_once dirname(__DIR__, 2) . '/helpers/session.php';
require_once dirname(__DIR__, 2) . '/models/Application.php';

$user = currentUser();
$appModel = new Application();

$isEmployer = ($user['role'] === 'employer' || $user['role'] === 'admin');
$applications = $isEmployer 
    ? $appModel->getApplicationsForEmployer($user['id']) 
    : $appModel->getApplicationsByCandidate($user['id']);
?>

<div class="dashboard-header">
    <h2><?= $isEmployer ? 'Received Candidate Applications' : 'My Job Applications' ?></h2>
    <p><?= $isEmployer ? 'Review applicants who applied for your job postings.' : 'Track status and details of your submitted applications.' ?></p>
</div>

<?php if (empty($applications)): ?>
    <div class="empty-state">
        <p><?= $isEmployer ? 'No candidates have applied to your active job listings yet.' : 'You have not submitted any applications yet.' ?></p>
        <?php if (!$isEmployer): ?>
            <a href="/job-portal/public/jobs.php" class="btn-primary" style="display:inline-block; margin-top:1rem;">Browse Jobs</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <?php if ($isEmployer): ?>
                        <th>Candidate</th>
                        <th>Email</th>
                    <?php else: ?>
                        <th>Company</th>
                        <th>Location</th>
                    <?php endif; ?>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Resume</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($app['job_title']) ?></strong></td>
                        <?php if ($isEmployer): ?>
                            <td><?= htmlspecialchars($app['candidate_name']) ?></td>
                            <td><?= htmlspecialchars($app['candidate_email']) ?></td>
                        <?php else: ?>
                            <td><?= htmlspecialchars($app['company_name']) ?></td>
                            <td><?= htmlspecialchars($app['location']) ?></td>
                        <?php endif; ?>
                        <td>
                            <span class="badge badge-status-<?= htmlspecialchars($app['status']) ?>">
                                <?= ucfirst(htmlspecialchars($app['status'])) ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y', strtotime($app['applied_at'])) ?></td>
                        <td>
                            <a href="/job-portal/public/<?= htmlspecialchars($app['resume_path']) ?>" target="_blank" class="btn-view">Download</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>