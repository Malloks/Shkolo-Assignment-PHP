<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file here
require_once 'db_connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$docId = $data['docId'] ?? null;
$updatedData = $data['updatedData'] ?? [];

if (!$docId) {
    echo json_encode(['error' => 'No ID provided']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE hyperlinks SET title = ?, url = ?, color = ?, updated_on = NOW() WHERE id = ?");
    $stmt->execute([$updatedData['title'], $updatedData['url'], $updatedData['color'], $docId]);
    
    echo json_encode(['success' => 'Hyperlink updated']);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error updating hyperlink: ' . $e->getMessage()]);
}
?>
