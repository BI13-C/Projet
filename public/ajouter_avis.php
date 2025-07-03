<?php
session_start();
include('../includes/db.php');

// Cas où l'utilisateur n'est pas connecté et tente de soumettre un avis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_SESSION['utilisateur_id'])) {
    echo "
    <html>
    <head>
        <title>Connexion requise</title>
        <style>
            body {
                font-family: 'Segoe UI', sans-serif;
                background: #fff0f5;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .message-box {
                background: white;
                border-radius: 12px;
                padding: 40px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                max-width: 500px;
                text-align: center;
                color: #66021f;
            }
            .message-box h2 {
                margin-bottom: 20px;
                color: #a51c45;
            }
            .message-box a {
                display: inline-block;
                margin: 10px;
                padding: 10px 20px;
                background-color: #66021f;
                color: white;
                border-radius: 6px;
                text-decoration: none;
                font-weight: bold;
            }
            .message-box a:hover {
                background-color: #b02c52;
            }
        </style>
    </head>
    <body>
        <div class='message-box'>
            <h2>Connexion requise</h2>
            <p>Pour pouvoir laisser un avis, veuillez vous connecter à votre compte existant ou créer un nouveau compte.</p>
            <a href='connexion.php'>Se connecter</a>
            <a href='inscription.php'>Créer un compte</a>
        </div>
    </body>
    </html>";
    exit;
}

// Cas normal : utilisateur connecté
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilisateur_id = $_SESSION['utilisateur_id'];
    $vendeur_id = intval($_POST['vendeur_id']);
    $contenu = trim($_POST['avis']);
    $categorie = isset($_POST['categorie']) ? $_POST['categorie'] : '';

    // Vérifier si l'utilisateur existe encore
    $verif = $conn->prepare("SELECT id FROM utilisateurs WHERE id = ?");
    $verif->bind_param("i", $utilisateur_id);
    $verif->execute();
    $verif_result = $verif->get_result();

    if ($verif_result->num_rows === 0) {
        echo "
        <html>
        <head><title>Erreur</title></head>
        <body style='font-family:Arial; background:#fff0f5; text-align:center; padding:50px;'>
            <h2 style='color:#a51c45;'>Erreur</h2>
            <p>Votre compte semble ne plus exister. Veuillez vous reconnecter.</p>
            <a href='../page/connection.html' style='display:inline-block; padding:10px 20px; background:#66021f; color:white; text-decoration:none; border-radius:5px;'>Se connecter</a>
        </body>
        </html>
        ";
        exit;
    }

    // Enregistrer l'avis
    $stmt = $conn->prepare("INSERT INTO avis (utilisateur_id, vendeur_id, contenu) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $utilisateur_id, $vendeur_id, $contenu);
    $stmt->execute();

    // Redirection vers la page d’origine (catégorie)
    $redirect = "../public/liste_vendeurs.php";
    if (!empty($categorie)) {
        $redirect .= "?categorie=" . urlencode($categorie);
    }
    header("Location: $redirect");
    exit;
}

// Préparation des champs cachés pour le formulaire
$vendeur_id = isset($_GET['vendeur_id']) ? intval($_GET['vendeur_id']) : 0;
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laisser un avis</title>
    <meta charset="UTF-8">
    <style>
        body {
            background-color: #fff0f5;
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
            text-align: center;
        }
        h2 {
            color: #66021f;
        }
        textarea {
            width: 80%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            resize: vertical;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #66021f;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #b02c52;
        }
    </style>
</head>
<body>
    <h2>Laisser un avis</h2>
    <form method="POST">
        <input type="hidden" name="vendeur_id" value="<?= $vendeur_id ?>">
        <input type="hidden" name="categorie" value="<?= htmlspecialchars($categorie) ?>">
        <textarea name="avis" placeholder="Votre avis ici..." rows="6" required></textarea><br>
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>