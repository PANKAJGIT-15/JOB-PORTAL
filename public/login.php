<?php

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/helpers/session.php';
require_once dirname(__DIR__) . '/controllers/AuthController.php';

if (isLoggedIn()) {
    header('Location: /job-portal/public/');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $auth->handleLogin();
}

require_once dirname(__DIR__) . '/views/auth/login.php';