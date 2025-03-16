<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est bien un admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Bienvenue, Admin <?= htmlspecialchars($_SESSION['login']) ?></h2>

        <p>Que voulez-vous faire ?</p>

        <div class="button-group">
            <a href="admin.php" class="btn">Ajouter des Notes</a>
            <a href="gestion_notes.php" class="btn">Gérer les Notes</a>
            <a href="liste_etudiants.php" class="btn btn-secondary">Gérer les Étudiants</a>
            <a href="logout.php" class="btn btn-danger">Se Déconnecter</a>
        </div>
    </div>

</body>
</html>
