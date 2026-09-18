<?php

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/helpers/session.php';
require_once dirname(__DIR__) . '/controllers/AuthController.php';

$auth = new AuthController();
$auth->handleLogout();