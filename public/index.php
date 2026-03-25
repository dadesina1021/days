<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/TaskController.php';

$url = $_GET['url'] ?? 'login';
$url = trim($url, '/');

$authController = new AuthController();

switch ($url) {
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            $authController->showRegister();
        }
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            $authController->showLogin();
        }
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'tasks':
        $taskController = new TaskController();
        $taskController->index();
        break;

    case 'store-task':
        $taskController = new TaskController();
        $taskController->store();
        break;

    case 'toggle-task':
        $taskController = new TaskController();
        $taskController->toggleComplete();
        break;

    case 'delete-task':
        $taskController = new TaskController();
        $taskController->delete();
        break;

    default:
        echo "Page not found";
        break;
}