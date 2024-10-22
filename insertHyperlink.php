<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload Guzzle and Google Cloud libraries
require 'vendor/autoload.php'; // Ensure you have installed Guzzle and Google Cloud PHP libraries using Composer

use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Core\Timestamp; // Import the Timestamp class

// Create a Firestore client
$firestore = new FirestoreClient([
    'projectId' => 'shkolo-task-ea556', // Replace with your project ID
]);

// Get the input data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is received
if (!$data) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No data received']);
    exit;
}

// Extract variables from the input data
$title = $data['title'] ?? null;
$url = $data['url'] ?? null;
$color = $data['color'] ?? null;
$position = $data['position'] ?? null;

// Validate the input data
if (!$title || !$url || !$color || $position === null) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

// Reference the Firestore collection
$collectionRef = $firestore->collection('hyperlinks');

try {
    // Add a new document to the collection
    $docRef = $collectionRef->add([
        'title' => $title,
        'url' => $url,
        'color' => $color,
        'position' => $position,
        'created_on' => new Timestamp(new DateTime()), // Use DateTime for the current time
        'updated_on' => new Timestamp(new DateTime()), // Use DateTime for the current time
        'deleted_on' => null,
    ]);

    // Return the document ID in JSON format
    header('Content-Type: application/json');
    echo json_encode(['id' => $docRef->id()]);
} catch (Exception $e) {
    // Return an error message if insertion fails
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>