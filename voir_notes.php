<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est bien un admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

// Vérifier si un ID est passé
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gestion_notes.php");
    exit();
}

$etudiant_id = $_GET['id'];

// Récupérer les infos de l'étudiant et ses notes
try {
    $stmt = $pdo->prepare("SELECT nom, prenom FROM etudiants WHERE id = ?");
    $stmt->execute([$etudiant_id]);
    $etudiant = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT m.nom AS matiere, n.note 
                           FROM notes n 
                           JOIN matieres m ON n.matiere_id = m.id 
                           WHERE n.etudiant_id = ?");
    $stmt->execute([$etudiant_id]);
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
    <title>Notes de <?= htmlspecialchars($etudiant['prenom'] . " " . $etudiant['nom']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Notes de <?= htmlspecialchars($etudiant['prenom'] . " " . $etudiant['nom']) ?></h2>
        
        <table>
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

        <a href="gestion_notes.php" class="btn btn-secondary">Retour</a>
    </div>

</body>
</html>
