<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_utilisateur = trim($_POST['nom_utilisateur']);
    $email = trim($_POST['email']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    // Vérifier si le nom d'utilisateur existe déjà
    $stmt_verif = $conn->prepare("SELECT id FROM utilisateurs WHERE nom_utilisateur = ?");
    $stmt_verif->bind_param("s", $nom_utilisateur);
    $stmt_verif->execute();
    $result_verif = $stmt_verif->get_result();

    if ($result_verif->num_rows > 0) {
        echo "❌ Ce nom d'utilisateur est déjà pris. Veuillez en choisir un autre.";
    } else {
        // Vérifier si l'email est déjà utilisé aussi (optionnel mais recommandé)
        $stmt_email = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt_email->bind_param("s", $email);
        $stmt_email->execute();
        $result_email = $stmt_email->get_result();

        if ($result_email->num_rows > 0) {
            echo "❌ Cet email est déjà associé à un compte.";
        } else {
            // Si tout est OK, on insère le nouveau compte
            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom_utilisateur, email, mot_de_passe) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nom_utilisateur, $email, $mot_de_passe);

            if ($stmt->execute()) {
                session_start();
                $_SESSION['nom_utilisateur'] = $nom_utilisateur;
                $_SESSION['message'] = "Inscription réussie : Bonjour $nom_utilisateur !";
                    header("Location: ../page/index.php");
                    exit();
            } else {
                echo "❌ Une erreur s'est produite lors de l'inscription : " . $conn->error;
            }
        }
    }
}
?>