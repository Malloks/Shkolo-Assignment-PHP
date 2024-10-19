<?php
require 'vendor/autoload.php'; // Load Composer's autoloader

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

try {
    $factory = (new Factory)
    ->withServiceAccount(__DIR__.'/privateKey.json')
    ->withDatabaseUri('https://ur/database/url/firebasedatabase.app');

$database = $factory->createDatabase();

    // Example: Fetch all hyperlinks from Firestore
    $collection = $firestore->collection('hyperlinks');
    $documents = $collection->documents();

    foreach ($documents as $document) {
        if ($document->exists()) {
            echo $document->id() . ': ' . json_encode($document->data()) . '<br>';
        } else {
            echo 'Document ' . $document->id() . ' does not exist.<br>';
        }
    }
} catch (Exception $e) {
    // Handle exceptions
    echo 'Error: ' . $e->getMessage();
}
?>