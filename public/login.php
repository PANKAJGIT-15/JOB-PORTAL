<?php

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/helpers/session.php';

if (isLoggedIn()) {
    header('Location: /job-portal/public/');
    exit;
}

require_once dirname(__DIR__) . '/views/auth/login.php';