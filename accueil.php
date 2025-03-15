<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est connecté et n'est pas un admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Récupérer les infos de l'étudiant connecté, y compris la photo
    $stmt = $pdo->prepare("SELECT e.nom, e.prenom, e.photo, c.nom AS classe 
                           FROM etudiants e 
                           JOIN classes c ON e.classe_id = c.id 
                           WHERE e.id = ?");
    $stmt->execute([$user_id]);
    $etudiant = $stmt->fetch();

    // Récupérer les notes de l'étudiant
    $stmt = $pdo->prepare("SELECT m.nom AS matiere, n.note 
                           FROM notes n 
                           JOIN matieres m ON n.matiere_id = m.id 
                           WHERE n.etudiant_id = ?");
    $stmt->execute([$user_id]);
    $notes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Étudiant</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Bienvenue, <?= htmlspecialchars($etudiant['prenom']) . " " . htmlspecialchars($etudiant['nom']) ?> !</h2>
        <p><strong>Classe :</strong> <?= htmlspecialchars($etudiant['classe']) ?></p>

        <!-- Affichage de la photo de profil -->
        <?php if (!empty($etudiant['photo'])) { ?>
            <div class="photo-container">
                <img src="<?= htmlspecialchars($etudiant['photo']) ?>" alt="Photo de profil" class="profil-photo">

            </div>
        <?php } else { ?>
            <p>Aucune photo de profil disponible.</p>
        <?php } ?>


        <h3>Vos Notes</h3>
        <table border="1">
            <tr>
                <th>Matière</th>
                <th>Note</th>
            </tr>
            <?php if (count($notes) > 0) { 
                foreach ($notes as $note) { ?>
                    <tr>
                        <td><?= htmlspecialchars($note['matiere']) ?></td>
                        <td><?= htmlspecialchars($note['note']) ?></td>
                    </tr>
                <?php } 
            } else { ?>
                <tr><td colspan="2">Aucune note disponible.</td></tr>
            <?php } ?>
        </table>

        <a href="logout.php" class="btn">Se déconnecter</a>
    </div>

</body>
</html>
