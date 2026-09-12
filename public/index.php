<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define("ROOT_PATH", dirname(__DIR__));
define("VIEW_PATH", ROOT_PATH . "/views");

echo "<h1>Job Portal System Initialized!</h1>";