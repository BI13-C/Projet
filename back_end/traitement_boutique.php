<?php
include("../includes/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $description = isset($_POST['description']) ? trim($_POST['description']) :'';
    $telephone = $_POST['telephone'];
    $instagram = $_POST['instagram'];
    $tiktok = $_POST['tiktok'];
    $categorie_id = $_POST['categorie_id'];

    // Insertion du vendeur avec la bonne colonne
    $stmt = $conn->prepare("INSERT INTO vendeurs (nom, description,  telephone, instagram, tiktok, categorie_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nom,$description, $telephone, $instagram, $tiktok, $categorie_id);
    $stmt->execute();

    $vendeur_id = $conn->insert_id;

    // Enregistrement des images
    $images = $_FILES['images'];
    $upload_dir = "../images/";

    for ($i = 0; $i < count($images['name']) && $i < 5; $i++) {
        if ($images['error'][$i] === 0) {
            $tmp_name = $images['tmp_name'][$i];
            $filename = uniqid() . "_" . basename($images['name'][$i]);
            $destination = $upload_dir . $filename;

            move_uploaded_file($tmp_name, $destination);

            $stmt_img = $conn->prepare("INSERT INTO vendeur_images (vendeur_id, image) VALUES (?, ?)");
            $stmt_img->bind_param("is", $vendeur_id, $filename);
            $stmt_img->execute();
        }
    }

    // Message joliment présenté
    echo "
    <html>
    <head>
        <meta http-equiv='refresh' content='2;url=../public/liste_vendeurs.php'>
        <style>
            body {
                font-family: 'Segoe UI', sans-serif;
                background-color: #fefefe;
                text-align: center;
                padding-top: 100px;
                color: #28a745;
            }
            .box {
                display: inline-block;
                background: #e6ffed;
                border: 1px solid #c3e6cb;
                padding: 30px 50px;
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
            h1 {
                margin-bottom: 10px;
            }
            p {
                font-size: 1.1em;
                color: #555;
            }
        </style>
    </head>
    <body>
        <div class='box'>
            <h1>🎉 Boutique enregistrée avec succès !</h1>
            <p>Vous allez être redirigé vers la liste des vendeurs...</p>
        </div>
    </body>
    </html>
    ";
}
?>