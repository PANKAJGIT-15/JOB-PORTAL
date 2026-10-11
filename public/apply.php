<?php

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/helpers/session.php';
require_once dirname(__DIR__) . '/models/Job.php';
require_once dirname(__DIR__) . '/controllers/ApplicationController.php';

if (!isLoggedIn()) {
    setFlash('error', 'Please log in to apply for this vacancy.');
    header('Location: /job-portal/public/login.php');
    exit;
}

$currentUser = currentUser();
if ($currentUser['role'] !== 'job_seeker') {
    setFlash('error', 'Only job seekers can apply for positions.');
    header('Location: /job-portal/public/jobs.php');
    exit;
}

$jobId = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;
if ($jobId <= 0) {
    header('Location: /job-portal/public/jobs.php');
    exit;
}

$jobModel = new Job();
$job = $jobModel->findById($jobId);

if (!$job) {
    setFlash('error', 'Selected vacancy does not exist.');
    header('Location: /job-portal/public/jobs.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appController = new ApplicationController();
    $appController->handleApply($jobId);
}

require_once dirname(__DIR__) . '/views/jobs/apply.php';    