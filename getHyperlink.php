<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php'; // Make sure this path is correct

use Kreait\Firebase\Factory;

try {
// Initialize the Firebase Factory with the service account credentials
$factory = (new Factory)
    ->withServiceAccount([
        'type' => 'service_account',
        'project_id' => 'shkolo-task-ea556',
        'private_key_id' => 'ff57902735b8eb6339d7b67dbb327631546227b0',
        'private_key' => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDDKABS2Pt9dH33\nEyoK3sM3mZBZyAkxfnGMq3mwtGDNfBWHvOm4FmtIhVVL6gGPJCKNfjcNICeGC1n2\nk/iYZztgKdlTefGpDxxong0hl4tdXz4uX0Nh4e3jnwiT4tmKU3/JRgtppXTgT5wK\n1Hrck2PwPkahluYkV+8ayidi0uZ61iYWHm0t5aoH5HO+UH7wi8LOFzUCY3qRi3fl\n3x+bKzEbQFAJteTlGguKuw3KOZzltQkWnvYXn3a446CveB3qxcfDXNYQBu5WJ+aE\nUcs+EeunA1ycR9i3vaaBPH9E1icx+9P/EAue0oEqfNUZJrLciKpXkUHkXNghFSVw\nim3+IwL5AgMBAAECggEADtkbBzKjlcS4dcUzQG747uDD6xTNYELkzEVapZ9fsxqi\n/7gXly47fOHKHtO/yR6Ac3/8G8dLwobYEirpcdz+1z7v3kpLoOd5q5sE2D6xt4mw\nzzHnzEl2gkg+DJRopT3bndpysVfJl+xurKNEcsZTBvsSACbdPTwkgMcLLplpuEQz\nLkqPZk3SUmj+4WPzMO1Tez7GAvdHzp2E14GfVqyTksiStCUSsc0dseywUzdzaPyX\nRkNP/XGWNz/SbPlX9aC7YGY3UmN7t8oFifUzSSlqKjhJmHYsP2T6a5lYBIrB6bCA\nHzMFtgxIcvZP7ZCJRS1Y10DIkRFKrPdeyv4b/rU8YQKBgQD0EVllr16svEkUslPI\nn/25xRGJtOe/9Jwt8QshoG6lLfzBms6+tuJhJnKKdG/W6I8t5ldY/wcBSwTco+zY\nDtCE36gquxBgxoMy+7dv3l1ipXKHIs9keUEkBtZcyVmF+wRRVz6kpfTR4iUb5EW9\nQ5Ub44YUTKojbfXDFX2iqxkISQKBgQDMsn7wUhfNuon26380VoJv16G1H0lzsMYx\nRdVfmjGayNEkqMAPoqf5gaS/W5nqugg0wsO3zlbjSDWgSJm93uZ81YfjbfnsVfZK\nkU5Ll/Bu/rFuo10uYMvd84mrbZJzuqlT0nZXJIGau8hibvXWibW6WEmRIAdul8fz\nHoLKLbcFMQKBgGxDkeNAUn/PGZ/bpry086jHGQt7ut10VR8v8F8fOlV9O8RptlH0\n8CBmHKL8GWw8Rt72cUKiBep165cwA+ynTanTSFaEGjTDeglQjUNLYSNT/qShNVv9\nSX8ApXANRO/gtD6cs8X3c8zyQsHHlYPqCaeQWraAd2w157F8w/k3amg5AoGAISbP\ndDB3dnQ9n+XoBhv0qBRuNh1JEhmRH63FruQUIJNhe+RwppebSMd8XRmCxgc3CQR6\nGiyq2ch6FulrLNsKzTDv/x9ymJobd1wtKAUFhZRuPBLtJnO60Ml+hGsMiv2yKsct\nL1PUQbEj9P0vh31qFzHxSDW9vmWKlo9cc04QFCECgYEAqphxZExDCuB2k3puIe0c\ntjnPfECQXQHug0lDFLNASLYfetd4yDG97zOwhV2sSKw4tC5j/c3O2KR+buVHbBSB\njXtgYUOeZ8abVp8T/ePg5QhhxgN2VElG6IwCDFx8wgZc5eEQBx80uqq+GPYX7q/M\nDeb66gStsaZZwd3ixkGrOMY=\n-----END PRIVATE KEY-----\n",
        'client_email' => 'firebase-adminsdk-7be8x@shkolo-task-ea556.iam.gserviceaccount.com',
        'client_id' => '116006331443890069192',
        'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
        'token_uri' => 'https://oauth2.googleapis.com/token',
        'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
        'client_x509_cert_url' => 'https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-7be8x%40shkolo-task-ea556.iam.gserviceaccount.com',
    ])
    ->withDatabaseUri('https://your-database-url.firebasedatabase.app'); // Replace with your actual Firestore database URL

// Initialize Firestore
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