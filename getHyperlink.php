<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php'; // Make sure this path is correct

use Kreait\Firebase\Factory;

try {
    // Initialize the Firebase Factory with the service account JSON and database URI
    $factory = (new Factory)
        ->withServiceAccount(__DIR__.'/shkolo-task-ea556-firebase-adminsdk-7be8x-57bb9ccfda.json')
        ->withDatabaseUri('https://your-database-url.firebasedatabase.app'); // Replace with your actual Realtime Database URL if needed

    // Initialize Firestore (not Realtime Database)
    $firestore = $factory->createFirestore();
    $database = $firestore->database();

    // Fetch the 'hyperlinks' collection
    if (isset($_GET['id'])) {
        $docId = $_GET['id'];
        $document = $database->collection('hyperlinks')->document($docId)->snapshot();

        if ($document->exists()) {
            $data = $document->data();
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Document not found']);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'No document ID provided']);
    }
} catch (Exception $e) {
    // Return the error message as JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error fetching hyperlink: ' . $e->getMessage()]);
}