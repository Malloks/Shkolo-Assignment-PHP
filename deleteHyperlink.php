<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Google\Cloud\Firestore\FirestoreClient;

$firestore = new FirestoreClient([
    'projectId' => 'shkolo-task-ea556', // Replace with your project ID
]);

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['error' => 'No data received']);
    exit;
}

$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$collectionRef = $firestore->collection('hyperlinks');
$docRef = $collectionRef->document($id);

try {
    $docRef->set([
        'deleted_on' => new \Google\Cloud\Core\Timestamp() // Set to current timestamp
    ], ['merge' => true]); // Merge to keep other fields intact

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>