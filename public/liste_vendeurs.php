<?php 
include('../includes/db.php');

$categorie = null;
$vendeurs = null;

if (isset($_GET['categorie'])) {
    $categorie_id = intval($_GET['categorie']);

    $stmt = $conn->prepare("SELECT nom FROM categories WHERE id = ?");
    $stmt->bind_param("i", $categorie_id);
    $stmt->execute();
    $cat_result = $stmt->get_result();
    $cat = $cat_result->fetch_assoc();

    if ($cat) {
        $categorie = $cat['nom'];

        $req = $conn->prepare("SELECT * FROM vendeurs WHERE categorie_id = ?");
        $req->bind_param("i", $categorie_id);
        $req->execute();
        $vendeurs = $req->get_result();
    } else {
        echo "<p style='text-align:center;'>Catégorie introuvable.</p>";
        exit;
    }
} else {
    // Pas de catégorie : on prend tous les vendeurs
    $sql = "SELECT * FROM vendeurs";
    $vendeurs = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Liste des vendeurs certifiés</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: pink;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            margin-top: 30px;
            color: #66021f;
            font-family: Brown sugar;
        }
        h3 {
            text-align: center;
            color: #66021f;
        }
        .vendeurs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px ;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px #ffe4e1;
            padding: 24px 19px 18px 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: box-shadow 0.2s;
        }
        .card:hover {
            box-shadow: 0 4px 24px rgba(0,0,0,0.13);
        }
        .carousel {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            width: 100%;
            margin-bottom: 16px;
            padding-bottom: 8px;
        }
        .carousel img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            background: #fafafa;
            flex-shrink: 0;
            cursor: pointer;
        }
        .card h3 {
            margin: 0 0 8px 0;
            font-size: 1.2em;
            color: #222;
            text-align: center;
        }
        .card p {
            color: #444;
            font-size: 1em;
            text-align: center;
            margin-bottom: 12px;
            min-height: 48px;
            max-height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            position: relative;
        }
        .badge-certifie {
            color: #ffc95f;
            font-size: 1.2em;
            margin-left: 6px;
            vertical-align: middle;
        }
        .btn, .toggle-avis {
            display: inline-block;
            margin: 8px 4px 0 4px;
            padding: 8px 18px;
            background: #66021f;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }
        .btn:hover, .toggle-avis:hover {
            background: #f2afbc;
        }
        .avis-content {
            width: 100%;
            margin-top: 10px;
            display: none;
            height: 100px;
            overflow-y: scroll;
            background-color: palevioletred;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 0.97em;
        }
        .coordonnees {
            text-align: center;
            font-size: 0.9em;
            margin-top: 10px;
        }
        .coordonnees a {
            color: #a55166;
            text-decoration: none;
        }
        .coordonnees a:hover {
            text-decoration: underline;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.85);
        }
        .modal-content {
            margin: 60px auto;
            display: block;
            max-width: 90%;
            max-height: 80%;
        }
        .modal-content img {
            width: 50%;
            border-radius: 8px;
        }
        .close-modal {
            position: absolute;
            top: 30px;
            right: 40px;
            font-size: 40px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .en-savoir-plus {
            background: none;
            color: #66021f;
            border: none;
            font-size: 0.9em;
            cursor: pointer;
            margin-top: -8px;
            margin-bottom: 12px;
            text-decoration: underline;
        }
        .bouton {
    display: inline-block;
    padding: 12px 20px;
    background-color: #66021f;
    color: white;
    text-decoration: none;
    font-weight: bold;
    border-radius: 6px;
    transition: background-color 0.3s ease;
}

.bouton:hover {
    background-color: palevioletred;
}


        @media (max-width: 900px) {
            .vendeurs-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }
        }
        @media (max-width: 600px) {
            .vendeurs-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <h2>Trouvez votre bonheur !</h2>

    <div style="text-align: center; margin-top: 20px;">
    <a href="../public/categories.php" class="bouton">Voir les Différentes catégories de vendeur ?</a>
</div>



    <?php if ($categorie): ?>
        <h3>Catégorie : <?= htmlspecialchars(ucfirst($categorie)) ?></h3>
    <?php endif; ?>

    <div class="vendeurs-grid">
    <?php
    if ($vendeurs && $vendeurs->num_rows > 0) {
    while ($row = $vendeurs->fetch_assoc()) {
            $vendeur_id = $row['id'];
            echo "<div class='card'>";

            echo "<div class='carousel'>";
            $img_result = $conn->query("SELECT image FROM vendeur_images WHERE vendeur_id = $vendeur_id");
            if ($img_result && $img_result->num_rows > 0) {
                while ($img = $img_result->fetch_assoc()) {
                    $imgPath = '../images/' . htmlspecialchars($img['image']);
                    echo "<img src='$imgPath' alt='Image vendeur' onclick=\"openModal('$imgPath')\">";
                }
            }
            echo "</div>";

            echo "<h3>" . htmlspecialchars($row['nom']) . " <i class='fas fa-award badge-certifie' title='Vendeur certifié'></i></h3>";
            echo "<p class='description'>" . nl2br(htmlspecialchars($row['description'])) . "</p>";
            echo "<button class='en-savoir-plus' onclick='toggleDescription(this)'>lire plus</button>";

            echo "<div class='coordonnees'>";
            if (!empty($row['telephone'])) {
                echo "<p>📞 <a href='tel:" . htmlspecialchars($row['telephone']) . "'>" . htmlspecialchars($row['telephone']) . "</a></p>";
            }
            if (!empty($row['instagram'])) {
                echo "<p>📸 <a href='" . htmlspecialchars($row['instagram']) . "' target='_blank'>Instagram</a></p>";
            }
            if (!empty($row['tiktok'])) {
                echo "<p>🎵 <a href='" . htmlspecialchars($row['tiktok']) . "' target='_blank'>TikTok</a></p>";
            }
            echo "</div>";

            echo "<button class='toggle-avis' onclick=\"toggleAvis('avis-$vendeur_id', this)\">Afficher les avis</button>";

            $avis_stmt = $conn->prepare("SELECT a.contenu, a.date_avis, u.nom_utilisateur 
                            FROM avis a 
                            JOIN utilisateurs u ON a.utilisateur_id = u.id 
                            WHERE a.vendeur_id = ? 
                            ORDER BY a.date_avis DESC");
                    $avis_stmt->bind_param("i", $vendeur_id);
                    $avis_stmt->execute();
                    $avis_result = $avis_stmt->get_result();

echo "<div id='avis-$vendeur_id' class='avis-content'>";
if ($avis_result->num_rows > 0) {
    while ($avis = $avis_result->fetch_assoc()) {
        echo "<p style='margin: 8px 0; font-size: 14px;'>";
        echo "<strong>" . htmlspecialchars($avis['nom_utilisateur']) . "</strong> – ";
        echo "<em>" . date("d/m/Y", strtotime($avis['date_avis'])) . "</em><br>";
        echo htmlspecialchars($avis['contenu']);
        echo "</p>";
    }
} else {
    echo "<em>Aucun avis pour l’instant.</em>";
}
echo "</div>";

            echo "<a class='btn' href='ajouter_avis.php?vendeur_id=" . htmlspecialchars($row['id']) . "&categorie=" . urlencode($categorie) . "'>Laisser un avis</a>";
            echo "</div>";
        }
    } else {
        echo "<p style='text-align:center; font-size:1.2em;'>Aucun vendeur trouvé dans cette catégorie.</p>";
    }
    ?>
    </div>

    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close-modal">&times;</span>
        <div class="modal-content">
            <img id="modalImg" src="" alt="Image en grand">
        </div>
    </div>

<script>
function toggleAvis(id, btn) {
    var el = document.getElementById(id);
    if (el.style.display === "none" || el.style.display === "") {
        el.style.display = "block";
        btn.textContent = "Masquer les avis";
    } else {
        el.style.display = "none";
        btn.textContent = "Afficher les avis";
    }
}

function openModal(src) {
    document.getElementById("modalImg").src = src;
    document.getElementById("imageModal").style.display = "block";
}

function closeModal() {
    document.getElementById("imageModal").style.display = "none";
}

function toggleDescription(button) {
    var p = button.previousElementSibling;
    if (p.style.maxHeight === "none") {
        p.style.maxHeight = "60px";
        button.textContent = "En savoir plus";
    } else {
        p.style.maxHeight = "none";
        button.textContent = "Réduire";
    }
}
</script>
</body>
</html>
