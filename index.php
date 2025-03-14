<?php
session_start(); // Démarrer la session pour gérer les connexions
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Notes - Accueil</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <div class="container">
        <h1>Bienvenue sur la plateforme de gestion des notes</h1>
        <p>Connectez-vous ou créez un compte pour accéder à vos notes.</p>
        
        <div class="buttons">
            <a href="login.php" class="btn">Se connecter</a>
            <a href="register.php" class="btn">S'inscrire</a>
        </div>
    </div>

</body>
</html>
