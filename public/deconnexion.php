<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: ../page/index.php");
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['continuer'])) {
        $stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->bind_param("i", $utilisateur_id);
        $stmt->execute();

        session_destroy();
        header("Location: ../page/index.php?message=supprime"); // ✅ Redirection correcte
        exit;

    } elseif (isset($_POST['annuler'])) {
        header("Location: ../page/index.php"); // ✅ Redirection correcte
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déconnexion</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #ffe4ec;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .confirmation {
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .confirmation h2 {
            color: #66021f;
        }
        .confirmation form button {
            margin: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-oui {
            background-color: #66021f;
            color: white;
        }
        .btn-non {
            background-color: #ccc;
            color: black;
        }
    </style>
</head>
<body>
    <div class="confirmation">
        <h2>Êtes-vous sûr de vouloir vous déconnecter ?</h2>
        <p>⚠ Cela effacera votre compte de notre base de données.</p>
        <form method="POST">
            <button class="btn-oui" name="continuer">Continuer</button>
            <button class="btn-non" name="annuler">Annuler</button>
        </form>
    </div>
</body>
</html>
