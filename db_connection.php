<?php
$host = 'your_host'; // e.g., 'localhost'
$db = 'your_database'; // e.g., 'my_database'
$user = 'your_username'; // e.g., 'root'
$pass = 'your_password'; // e.g., 'password'

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>
