<?php include('../includes/db.php'); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $noms = $_POST['nom'];
    $descriptions = $_POST['description'];
    $telephones = $_POST['telephone'];
    $instagrams = $_POST['instagram'];
    $tiktoks = $_POST['tiktok'];

    for ($i = 0; $i < count($noms); $i++) {
        $nom = trim($noms[$i]);
        $description = trim($descriptions[$i]);
        $telephone = trim($telephones[$i]);
        $instagram = trim($instagrams[$i]);
        $tiktok = trim($tiktoks[$i]);

        // Traitement des images - max 5 images
        $fichiers = $_FILES["images$i"];
        $max_images = 5;
        $filtrées = [];

        if (isset($fichiers['name']) && is_array($fichiers['name'])) {
            for ($j = 0; $j < count($fichiers['name']); $j++) {
                if (!empty($fichiers['name'][$j]) && $fichiers['error'][$j] === 0) {
                    $filtrées[] = [
                        'tmp_name' => $fichiers['tmp_name'][$j],
                        'name' => $fichiers['name'][$j]
                    ];
                }
                if (count($filtrées) >= $max_images) break;
            }
        }

        // Insertion du vendeur
        $stmt = $conn->prepare("INSERT INTO vendeurs (nom, description, telephone, instagram, tiktok) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nom, $description, $telephone, $instagram, $tiktok);

        if ($stmt->execute()) {
            $vendeur_id = $conn->insert_id;
            $ajoutees = 0;
            $upload_dir = "../images/";

            foreach ($filtrées as $file) {
                $tmp_name = $file['tmp_name'];
                $original_name = basename($file['name']);
                $filename = time() . '' . preg_replace('/[^a-zA-Z0-9.-]/', '', $original_name);
                $destination = $upload_dir . $filename;

                if (move_uploaded_file($tmp_name, $destination)) {
                    $stmt_img = $conn->prepare("INSERT INTO vendeur_images (vendeur_id, image) VALUES (?, ?)");
                    $stmt_img->bind_param("is", $vendeur_id, $filename);
                    $stmt_img->execute();
                    $ajoutees++;
                }
            }

            if ($ajoutees > 0) {
                echo "<p style='color:green;'>✅ Vendeur " . ($i + 1) . " ajouté avec $ajoutees image(s).</p>";
            } else {
                echo "<p style='color:orange;'>⚠ Vendeur " . ($i + 1) . " ajouté, mais aucune image n’a été enregistrée.</p>";
            }
        } else {
            echo "<p style='color:red;'>❌ Erreur ajout vendeur " . ($i + 1) . " : " . $stmt->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter des vendeurs</title>
</head>
<body>
    <h2>Formulaire d'ajout de 4 vendeurs (admin seulement)</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <?php for ($i = 0; $i < 4; $i++) { ?>
            <fieldset style="border:1px solid #ccc; padding:20px; margin-bottom:30px;">
                <legend>Vendeur <?php echo $i + 1; ?></legend>
                <input type="text" name="nom[]" placeholder="Nom du vendeur" required><br><br>
                <textarea name="description[]" placeholder="Description" required></textarea><br><br>

                <label>Choisissez jusqu'à 5 images :</label><br>
                <input type="file" name="images<?php echo $i; ?>[]" accept="image/*" multiple required onchange="limitImages(this)"><br><br>

                <input type="text" name="telephone[]" placeholder="Téléphone (+243...)" required><br><br>
                <input type="url" name="instagram[]" placeholder="Lien Instagram"><br><br>
                <input type="url" name="tiktok[]" placeholder="Lien TikTok"><br><br>
            </fieldset>
        <?php } ?>
        <button type="submit" name="ajouter">Ajouter les 4 vendeurs</button>
    </form>

    <!-- Optionnel : vérification JS -->
    <script>
    function limitImages(input) {
        if (input.files.length > 5) {
            alert("Vous ne pouvez sélectionner que 5 images maximum.");
            input.value = ""; // Réinitialise la sélection
        }
    }
    </script>
</body>
</html>


