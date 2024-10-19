<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file here
require_once 'db_connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(['error' => 'No ID provided']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM hyperlinks WHERE id = ? AND deleted_on IS NULL");
    $stmt->execute([$id]);
    $hyperlink = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($hyperlink) {
        echo json_encode($hyperlink);
    } else {
        echo json_encode(['error' => 'Hyperlink not found']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching hyperlink: ' . $e->getMessage()]);
}
?>
