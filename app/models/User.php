<?php

require_once __DIR__ . '/../core/Database.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function create($username, $email, $password) {
        $query = "INSERT INTO {$this->table} (username, email, password)
                  VALUES (:username, :email, :password)";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password
        ]);
    }

    public function findByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByUsername($username) {
        $query = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':username' => $username]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}