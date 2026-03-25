<?php

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->userModel = new User();
    }

    public function showRegister() {
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /~2020584/public/index.php?url=register');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        $errors = [];

        if ($username === '') {
            $errors[] = 'Username is required.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        }

        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters long.';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }

        if ($this->userModel->findByEmail($email)) {
            $errors[] = 'Email is already registered.';
        }

        if ($this->userModel->findByUsername($username)) {
            $errors[] = 'Username is already taken.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = [
                'username' => $username,
                'email' => $email
            ];

            header('Location: /~2020584/public/index.php?url=register');
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $success = $this->userModel->create($username, $email, $hashedPassword);

        if ($success) {
            $_SESSION['success'] = 'Account created successfully. Please log in.';
            header('Location: /~2020584/public/index.php?url=login');
            exit;
        } else {
            $_SESSION['errors'] = ['Something went wrong. Please try again.'];
            header('Location: /~2020584/public/index.php?url=register');
            exit;
        }
    }

    public function showLogin() {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /~2020584/public/index.php?url=login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $errors = [];

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        }

        if ($password === '') {
            $errors[] = 'Password is required.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /~2020584/public/index.php?url=login');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header('Location: /~2020584/public/index.php?url=tasks');
            exit;
        } else {
            $_SESSION['errors'] = ['Invalid email or password.'];
            header('Location: /~2020584/public/index.php?url=login');
            exit;
        }
    }

    public function logout() {
        session_unset();
        session_destroy();

        header('Location: /~2020584/public/index.php?url=login');
        exit;
    }
}