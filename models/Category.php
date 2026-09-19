<?php

require_once dirname(__DIR__) . '/config/database.php';

class Category {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function all(): array {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => trim($slug)]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(string $name, ?string $description = null): int {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        
        $sql = "INSERT INTO categories (name, slug, description) VALUES (:name, :slug, :description)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name'        => trim($name),
            'slug'        => $slug,
            'description' => $description ? trim($description) : null
        ]);

        return (int) $this->db->lastInsertId();
    }
}