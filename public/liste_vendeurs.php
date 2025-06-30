<?php include('../includes/db.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des vendeurs certifiés</title>
</head>
<body>
    <h2> Trouvez votre bonheur !</h2> 
    <?php
    
$result = $conn->query("SELECT * FROM vendeurs");

while ($row = $result->fetch_assoc()) {
    echo "<div class='card'>";
    echo "<img src='../images/" . htmlspecialchars($row['image']) . "' alt='Image vendeur'>";
    echo "<h3>" . htmlspecialchars($row['nom']) . " <span style='color:green;'>(Certifié ✅)</span></h3>";
    echo "<p>" . nl2br(htmlspecialchars($row['description'])) . "</p>";

    // === Bloc avis ===
    $vendeur_id = $row['id'];
    $avis_result = $conn->query("SELECT contenu, date_avis FROM avis WHERE vendeur_id = $vendeur_id ORDER BY date_avis DESC");

    echo "<div style='height:100px; overflow-y:scroll; background-color:#f0f0f0; border:1px solid #ccc; padding:10px; border-radius:5px; margin-top:10px;'>";
    if ($avis_result->num_rows > 0) {
        echo "<strong>Avis :</strong><br>";
        while ($avis = $avis_result->fetch_assoc()) {
            echo "<p style='margin: 8px 0; font-size: 14px;'>• " . htmlspecialchars($avis['contenu']) . "</p>";
        }
    } else {
        echo "<em>Aucun avis pour l’instant.</em>";
    }
    echo "</div>";

    // === Bouton laisser un avis ===
    echo "<a class='btn' href='ajouter_avis.php?vendeur_id=" . htmlspecialchars($row['id']) . "'>Laisser un avis</a>";
    echo "</div>";
}




    ?>
</body>
</html>
