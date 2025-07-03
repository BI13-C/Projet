<?php
include('../includes/db.php');

// Récupération de toutes les catégories
$categories = $conn->query("SELECT id, nom FROM categories ORDER BY nom ASC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catégories</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #ffe4ec;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1 {
            margin-top: 40px;
            color: #66021f;
            font-family: Brown sugar;
        }

        .grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 40px auto;
            max-width: 1000px;
            padding: 0 20px;
        }

        .categorie {
            display: block;
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            color: #66021f;
            font-weight: bold;
            font-size: 1em;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.25s ease;
            width: 220px;
        }

        .categorie:hover {
            background: #ffb6c1;
            color: white;
            transform: translateY(-4px);
        }

        @media (max-width: 600px) {
            .categorie {
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <h1>Choisissez une catégorie</h1>

    <div class="grid">
        <?php
        if ($categories && $categories->num_rows > 0) {
            while ($row = $categories->fetch_assoc()) {
                $id = $row['id'];
                $nom = ucfirst($row['nom']);
                echo "<a class='categorie' href='liste_vendeurs.php?categorie=$id'>$nom</a>";
            }
        } else {
            echo "<p>Aucune catégorie trouvée.</p>";
        }
        ?>
    </div>

</body>
</html>