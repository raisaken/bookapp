<?php
namespace App\Controllers;
require_once __DIR__ . '/../models/UserModel.php';

use App\Models\UserModel;

class AuthController {
    private $twig;
    private $userModel;

    public function __construct($twig) {
        $this->twig = $twig;
        $this->userModel = new UserModel();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $token = $_POST['csrf'] ?? '';

            if (!\App\verify_csrf($token)) {
                die('Invalid CSRF token');
            }

            $user = $this->userModel->findByUsername($username);
            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user'] = ['id' => $user['id'], 'username' => $user['username']];
                header('Location: ' . BASE_URL . '?p=books/list');
                exit;
            } else {
                echo $this->twig->render('auth/login.html.twig', ['error' => 'Invalid credentials', 'csrf' => \App\csrf_token()]);
                return;
            }
        }

        echo $this->twig->render('auth/login.html.twig', ['csrf' => \App\csrf_token()]);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $token = $_POST['csrf'] ?? '';

            if (!\App\verify_csrf($token)) {
                die('Invalid CSRF token');
            }

            if ($username === '' || $email === '' || $password === '') {
                echo $this->twig->render('auth/register.html.twig', ['error' => 'All fields required', 'csrf' => \App\csrf_token()]);
                return;
            }

            if ($this->userModel->findByUsername($username)) {
                echo $this->twig->render('auth/register.html.twig', ['error' => 'Username already exists', 'csrf' => \App\csrf_token()]);
                return;
            }

            $this->userModel->create($username, $email, $password);
            header('Location: ' . BASE_URL . '?p=auth/login');
            exit;
        }

        echo $this->twig->render('auth/register.html.twig', ['csrf' => \App\csrf_token()]);
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '?p=auth/login');
        exit;
    }
}
