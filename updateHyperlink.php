<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Google\Cloud\Firestore\FirestoreClient;

$firestore = new FirestoreClient([
    'projectId' => 'shkolo-task-ea556',
]);

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['docId']) || !isset($data['updatedData'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$docId = $data['docId'];
$updatedData = $data['updatedData'];

// Log incoming data for debugging
file_put_contents('php://stderr', print_r($data, true));

$collectionRef = $firestore->collection('hyperlinks');

try {
    // Update the document in Firestore, merging with existing data
    $collectionRef->document($docId)->set($updatedData, ['merge' => true]);

    // Send a success response
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>