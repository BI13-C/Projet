<?php
session_start();
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant']);
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE nom_utilisateur = ? OR email = ?");
    $stmt->bind_param("ss", $identifiant, $identifiant);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $utilisateur = $result->fetch_assoc();

        if (password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            $_SESSION['utilisateur_id'] = $utilisateur['id'];
            $_SESSION['nom_utilisateur'] = $utilisateur['nom_utilisateur'];
            $_SESSION['message'] = "Bienvenue, " . $_SESSION['nom_utilisateur'] . " !";
            header("Location: ../page/index.php"); // ou accueil.php selon ton site
            exit();
            // header("Location: accueil.php"); // Redirige vers l'accueil si tu veux
        } else {
            echo "❌ Mot de passe incorrect.";
        }
    } else {
        echo "❌ Aucun compte trouvé avec cet identifiant.";
    }
}
?>
