<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'eh';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die('Connexion échouée'. $conn->connect_error);  
}
$conn->set_charset("utf8mb4");
?>
