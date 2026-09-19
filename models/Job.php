<?php

require_once dirname(__DIR__) . '/config/database.php';

class Job {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAllActive(?string $keyword = null, ?int $categoryId = null): array {
        $sql = "SELECT j.*, c.name AS category_name 
                FROM jobs j
                JOIN categories c ON j.category_id = c.id
                WHERE j.status = 'active'";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (j.title LIKE :keyword OR j.company_name LIKE :keyword OR j.location LIKE :keyword)";
            $params['keyword'] = '%' . trim($keyword) . '%';
        }

        if (!empty($categoryId)) {
            $sql .= " AND j.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        $sql .= " ORDER BY j.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $sql = "SELECT j.*, c.name AS category_name, u.name AS employer_name, u.email AS employer_email
                FROM jobs j
                JOIN categories c ON j.category_id = c.id
                JOIN users u ON j.employer_id = u.id
                WHERE j.id = :id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $job = $stmt->fetch();
        return $job ?: null;
    }

    public function create(array $data): int {
        $sql = "INSERT INTO jobs (employer_id, category_id, title, company_name, location, job_type, salary_range, description, requirements)
                VALUES (:employer_id, :category_id, :title, :company_name, :location, :job_type, :salary_range, :description, :requirements)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'employer_id'  => $data['employer_id'],
            'category_id'  => $data['category_id'],
            'title'        => trim($data['title']),
            'company_name' => trim($data['company_name']),
            'location'     => trim($data['location']),
            'job_type'     => $data['job_type'] ?? 'Full-time',
            'salary_range' => trim($data['salary_range'] ?? ''),
            'description'  => trim($data['description']),
            'requirements' => trim($data['requirements'] ?? '')
        ]);

        return (int) $this->db->lastInsertId();
    }
}