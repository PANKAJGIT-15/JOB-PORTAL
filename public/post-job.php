<?php

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/helpers/session.php';

// Check 1: Agar user logged in nahi hai, toh login page par bhej do
if (!isLoggedIn()) {
    setFlash('error', 'Please log in to post a job vacancy.');
    header('Location: /job-portal/public/login.php');
    exit;
}

// Check 2: Agar user candidate/job seeker hai (employer nahi hai), toh home page par redirect kar do
$currentUser = currentUser();
if ($currentUser['role'] !== 'employer' && $currentUser['role'] !== 'admin') {
    setFlash('error', 'Access restricted. Only employers can create job listings.');
    header('Location: /job-portal/public/');
    exit;
}

// Agar dono check pass ho gaye, tabhi Step 2 wala form dikhao
require_once dirname(__DIR__) . '/views/employer/post-job.php';