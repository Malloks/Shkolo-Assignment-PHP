<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php'; // Adjust the path if necessary

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

// Path to your Firebase service account JSON file
$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/path/to/your/firebase_credentials.json'); // Adjust path

$firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->create();

$firestore = $firebase->createFirestore();
$database = $firestore->database();

try {
    $collection = $database->collection('hyperlinks');
    $documents = $collection->where('deleted_on', '=', null)->documents();

    $hyperlinks = [];

    foreach ($documents as $document) {
        if ($document->exists()) {
            $hyperlinks[] = $document->data();
        }
    }

    // Return hyperlinks as JSON
    header('Content-Type: application/json'); // Set header to return JSON
    echo json_encode($hyperlinks);
} catch (Exception $e) {
    // If there's an error, return the error message
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error fetching hyperlinks: ' . $e->getMessage()]);
}