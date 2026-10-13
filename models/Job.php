<?php
require_once dirname(__DIR__) . '/config/database.php';

class Job {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getPaginatedActive($keyword = null, $categoryId = null, $jobType = null, $page = 1, $perPage = 4) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT j.*, c.name AS category_name 
                FROM jobs j 
                LEFT JOIN categories c ON j.category_id = c.id 
                WHERE j.status = 'active'";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (j.title LIKE :kw OR j.company_name LIKE :kw OR j.location LIKE :kw OR j.description LIKE :kw)";
            $params[':kw'] = '%' . $keyword . '%';
        }

        if (!empty($categoryId)) {
            $sql .= " AND j.category_id = :catId";
            $params[':catId'] = (int)$categoryId;
        }

        if (!empty($jobType)) {
            $sql .= " AND j.job_type = :jobType";
            $params[':jobType'] = $jobType;
        }

        $sql .= " ORDER BY j.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countActive($keyword = null, $categoryId = null, $jobType = null) {
        $sql = "SELECT COUNT(*) FROM jobs j WHERE j.status = 'active'";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (j.title LIKE :kw OR j.company_name LIKE :kw OR j.location LIKE :kw OR j.description LIKE :kw)";
            $params[':kw'] = '%' . $keyword . '%';
        }

        if (!empty($categoryId)) {
            $sql .= " AND j.category_id = :catId";
            $params[':catId'] = (int)$categoryId;
        }

        if (!empty($jobType)) {
            $sql .= " AND j.job_type = :jobType";
            $params[':jobType'] = $jobType;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getCategoryCounts() {
        $sql = "SELECT c.id, c.name, COUNT(j.id) as total_jobs
                FROM categories c
                LEFT JOIN jobs j ON j.category_id = c.id AND j.status = 'active'
                GROUP BY c.id, c.name
                ORDER BY c.name ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}