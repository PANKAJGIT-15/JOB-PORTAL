<?php

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/helpers/session.php';

if (!isLoggedIn()) {
    setFlash('error', 'Please log in to access your applications dashboard.');
    header('Location: /job-portal/public/login.php');
    exit;
}

require_once dirname(__DIR__) . '/views/dashboard/applications.php';