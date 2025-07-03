<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Enregistrer votre boutique</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #333;
    }

    form {
      max-width: 700px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    label {
      font-weight: bold;
    }

    input[type="text"], input[type="file"] {
      width: 100%;
      padding: 10px;
      margin: 8px 0 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    textarea  {
      width: 100%;
      padding: 10px;
      margin: 8px 0 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    } 

    .categories-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 15px;
      margin-bottom: 20px;
    }

    .category-option {
      border: 2px solid #ccc;
      border-radius: 8px;
      padding: 10px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      background-color: #f1f1f1;
    }

    .category-option:hover {
      background-color: #d4edda;
      border-color: #28a745;
    }

    input[type="radio"] {
      display: none;
    }

    input[type="radio"]:checked + .category-option {
      background-color: #28a745;
      color: white;
      border-color: #218838;
    }

    button {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #218838;
    }

  </style>
</head>
<body>

<h2>Enregistrer votre boutique</h2>

<form action="../back_end/traitement_boutique.php" method="POST" enctype="multipart/form-data">
  <label>Nom de la boutique :</label>
  <input type="text" name="nom" required>

  <label for="description">Description :</label><br>
  <textarea id="description" name="description" rows="4" cols="50" placeholder="Décris ta boutique ici..."></textarea>
  <br><br>

  <label>Numéro de téléphone :</label>
  <input type="text" name="telephone" required>

  <label>Instagram :</label>
  <input type="text" name="instagram">

  <label>TikTok :</label>
  <input type="text" name="tiktok">

  <label>Choisissez une catégorie :</label><br><br>

  <div class="categories-grid">
    <?php
    include("../includes/db.php");
    $req = $conn->query("SELECT * FROM categories");

    while ($cat = $req->fetch_assoc()) {
        echo '
        <label>
          <input type="radio" name="categorie_id" value="' . $cat['id'] . '" required>
          <div class="category-option">' . htmlspecialchars($cat['nom']) . '</div>
        </label>';
    }
    ?>
  </div>

  <label>Images de la boutique (5 max) :</label>
  <input type="file" name="images[]" accept="image/*" multiple required>

  <button type="submit">S’enregistrer</button>
</form>

</body>
</html>