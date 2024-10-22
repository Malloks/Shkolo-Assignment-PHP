<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php'; // Make sure this path is correct

use Kreait\Firebase\Factory;

try {
    // Initialize the Firebase Factory with the service account JSON
    $factory = (new Factory)
        ->withServiceAccount(__DIR__.'/shkolo-task-ea556-firebase-adminsdk-7be8x-57bb9ccfda.json')
        ->withDatabaseUri('https://your-database-url.firebasedatabase.app'); // Replace with your actual Realtime Database URL if needed

    // Initialize Firestore (not Realtime Database)
    $firestore = $factory->createFirestore();
    $database = $firestore->database();

    // Fetch the 'hyperlinks' collection
    $collection = $database->collection('hyperlinks');
    $documents = $collection->where('deleted_on', '=', null)->documents();

    $hyperlinks = [];

    // Loop through the documents and collect data
    foreach ($documents as $document) {
        if ($document->exists()) {
            $data = $document->data();
            $data['id'] = $document->id(); // Add the document ID to the data
            $hyperlinks[] = $data;
        }
    }

    // Return the hyperlinks as JSON
    header('Content-Type: application/json');
    echo json_encode($hyperlinks);

} catch (Exception $e) {
    // Return the error message as JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error fetching hyperlinks: ' . $e->getMessage()]);
}
?>