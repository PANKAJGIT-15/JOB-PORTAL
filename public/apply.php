<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . '/config/database.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'job_seeker') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobId   = (int)($_POST['job_id'] ?? 0);
    $userId  = (int)$_SESSION['user_id'];
    $notes   = trim($_POST['cover_note'] ?? '');

    if ($jobId <= 0) {
        header("Location: jobs.php");
        exit;
    }

    $db = Database::connect();

    // Check if already applied
    $check = $db->prepare("SELECT id FROM applications WHERE job_id = :jid AND user_id = :uid");
    $check->execute([':jid' => $jobId, ':uid' => $userId]);
    if ($check->fetch()) {
        header("Location: job-detail.php?id={$jobId}&status=already_applied");
        exit;
    }

    // Insert Application
    $stmt = $db->prepare("INSERT INTO applications (job_id, user_id, cover_letter, status, applied_at) VALUES (:jid, :uid, :cl, 'pending', NOW())");
    $stmt->execute([
        ':jid' => $jobId,
        ':uid' => $userId,
        ':cl'  => $notes
    ]);

    header("Location: job-detail.php?id={$jobId}&status=success");
    exit;
}