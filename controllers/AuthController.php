<?php

require_once dirname(__DIR__) . '/models/User.php';
require_once dirname(__DIR__) . '/helpers/session.php';

class AuthController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function handleRegister(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role     = $_POST['role'] ?? 'job_seeker';
        $phone    = trim($_POST['phone'] ?? '');

        if (empty($name) || empty($email) || empty($password)) {
            setFlash('error', 'All required fields must be filled.');
            header('Location: /job-portal/public/register.php');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlash('error', 'Invalid email address format.');
            header('Location: /job-portal/public/register.php');
            exit;
        }

        if ($this->userModel->findByEmail($email)) {
            setFlash('error', 'An account with this email already exists.');
            header('Location: /job-portal/public/register.php');
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $userId = $this->userModel->create([
            'name'     => $name,
            'email'    => $email,
            'password' => $hashedPassword,
            'role'     => in_array($role, ['job_seeker', 'employer']) ? $role : 'job_seeker',
            'phone'    => $phone ?: null
        ]);

        if ($userId) {
            setFlash('success', 'Registration successful! Please login.');
            header('Location: /job-portal/public/login.php');
            exit;
        }

        setFlash('error', 'Something went wrong. Please try again.');
        header('Location: /job-portal/public/register.php');
        exit;
    }

    public function handleLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            setFlash('error', 'Email and password are required.');
            header('Location: /job-portal/public/login.php');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            setFlash('error', 'Invalid credentials provided.');
            header('Location: /job-portal/public/login.php');
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role']
        ];

        setFlash('success', 'Welcome back, ' . htmlspecialchars($user['name']) . '!');
        header('Location: /job-portal/public/');
        exit;
    }

    public function handleLogout(): void {
        unset($_SESSION['user_id'], $_SESSION['user']);
        session_destroy();
        header('Location: /job-portal/public/login.php');
        exit;
    }
}
