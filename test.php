<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php'; // This should point to the vendor folder in the same directory

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

try {
    $serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/path/to/your/firebase_credentials.json'); // Adjust the path
    $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
    
    echo json_encode(['message' => 'Connected to Firestore successfully!']);
} catch (Exception $e) {
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
}
?>
