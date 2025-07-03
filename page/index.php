<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="Style3.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .flash-message {
    background-color: #d4edda;
    color: pink;
    padding: 15px;
    margin: 15px auto;
    text-align: center;
    justify-content: center;
    border-radius: 5px;
    width: 80%;
    font-weight: bold;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    animation: fadeOut 5s forwards;
}
h6{
    text-align: center;
    font-size: 12px;
}

.bouton {
    display: inline-block;
    padding: 12px 20px;
    background-color: pink;
    color: white;
    text-decoration: none;
    font-weight: bold;
    border-radius: 6px;
    transition: background-color 0.3s ease;
}

.bouton:hover {
    background-color: palevioletred;
}

@keyframes fadeOut {
    0% { opacity: 1; }
    80% { opacity: 1; }
    100% { opacity: 0; display: none; }
}

</style>







</head>
<body>


    <div class="navbar">
        <div class="logo">
            <img src="Logo.png" height="50px" width="100px">
        </div>
        <div class="content">
            <div class="lien" id="menu">
                <a href="connection.html">Connexion</a>
                <a href="Inscription2.html">Inscrivez-vous !</a>
                <a href="../public/deconnexion.php">Déconnexion</a>
            </div>
        </div>
    </div><br><br>

    <?php
session_start();

if (isset($_GET['message']) && $_GET['message'] === 'supprime') {
    echo "<script>alert('Votre compte a été supprimé avec succès.');</script>";
}


if (isset($_SESSION['message'])) {
    echo "<div class='flash-message'>" . htmlspecialchars($_SESSION['message']) . "</div>";
    unset($_SESSION['message']); // Supprime le message après affichage
}
?>



    <h1 class="h1">Découvrez des Vendeurs<br> de Confiance</h1><br>
    <h2 class="h2">Trouvez des vendeurs fiables et pas seulement !<br> pour tous vos achats en toute sécurité.</h2><br>
    
    <div style="text-align:center;">
        <button class="button"><a href="plus.html"> En savoir plus</a></button>
    </div>

    <h4 class="h4">Comment fonctionne notre site</h4>

    <div style="text-align: center; margin-top: 20px;">
    <a href="../public/liste_vendeurs.php" class="bouton">Découvrir + de 100 vendeur !</a>
</div>

    <div class="card-container">
        <a href="../public/categories.php" style="text-decoration: none; color: inherit;">
            <div class="info-card">
                <i class="fas fa-search"></i>
                <h3>Recherche</h3>
                <p>Trouvez facilement des vendeurs en e-commerce à Kin avec plus de facilité, plus besoin de vous prendre la tête.</p>
            </div>
        </a>

        <a href="../public/tous_les_avis.php" style="text-decoration: none; color: inherit;">


            <div class="info-card">
                <i class="fas fa-star"></i>
                <h3>Avis</h3>
                <p>Lisez et partagez des avis pour garantir la confiance et la sécurité de vos achats.</p>
            </div>
        </a>
    </div>

    <h5 class="h5">Découvrez Vos vendeurs préférés !</h5><br>


    <h6 >Etes-vous un vendeur ?<br> Voulez-vous réjoindre l'avanture au côtés de + de 100 autres vendeurs ?</h6>
    
    <div style="text-align: center; margin-top: 20px;">
    <a href="../public/ajouter_boutique.php" class="bouton">S'enregistrer comme vendeur ?</a>
</div>


    <footer class="footer"><br><br>
        <p class="p2">Easy Research est une plateforme dédiée à la recherche de vendeurs fiables en e-commerce à Kinshasa.</p>
        <p class="p3">&copy; 2025 Easy Research. Tous droits réservés.</p>
    </footer>
</body>
</html>



