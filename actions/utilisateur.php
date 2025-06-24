<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des champs
    $accountType = trim($_POST['accountType'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $entite = trim($_POST['entite'] ?? '');

    // Vérification des champs obligatoires
    if (empty($accountType) || empty($username) || ($accountType === 'vendeur' && empty($entite))) {
        echo "<p style='color:pink;'>❌ Merci de remplir tous les champs obligatoires.</p>";
        } else {
        // Gestion du fichier image
        $uploadPath = null;
        if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === 0) {
            $profilePicName = uniqid() . '_' . basename($_FILES['profilePic']['name']);
            $profilePicTmp = $_FILES['profilePic']['tmp_name'];
            $uploadDir = "../uploads/";
            $uploadPath = $uploadDir . $profilePicName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (!move_uploaded_file($profilePicTmp, $uploadPath)) {
                echo "<p style='color:red;'>Erreur : Impossible de sauvegarder la photo.</p>";
                $uploadPath = null;
            }
        }

        // Vérification connexion
        if (!$conn) {
            echo "<p style='color:red;'>Erreur de connexion à la base de données.</p>";
        } else {
               // Vérifier si le nom d'utilisateur existe déjà
            $checkSql = "SELECT COUNT(*) FROM user WHERE nom_utilisateur = ?";
            $checkStmt = mysqli_prepare($conn, $checkSql);
            mysqli_stmt_bind_param($checkStmt, "s", $username);
            mysqli_stmt_execute($checkStmt);
            mysqli_stmt_bind_result($checkStmt, $count);
            mysqli_stmt_fetch($checkStmt);
            mysqli_stmt_close($checkStmt);

            if ($count > 0) {
                echo "<p style='color:pink;'>❌ Ce nom d'utilisateur est déjà pris.</p>";
            } else {
            // Insertion dans la base
            $sql = "INSERT INTO user (type_compte, nom_utilisateur, photo_profil, description, entite) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            if (!$stmt) {
                echo "<p style='color:red;'>Erreur de préparation de la requête : " . mysqli_error($conn) . "</p>";
            } else {
                mysqli_stmt_bind_param($stmt, "sssss", $accountType, $username, $uploadPath, $description, $entite);

                if (mysqli_stmt_execute($stmt)) {
                    echo "<p style='color:green;'>✅ Profil enregistré avec succès !</p>";
                } else {
                    echo "<p style='color:red;'>❌ Erreur : " . mysqli_stmt_error($stmt) . "</p>";
                }

                mysqli_stmt_close($stmt);
            }
        }
    }
}
}
?>


