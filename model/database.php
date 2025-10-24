<?php
require '/var/www/html/vendor/autoload.php';

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;

$secretName = 'GorgeousCupcakesDatabaseCredentials';
$region = 'us-east-1';

$client = new SecretsManagerClient([
    'version' => 'latest',
    'region' => $region
]);

try {
    // Retrieve secret from AWS Secrets Manager
    $result = $client->getSecretValue([
        'SecretId' => $secretName,
    ]);

    if (isset($result['SecretString'])) {
        $secret = json_decode($result['SecretString'], true);
    } else {
        $secret = json_decode(base64_decode($result['SecretBinary']), true);
    }

    // Extract credentials
    $host = $secret['host'];
    $user = $secret['username'];
    $password = $secret['password'];
    $database = $secret['dbname'];

    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (AwsException $e) {
    echo "<h3>Secrets Manager Error</h3><p>" . $e->getAwsErrorMessage() . "</p>";
    exit();
} catch (PDOException $e) {
    $error_message = $e->getMessage();
    include('../view/database_error.php');
    exit();
}
?>

