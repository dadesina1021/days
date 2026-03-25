<?php

require_once __DIR__ . '/../core/Database.php';

class Task {
    private $conn;
    private $table = 'tasks';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAllByUser($userId) {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($userId, $title) {
        $query = "INSERT INTO {$this->table} (user_id, title)
                  VALUES (:user_id, :title)";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':user_id' => $userId,
            ':title' => $title
        ]);
    }

    public function delete($taskId, $userId) {
        $query = "DELETE FROM {$this->table} WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' => $taskId,
            ':user_id' => $userId
        ]);
    }

    public function toggleComplete($taskId, $userId) {
        $query = "UPDATE {$this->table}
                  SET is_completed = NOT is_completed
                  WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' => $taskId,
            ':user_id' => $userId
        ]);
    }
}