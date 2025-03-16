<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est bien un admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

// Vérifier si un ID étudiant est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gestion_notes.php");
    exit();
}

$etudiant_id = $_GET['id'];

try {
    // Récupérer les informations de l'étudiant
    $stmt = $pdo->prepare("SELECT nom, prenom FROM etudiants WHERE id = ?");
    $stmt->execute([$etudiant_id]);
    $etudiant = $stmt->fetch();

    // Récupérer toutes les notes de l'étudiant
    $stmt = $pdo->prepare("SELECT n.id, n.note, m.nom AS matiere_nom 
                           FROM notes n
                           JOIN matieres m ON n.matiere_id = m.id
                           WHERE n.etudiant_id = ?");
    $stmt->execute([$etudiant_id]);
    $notes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['notes'] as $note_id => $nouvelle_note) {
        try {
            $stmt = $pdo->prepare("UPDATE notes SET note = ? WHERE id = ?");
            $stmt->execute([$nouvelle_note, $note_id]);
        } catch (PDOException $e) {
            die("Erreur lors de la mise à jour des notes : " . $e->getMessage());
        }
    }

    // Rediriger après la modification
    header("Location: gestion_notes.php?success=modification");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les Notes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Modifier les Notes de <?= $etudiant['prenom'] . " " . $etudiant['nom'] ?></h2>
        
        <form action="modifier_note.php?id=<?= $etudiant_id ?>" method="POST">
            <table>
                <tr>
                    <th>Matière</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($notes as $note) { ?>
                    <tr>
                        <td><?= $note['matiere_nom'] ?></td>
                        <td>    
                            <input type="number" step="0.05" name="notes[<?= $note['id'] ?>]" value="<?= $note['note'] ?>" required>
                        </td>
                        <td>
                            <a href="supprimer_note.php?id=<?= $note['id'] ?>&etudiant_id=<?= $etudiant_id ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette note ?');">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </table>

            <button type="submit">Mettre à jour</button>
        </form>

        <a href="gestion_notes.php" class="btn btn-secondary">Retour</a>
    </div>

</body>
</html>
