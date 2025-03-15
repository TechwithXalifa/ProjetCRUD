<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

// Récupérer toutes les notes avec les infos des étudiants et matières
try {
    $stmt = $pdo->query("SELECT n.id, e.nom AS etudiant_nom, e.prenom AS etudiant_prenom, 
                                m.nom AS matiere_nom, n.note 
                         FROM notes n
                         JOIN etudiants e ON n.etudiant_id = e.id
                         JOIN matieres m ON n.matiere_id = m.id
                         ORDER BY e.nom, m.nom");
    $notes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Supprimer une note
if (isset($_POST['delete_note_id'])) {
    $note_id = $_POST['delete_note_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
        $stmt->execute([$note_id]);
        header("Location: gestion_notes.php"); // Recharger la page après suppression
        exit();
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Notes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Gérer les Notes</h2>

        <table border="1">
            <tr>
                <th>Étudiant</th>
                <th>Matière</th>
                <th>Note</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($notes as $note) { ?>
                <tr>
                    <td><?= htmlspecialchars($note['etudiant_nom'] . " " . $note['etudiant_prenom']) ?></td>
                    <td><?= htmlspecialchars($note['matiere_nom']) ?></td>
                    <td><?= htmlspecialchars($note['note']) ?></td>
                    <td>
                        <a href="modifier_note.php?id=<?= $note['id'] ?>" class="btn btn-warning">Modifier</a>
                        <form action="gestion_notes.php" method="POST" style="display:inline;">
                            <input type="hidden" name="delete_note_id" value="<?= $note['id'] ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer cette note ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <a href="accueil_admin.php" class="btn btn-secondary">Retour au Tableau de Bord</a>
    </div>

</body>
</html>
