<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php'; // Ensure the path is correct

use Kreait\Firebase\Factory;

// Initialize Firebase
$factory = (new Factory)
    ->withServiceAccount(__DIR__.'/shkolo-task-ea556-firebase-adminsdk-7be8x-8a74001d44.json') // Adjust path
    ->withDatabaseUri('https://ur/database/url/firebasedatabase.app'); // Adjust the database URL

$database = $factory->createDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link = $_POST['link']; // Make sure the input name matches the form
    $description = $_POST['description']; // Similarly, make sure this matches your form

    if (!empty($link) && !empty($description)) {
        // Add hyperlink to database
        $database->getReference('hyperlinks')->push([
            'link' => $link,
            'description' => $description,
            'deleted_on' => null // Set to null if not deleted
        ]);

        echo json_encode(['success' => 'Hyperlink added successfully!']);
    } else {
        echo json_encode(['error' => 'Link and description cannot be empty.']);
    }
}
?>
