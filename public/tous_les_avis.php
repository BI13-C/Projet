<?php include('../includes/db.php'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tous les avis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff1f7;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin: 40px 0 20px;
            color: #66021f;
            font-family: Brow sugar;
        }
        .avis-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .avis-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
        }
        .avis-card h3 {
            margin: 0;
            font-size: 1.2em;
            color: #66021f;
        }
        .avis-card .date {
            font-size: 0.9em;
            color: #888;
            margin-bottom: 10px;
        }
        .avis-card p {
            font-size: 1em;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Tous les avis des utilisateurs</h1>
    <div class="avis-container">
        <?php
        $sql = "SELECT a.contenu, a.date_avis, v.nom AS nom_boutique, u.nom_utilisateur
                FROM avis a
                JOIN vendeurs v ON a.vendeur_id = v.id
                JOIN utilisateurs u ON a.utilisateur_id = u.id
                ORDER BY a.date_avis DESC";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='avis-card'>";
                echo "<h3>" . htmlspecialchars($row['nom_utilisateur']) . " a laissé un avis sur <strong>" . htmlspecialchars($row['nom_boutique']) . "</strong></h3>";
                echo "<div class='date'>Posté le " . date("d/m/Y", strtotime($row['date_avis'])) . "</div>";
                echo "<p>" . htmlspecialchars($row['contenu']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Aucun avis n'a encore été publié.</p>";
        }
        ?>
    </div>
</body>
</html>