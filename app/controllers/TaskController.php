<?php

require_once __DIR__ . '/../models/Task.php';

class TaskController {
    private $taskModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /~2020584/public/index.php?url=login');
            exit;
        }

        $this->taskModel = new Task();
    }

    public function index() {
        $tasks = $this->taskModel->getAllByUser($_SESSION['user_id']);
        require_once __DIR__ . '/../views/tasks/index.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');

            if ($title !== '') {
                $this->taskModel->create($_SESSION['user_id'], $title);
            }
        }

        header('Location: /~2020584/public/index.php?url=tasks');
        exit;
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskId = $_POST['task_id'] ?? null;

            if ($taskId) {
                $this->taskModel->delete($taskId, $_SESSION['user_id']);
            }
        }

        header('Location: /~2020584/public/index.php?url=tasks');
        exit;
    }

    public function toggleComplete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskId = $_POST['task_id'] ?? null;

            if ($taskId) {
                $this->taskModel->toggleComplete($taskId, $_SESSION['user_id']);
            }
        }

        header('Location: /~2020584/public/index.php?url=tasks');
        exit;
    }

    public function ajaxStore() {
        header('Content-Type: application/json');

        $title = trim($_POST['title'] ?? '');

        if ($title === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Task title is required.'
            ]);
            exit;
        }

        $success = $this->taskModel->create($_SESSION['user_id'], $title);
        $tasks = $this->taskModel->getAllByUser($_SESSION['user_id']);

        echo json_encode([
            'success' => $success,
            'tasks' => $tasks
        ]);
        exit;
    }

    public function ajaxDelete() {
        header('Content-Type: application/json');

        $taskId = $_POST['task_id'] ?? null;

        if (!$taskId) {
            echo json_encode([
                'success' => false,
                'message' => 'Task ID is missing.'
            ]);
            exit;
        }

        $success = $this->taskModel->delete($taskId, $_SESSION['user_id']);
        $tasks = $this->taskModel->getAllByUser($_SESSION['user_id']);

        echo json_encode([
            'success' => $success,
            'tasks' => $tasks
        ]);
        exit;
    }

    public function ajaxToggleComplete() {
        header('Content-Type: application/json');

        $taskId = $_POST['task_id'] ?? null;

        if (!$taskId) {
            echo json_encode([
                'success' => false,
                'message' => 'Task ID is missing.'
            ]);
            exit;
        }

        $success = $this->taskModel->toggleComplete($taskId, $_SESSION['user_id']);
        $tasks = $this->taskModel->getAllByUser($_SESSION['user_id']);

        echo json_encode([
            'success' => $success,
            'tasks' => $tasks
        ]);
        exit;
    }
}