<?php
require __DIR__ . '/vendor/autoload.php'; // Path to Composer's autoload

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Google\Cloud\Firestore\FirestoreClient;

class FirebaseService {
    private $firestore;
    private $collectionRef;

    public function __construct() {
        // Firebase credentials JSON file, downloaded from Firebase Console
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'C:\Users\USER\Documents\GitHub\Shkolo-Assignment-PHP\privateKey.json'); 
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://your-database-url.firebaseio.com') // Optional for Firestore
            ->create();

        $this->firestore = $firebase->createFirestore();
        $this->collectionRef = $this->firestore->database()->collection('hyperlinks');
    }

    // Insert new hyperlink document
    public function insertHyperlink($data) {
        $data['created_on'] = new \DateTime();
        $data['updated_on'] = new \DateTime();
        $data['deleted_on'] = null;

        try {
            $docRef = $this->collectionRef->add($data);
            return $docRef->id();
        } catch (\Exception $e) {
            echo 'Error adding document: ' . $e->getMessage();
            return null;
        }
    }

    // Update existing hyperlink document by ID
    public function updateHyperlink($docId, $updatedData) {
        unset($updatedData['created_on']); // Prevent updating created_on
        $updatedData['updated_on'] = new \DateTime();

        try {
            $docRef = $this->collectionRef->document($docId);
            $docRef->set($updatedData, ['merge' => true]); // Merge updates
            echo 'Document successfully updated!';
        } catch (\Exception $e) {
            echo 'Error updating document: ' . $e->getMessage();
        }
    }

    // Retrieve all hyperlinks
    public function getAllHyperlinks() {
        $query = $this->collectionRef->where('deleted_on', '=', null);
        $documents = $query->documents();

        $hyperlinks = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $hyperlinks[] = array_merge(['id' => $document->id()], $document->data());
            }
        }
        return $hyperlinks;
    }

    // Retrieve a specific hyperlink by ID
    public function getHyperlink($docId) {
        $docRef = $this->collectionRef->document($docId);
        $snapshot = $docRef->snapshot();

        if ($snapshot->exists()) {
            return array_merge(['id' => $docId], $snapshot->data());
        } else {
            echo 'Document not found!';
            return null;
        }
    }
}

// Usage Example
$firebaseService = new FirebaseService();

// Insert a new hyperlink
$newHyperlink = [
    'title' => 'Example Title',
    'url' => 'https://example.com',
    'color' => 'blue',
    'position' => 1
];
$firebaseService->insertHyperlink($newHyperlink);

// Update an existing hyperlink
$updateData = [
    'title' => 'Updated Title',
    'url' => 'https://updated-example.com'
];
$firebaseService->updateHyperlink('document_id_here', $updateData);

// Get all hyperlinks
$hyperlinks = $firebaseService->getAllHyperlinks();
print_r($hyperlinks);

// Get a specific hyperlink by ID
$hyperlink = $firebaseService->getHyperlink('document_id_here');
print_r($hyperlink);
?>
