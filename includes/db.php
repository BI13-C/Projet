<?php
$servername = "localhost"; // ou l'adresse de votre serveur MySQL
$username = "root";        // votre nom d'utilisateur MySQL (souvent 'root' en local)
$password = "";            // votre mot de passe MySQL (souvent vide en local XAMPP)
$dbname = "utilisateur";   // le nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
?>
