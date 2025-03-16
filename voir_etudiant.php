<?php
session_start();
include 'config.php';

// Vérifier si un ID est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: liste_etudiants.php");
    exit();
}

$etudiant_id = $_GET['id'];

// Récupérer les informations de l'étudiant
try {
    $stmt = $pdo->prepare("SELECT e.nom, e.prenom, e.email, e.login, e.photo, c.nom AS classe 
                           FROM etudiants e
                           JOIN classes c ON e.classe_id = c.id
                           WHERE e.id = ?");
    $stmt->execute([$etudiant_id]);
    $etudiant = $stmt->fetch();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'Étudiant</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2><?= htmlspecialchars($etudiant['prenom'] . " " . $etudiant['nom']) ?></h2>

        <p><strong>Email :</strong> <?= $etudiant['email'] ?></p>
        <p><strong>Login :</strong> <?= $etudiant['login'] ?></p>
        <p><strong>Classe :</strong> <?= $etudiant['classe'] ?></p>

        <?php if (!empty($etudiant['photo'])) { ?>
            <div class="photo-container">
                <img src="<?= $etudiant['photo'] ?>" alt="Photo de profil" class="profil-photo">
            </div>
        <?php } ?>

        <a href="liste_etudiants.php" class="btn btn-secondary">Retour</a>
    </div>

</body>
</html>
