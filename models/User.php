<?php

require_once dirname(__DIR__) . '/config/database.php';

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => strtolower(trim($email))]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, name, email, role, phone, created_at FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function create(array $data): int {
        $sql = "INSERT INTO users (name, email, password, role, phone) 
                VALUES (:name, :email, :password, :role, :phone)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name'     => trim($data['name']),
            'email'    => strtolower(trim($data['email'])),
            'password' => $data['password'],
            'role'     => $data['role'] ?? 'job_seeker',
            'phone'    => $data['phone'] ?? null
        ]);

        return (int) $this->db->lastInsertId();
    }
}