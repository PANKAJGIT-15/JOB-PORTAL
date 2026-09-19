<?php

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/helpers/session.php';
require_once dirname(__DIR__) . '/controllers/JobController.php';

if (!isLoggedIn()) {
    setFlash('error', 'Please log in to post a job vacancy.');
    header('Location: /job-portal/public/login.php');
    exit;
}

$currentUser = currentUser();
if ($currentUser['role'] !== 'employer' && $currentUser['role'] !== 'admin') {
    setFlash('error', 'Access restricted. Only employers can create job listings.');
    header('Location: /job-portal/public/');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobController = new JobController();
    $jobController->handleCreate();
}

require_once dirname(__DIR__) . '/views/employer/post-job.php';