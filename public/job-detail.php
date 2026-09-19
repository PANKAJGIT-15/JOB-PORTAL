<?php

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/helpers/session.php';
require_once dirname(__DIR__) . '/models/Job.php';

$jobId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($jobId <= 0) {
    header('Location: /job-portal/public/jobs.php');
    exit;
}

$jobModel = new Job();
$job = $jobModel->findById($jobId);

if (!$job) {
    setFlash('error', 'Job posting not found or has expired.');
    header('Location: /job-portal/public/jobs.php');
    exit;
}

require_once dirname(__DIR__) . '/views/jobs/detail.php';
