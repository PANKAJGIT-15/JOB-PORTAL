<?php

require_once dirname(__DIR__) . '/models/Job.php';
require_once dirname(__DIR__) . '/helpers/session.php';

class JobController {
    private Job $jobModel;

    public function __construct() {
        $this->jobModel = new Job();
    }

    public function handleCreate(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $user = currentUser();
        if (!$user || !in_array($user['role'], ['employer', 'admin'])) {
            setFlash('error', 'Unauthorized action.');
            header('Location: /job-portal/public/login.php');
            exit;
        }

        $title        = trim($_POST['title'] ?? '');
        $companyName  = trim($_POST['company_name'] ?? '');
        $categoryId   = (int)($_POST['category_id'] ?? 0);
        $location     = trim($_POST['location'] ?? '');
        $jobType      = $_POST['job_type'] ?? 'Full-time';
        $salaryRange  = trim($_POST['salary_range'] ?? '');
        $description  = trim($_POST['description'] ?? '');
        $requirements = trim($_POST['requirements'] ?? '');

        if (empty($title) || empty($companyName) || empty($categoryId) || empty($location) || empty($description)) {
            setFlash('error', 'Please fill in all mandatory job fields.');
            header('Location: /job-portal/public/post-job.php');
            exit;
        }

        $validJobTypes = ['Full-time', 'Part-time', 'Contract', 'Remote'];
        if (!in_array($jobType, $validJobTypes)) {
            $jobType = 'Full-time';
        }

        $jobId = $this->jobModel->create([
            'employer_id'  => $user['id'],
            'category_id'  => $categoryId,
            'title'        => $title,
            'company_name' => $companyName,
            'location'     => $location,
            'job_type'     => $jobType,
            'salary_range' => $salaryRange,
            'description'  => $description,
            'requirements' => $requirements
        ]);

        if ($jobId) {
            setFlash('success', 'Job vacancy successfully posted!');
            header('Location: /job-portal/public/');
            exit;
        }

        setFlash('error', 'Unable to create job listing. Please try again.');
        header('Location: /job-portal/public/post-job.php');
        exit;
    }
}