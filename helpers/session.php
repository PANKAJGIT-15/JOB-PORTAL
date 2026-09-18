<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function setFlash(string $type, string $message): void {
    $_SESSION['flash'][$type] = $message;
}

function getFlash(string $type): ?string {
    if (isset($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $msg;
    }
    return null;
}

function hasFlash(string $type): bool {
    return isset($_SESSION['flash'][$type]);
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}