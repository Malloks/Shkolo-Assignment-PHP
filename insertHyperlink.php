<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file here
require_once 'db_connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$title = $data['title'] ?? null;
$url = $data['url'] ?? null;
$color = $data['color'] ?? null;
$position = $data['position'] ?? null;

if (!$title || !$url || !$color || $position === null) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO hyperlinks (title, url, color, position, created_on, updated_on) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$title, $url, $color, $position]);
    echo json_encode(['success' => 'Hyperlink inserted', 'id' => $pdo->lastInsertId()]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error inserting hyperlink: ' . $e->getMessage()]);
}
?>
