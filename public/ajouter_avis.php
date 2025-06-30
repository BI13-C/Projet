<?php include('../includes/db.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Laisser un avis</title>
</head>
<body>
    <h2>Laisser un avis</h2>

    <?php
    // Récupération de l'ID du vendeur depuis l'URL
    $vendeur_id = isset($_GET['vendeur_id']) ? intval($_GET['vendeur_id']) : 0;

    // Vérifie si l'id du vendeur est bien présent
    if ($vendeur_id <= 0) {
        echo "Vendeur non trouvé.";
        exit;
    }

    // Enregistrement de l'avis si le formulaire est soumis
    if (isset($_POST['envoyer'])) {
    $avis = trim($_POST['avis']);

    if (!empty($avis)) {
        $stmt = $conn->prepare("INSERT INTO avis (vendeur_id, contenu) VALUES (?, ?)");
        $stmt->bind_param("is", $vendeur_id, $avis);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Avis envoyé avec succès !</p>";
        } else {
            echo "<p style='color: red;'>Erreur : " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color: red;'>Le champ avis est vide.</p>";
    }
}

$vendeur_id = isset($_GET['vendeur_id']) ? intval($_GET['vendeur_id']) : 0;
if (isset($_POST['vendeur_id'])) {
    $vendeur_id = intval($_POST['vendeur_id']);
}
    ?>

    <form method="POST">
        <input type="hidden" name="vendeur_id" value="<?php echo $vendeur_id; ?>">
        <textarea name="avis" placeholder="Votre avis ici..." rows="5" cols="50" required></textarea><br><br>
        <button type="submit" name="envoyer">Envoyer</button>
    </form>
</body>
</html>
