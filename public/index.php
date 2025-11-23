<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/helpers.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

// Make current user available in templates
$twig->addGlobal('auth', [
    'user' => $_SESSION['user'] ?? null
]);

$action = $_GET['p'] ?? 'books/list';

// Very simple routing
$parts = explode('/', $action);
$controller = $parts[0] ?? 'books';
$method = $parts[1] ?? 'list';
$params = array_slice($parts, 2);

switch ($controller) {
    case 'auth':
        require __DIR__ . '/../src/Controllers/AuthController.php';
        $c = new \App\Controllers\AuthController($twig);
        break;
    case 'ajax':
        require __DIR__ . '/../src/Controllers/AjaxController.php';
        $c = new \App\Controllers\AjaxController($twig);
        break;
    case 'books':
    default:
        require __DIR__ . '/../src/Controllers/BookController.php';
        $c = new \App\Controllers\BookController($twig);
        break;
}

call_user_func_array([$c, $method], $params);
