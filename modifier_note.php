<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

// Vérifier si un ID est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gestion_notes.php");
    exit();
}

$note_id = $_GET['id'];

try {
    // Récupérer la note existante
    $stmt = $pdo->prepare("SELECT n.id, n.note, e.nom AS etudiant_nom, e.prenom AS etudiant_prenom, m.nom AS matiere_nom
                           FROM notes n
                           JOIN etudiants e ON n.etudiant_id = e.id
                           JOIN matieres m ON n.matiere_id = m.id
                           WHERE n.id = ?");
    $stmt->execute([$note_id]);
    $note = $stmt->fetch();

    // Si la note n'existe pas
    if (!$note) {
        header("Location: gestion_notes.php");
        exit();
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Mise à jour de la note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nouvelle_note'])) {
    $nouvelle_note = $_POST['nouvelle_note'];

    try {
        $stmt = $pdo->prepare("UPDATE notes SET note = ? WHERE id = ?");
        $stmt->execute([$nouvelle_note, $note_id]);

        // Redirection après modification
        header("Location: gestion_notes.php?success=modification");
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
    <title>Modifier la Note</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Modifier la Note</h2>
        
        <p><strong>Étudiant :</strong> <?= htmlspecialchars($note['etudiant_prenom'] . " " . $note['etudiant_nom']) ?></p>
        <p><strong>Matière :</strong> <?= htmlspecialchars($note['matiere_nom']) ?></p>

        <form action="modifier_note.php?id=<?= $note_id ?>" method="POST">
            <label for="nouvelle_note">Nouvelle Note :</label>
            <input type="number" step="0.01" name="nouvelle_note" value="<?= htmlspecialchars($note['note']) ?>" required>

            <button type="submit">Mettre à jour</button>
        </form>

        <a href="gestion_notes.php" class="btn btn-secondary">Retour à la Gestion des Notes</a>
    </div>

</body>
</html>
