<?php

require_once dirname(__DIR__) . '/models/Application.php';
require_once dirname(__DIR__) . '/helpers/session.php';

class ApplicationController {
    private Application $applicationModel;

    public function __construct() {
        $this->applicationModel = new Application();
    }

    public function handleApply(int $jobId): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $user = currentUser();
        if (!$user || $user['role'] !== 'job_seeker') {
            setFlash('error', 'Only registered candidates can submit applications.');
            header("Location: /job-portal/public/job-detail.php?id={$jobId}");
            exit;
        }

        if ($this->applicationModel->hasAlreadyApplied($jobId, $user['id'])) {
            setFlash('error', 'You have already submitted an application for this position.');
            header("Location: /job-portal/public/job-detail.php?id={$jobId}");
            exit;
        }

        if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
            setFlash('error', 'Please upload a valid resume file.');
            header("Location: /job-portal/public/apply.php?job_id={$jobId}");
            exit;
        }

        $fileTmpPath   = $_FILES['resume']['tmp_name'];
        $fileName      = $_FILES['resume']['name'];
        $fileSize      = $_FILES['resume']['size'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['pdf', 'doc', 'docx'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            setFlash('error', 'Only PDF, DOC, and DOCX files are allowed.');
            header("Location: /job-portal/public/apply.php?job_id={$jobId}");
            exit;
        }

        if ($fileSize > (5 * 1024 * 1024)) {
            setFlash('error', 'File size exceeds maximum permitted limit of 5MB.');
            header("Location: /job-portal/public/apply.php?job_id={$jobId}");
            exit;
        }

        $uploadDirectory = dirname(__DIR__) . '/public/uploads/resumes/';
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        $newFileName = 'resume_' . $user['id'] . '_' . $jobId . '_' . time() . '.' . $fileExtension;
        $destPath    = $uploadDirectory . $newFileName;

        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            setFlash('error', 'Failed to store resume on server. Try again.');
            header("Location: /job-portal/public/apply.php?job_id={$jobId}");
            exit;
        }

        $relativeResumePath = 'uploads/resumes/' . $newFileName;
        $coverLetter = trim($_POST['cover_letter'] ?? '');

        $appId = $this->applicationModel->apply($jobId, $user['id'], $relativeResumePath, $coverLetter);

        if ($appId) {
            setFlash('success', 'Your application has been successfully submitted!');
            header("Location: /job-portal/public/job-detail.php?id={$jobId}");
            exit;
        }

        setFlash('error', 'An error occurred while saving your application.');
        header("Location: /job-portal/public/apply.php?job_id={$jobId}");
        exit;
    }
}
