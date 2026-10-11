<?php

require_once dirname(__DIR__) . '/config/database.php';

class Application {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function hasAlreadyApplied(int $jobId, int $userId): bool {
        $stmt = $this->db->prepare("SELECT id FROM applications WHERE job_id = :job_id AND user_id = :user_id LIMIT 1");
        $stmt->execute([
            'job_id'  => $jobId,
            'user_id' => $userId
        ]);
        return (bool) $stmt->fetch();
    }

    public function apply(int $jobId, int $userId, string $resumePath, ?string $coverLetter = null): int {
        $sql = "INSERT INTO applications (job_id, user_id, resume_path, cover_letter, status)
                VALUES (:job_id, :user_id, :resume_path, :cover_letter, 'pending')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'job_id'       => $jobId,
            'user_id'      => $userId,
            'resume_path'  => $resumePath,
            'cover_letter' => $coverLetter ? trim($coverLetter) : null
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function getApplicationsByCandidate(int $userId): array {
        $sql = "SELECT a.*, j.title AS job_title, j.company_name, j.location, j.job_type
                FROM applications a
                JOIN jobs j ON a.job_id = j.id
                WHERE a.user_id = :user_id
                ORDER BY a.applied_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getApplicationsForEmployer(int $employerId): array {
        $sql = "SELECT a.*, u.name AS candidate_name, u.email AS candidate_email, j.title AS job_title
                FROM applications a
                JOIN jobs j ON a.job_id = j.id
                JOIN users u ON a.user_id = u.id
                WHERE j.employer_id = :employer_id
                ORDER BY a.applied_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['employer_id' => $employerId]);
        return $stmt->fetchAll();
    }
}