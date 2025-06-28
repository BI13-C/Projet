<?php 
session_start();
require_once("../includes/db.php");


// Récupération de l'ID utilisateur
$id = $_SESSION['user_id'] ?? null;

if (!$id) {
    echo json_encode(["error" => "Utilisateur non connecté."]);
    exit();
}

$stmt = $conn->prepare("SELECT * FROM profil WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($user);
?>

