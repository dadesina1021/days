<?php

require_once '../app/core/Database.php';

$db = new Database();
$conn = $db->connect();

if ($conn) {
    echo "Connected successfully to Days database!";
}