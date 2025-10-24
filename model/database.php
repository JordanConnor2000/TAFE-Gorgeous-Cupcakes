<?php
//database connection details
$host = 'gorgeous-cupcakes-db.cvdmxjecrfpy.us-east-1.rds.amazonaws.com';
$user = 'admin';
$password = 'deKdPn2JPxZfjd8h41Ln';
$database = 'gorgeous_cupcakes';

//connect to database with a try/catch statement
try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
} catch(PDOException $e) {
    $error_message = $e->getMessage();
    include('../view/database_error.php');
    exit();
}
?>
