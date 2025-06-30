<?php include('../includes/db.php'); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un vendeur</title>
</head>
<body>
    <h2>Formulaire d'ajout de vendeur (admin seulement)</h2>
    <form action="ajouter_vendeur.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="nom" placeholder="Nom du vendeur" required><br>
    <textarea name="description" placeholder="Description" required></textarea><br>
    <input type="file" name="image" accept="image/*" required><br>
    <button type="submit" name="ajouter">Ajouter</button>
    </form>

<?php
if (isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    
    //description de l'image 
    $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $destination = '../images/' . $imageName;

        if (move_uploaded_file($imageTmp, $destination)) {
            $sql = "INSERT INTO vendeurs (nom, description, image) VALUES ('$nom', '$description', '$imageName')";
            if ($conn->query($sql) === TRUE) {
                echo "Vendeur ajouté avec succès !";
            } else {
                echo "Erreur : " . $conn->error;
            }
        } else {
            echo "Échec du téléchargement de l’image.";
        }
    }
    ?>
</body>
</html>







