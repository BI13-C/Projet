<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des champs
    $accountType = trim($_POST['accountType'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $entite = trim($_POST['entite'] ?? '');

    // Vérification des champs obligatoires
    if (empty($accountType) || empty($username) || ($accountType === 'vendeur' && empty($entite))) {
        echo "<p style='color:pink;'>❌ Merci de remplir tous les champs obligatoires.</p>";
        exit;
    }

    // Gestion du fichier image
    $uploadPath = null;
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === 0) {
        $profilePicName = uniqid() . '_' . basename($_FILES['profilePic']['name']);
        $profilePicTmp = $_FILES['profilePic']['tmp_name'];
        $uploadDir = "uploads/";
        $uploadPath = $uploadDir . $profilePicName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!move_uploaded_file($profilePicTmp, $uploadPath)) {
            echo "<p style='color:red;'>Erreur : Impossible de sauvegarder la photo.</p>";
            exit;
        }
    }

    // Vérification connexion
    if (!$conn) {
        echo "<p style='color:red;'>Erreur de connexion à la base de données.</p>";
        exit;
    }

    // Insertion dans la base
    $sql = "INSERT INTO user (type_compte, nom_utilisateur, photo_profil, description, entite) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        echo "<p style='color:red;'>Erreur de préparation de la requête : " . mysqli_error($conn) . "</p>";
        exit;
    }

    mysqli_stmt_bind_param($stmt, "sssss", $accountType, $username, $uploadPath, $description, $entite);

    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color:green;'>✅ Profil enregistré avec succès !</p>";
    } else {
        echo "<p style='color:red;'>❌ Erreur : " . mysqli_stmt_error($stmt) . "</p>";
    }

    mysqli_stmt_close($stmt);
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <!-- meta charset : permet d'utiliser les accents, caractères spéciaux -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création d'un Profil</title>
    <link rel="stylesheet" href="Style1.css">
</head>
<body class="body2">

    <h1>Créee ton profil !</h1>

    <form id="profileForm" action="utilisateur.php"  method="POST" enctype="multipart/form-data"> <!-- Cette balise contient un formulaire, c’est-à-dire un ensemble de champs que l’utilisateur peut remplir (texte, email, mot de passe, sélection, etc.) et envoyer. -->
    <label for="accountType">Type de profil :</label> <!-- label : permet de cliquer sur le texte pour activer le champ associé. -->
    <select id="accountType" name="accountType" required> <!-- Select : C’est une liste déroulante (comme un menu), utilisée pour choisir une option parmi plusieurs. -->
    <option value="" disabled selected>Choisissez...</option> <!-- Les "option" sont les choix possibles dans un menu "select" L’attribut value est la valeur qui sera envoyée (en JavaScript ou vers un serveur) quand l’utilisateur choisit cette option. --> -->
    <option value="client">Client</option>
    <option value="vendeur">Vendeur en ligne</option>
    </select>

    <label for="name">Nom du compte :</label>
    <input type="text" id="name" name="username" placeholder="Ex : utilisateur00012" required>

    <label for="profilePic">Photo de profil :</label>
    <input type="file" id="profilePic" name="profilePic" accept="image/*">

    <label for="description">Description :</label>
    <textarea id="description" name="description" placeholder="Décrivez-vous ici..." rows="3"></textarea> <!-- textarea : C’est une zone de texte multiligne, pour écrire un texte plus long (par exemple une biographie, un message, etc.) -->
    <!-- rows : attribut de la balise "textarea" qui définit combien de lignes (en hauteur) on voit dans la zone de texte. -->


    <!-- Seulement le vendeur -->
    <div class="vendeur-only" id="vendeurFields">
    <label for="entite">Nom de l'entité :</label>
    <input type="text" id="entite" name="entite" placeholder="Nom de l'entreprise">
    </div>

    <button type="submit">Valider</button>
</form>

<script src="java.js"></script> <!-- JavaScript : C’est un langage de programmation qui permet d’ajouter des fonctionnalités interactives à une page web. -->


</body>
</html>

