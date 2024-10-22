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
            'private_key_id' => 'f4dc229a0845d08dd77729dc05079f5314e1629d',
            'private_key' => "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDDp4DKMPP78TTZ\nr+Q4Sz4kzMXWsPwlCGSSchqigTdKDetQ2VnuHHjq7sUOuTXm8CHc5ymsXtsPAw4e\nlRfN74HUhGHqk/ocA2VRndHeqNzkPOj5qNi047X8oZZNNYCsaIwoGA2r4CUGpT5P\nB4YNtOdOSQn5iaSQoT/FNZ63nWOa/n9QZuPX1SOcUBQ4GYk5oCxpYnM9MGEjIS3k\nGoz5jZB6joeihaOe5WWdVLQsx+OGE0VWOFmVEmZpxGofTzWep0ejmAgkhRyqQHR4\nx5lwEaiywcIO+igfyxgfIw7FGp6a3tX1tYNGtHPDACSkrXTFpin6zctQtlFo4Ag7\nfxdCczq1AgMBAAECggEAWNDS3/X066uFKER2Py+42A+0x060R0NWFDyD5rztkkpb\nRepErphyM4OMJLDizSC43oR5IGw22Mu0PyHDGpLcxtIJeOkPomPQPIp92KujCmC0\nCZEiPosX88SObCIbdShIZ3Lz0dAAPtIIROvPaK10Ss9RHdvjvlvUjPtYP1XM/3A4\nQCeuujt9qObFmFAaMLJdYhv8o7Ja80d05d4tr0ZPPoqUGa8E9pGzpA3RUqR3wv6Z\n4uLvHj311GVKAOMETy0uXg648WzsmP/dFqczNJT1nnTmYxP5cQqgpJGaw/sJIjWp\nrTwzle8woPV6954Ha4GGysTi1qtIvzKh97KNXQhqiQKBgQDv9EHiJr1tq+nW8N7+\nGkp8hi9aspP9Gwg3P8wdQubdwM5cs+dLZZ6G5Lf3ivFoqfZ7SNYcyWZI/T436Cvm\ng2jkmJBpL8Nf25W9hQSluquAAfuOHPDPkC0+/rvnEH+0iIz9WHvpjv2Eilm8LdTW\njc4oZaeTZbOEH097eos+LnrKNwKBgQDQvOINQfcZOH0bd+JayiPYkWw1rkvKVN5C\nIY1X1KGVELb41M+cjSLHVbF/YKXXOyBPTBKxQ2kqxqBnV2Xm2Jfq7FoeEYh/IIOY\nasRLq9QuaMes+/JHq06ZMrH0sshW4VU8zbT0jSFSha5yIHo2OLsFlwuQOg5JOvEA\nl4pkhIa8cwKBgQCDkwwNVfDJEltG4Q1liEUPwwR0VzeOsiPvJ6xk3yp4riB59Kv/\nS9mBXikCM83r9kaRk37UihrsKvDS9xwbajM6Je4Jv4cCKQYSxb2mopUnu+6UHopt\nRkGJKWUfboyYEcg9oydPCnk5FCPga8EpZNiGjjc1O68T45dRvgBcOGBcQQKBgD0X\nmx2AOf7SrqMUclZaJSWkYkRPz/TW4/n88e5L2ELVkoe/WUgbcptetEVwlvkoX+t1\ne18QOL6BRb8ZMEThDY1/QVzditKSRJGeZfczwjUXC6HQV3esZqOl17mjfckXyCe0\nmQqeJ6uMXqMeaEZoUixvqb27kCTUsGaY87M5qmmLAoGBAJiYdGdCHZDh57E5EOL7\nt547avuvoB8rxpa242uqJIjh5umKWit8R+8mVRu6ORSzoHh+bxNc/rXnGDg1nzDx\n/Ka6dBcXybDVAYiXIpz9nx+sU5UAtUeoDIdTe5SSXi42lmPaWZpbHbd8jiIgAiih\ngQsRDCCnRJDrsJTQZO8msTy9\n-----END PRIVATE KEY-----\n",
            'client_email' => 'firebase-adminsdk-7be8x@shkolo-task-ea556.iam.gserviceaccount.com',
            'client_id' => '116006331443890069192',
            'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
            'token_uri' => 'https://oauth2.googleapis.com/token',
            'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
            'client_x509_cert_url' => 'https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-7be8x%40shkolo-task-ea556.iam.gserviceaccount.com',
        ])
        ->withDatabaseUri('https://shkolo-task-ea556.firebaseio.com'); // Replace with your actual Firestore database URL

    // Initialize Firestore
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