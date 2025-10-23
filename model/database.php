<?php
require '/var/www/html/vendor/autoload.php';

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;

$secretName = 'DatabaseCredentials';
$region = 'us-east-1';

$client = new SecretsManagerClient([
    'version' => 'latest',
    'region' => $region
]);

try {
    $result = $client->getSecretValue([
        'SecretId' => $secretName,
    ]);

    if (isset($result['SecretString'])) {
        $secret = json_decode($result['SecretString'], true);
    } else {
        $secret = json_decode(base64_decode($result['SecretBinary']), true);
    }

    $host = $secret['host'];
    $user = $secret['username'];
    $password = $secret['password'];
    $database = $secret['dbname'];

    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);

} catch (AwsException $e) {
    echo "AWS Secrets Manager error: " . $e->getMessage();
    exit();
} catch (PDOException $e) {
    $error_message = $e->getMessage();
    include('../view/database_error.php');
    exit();
}
?>
