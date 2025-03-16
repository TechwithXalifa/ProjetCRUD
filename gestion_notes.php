<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est bien un admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

// Récupérer la liste des classes
try {
    $stmt = $pdo->query("SELECT * FROM classes");
    $classes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors du chargement des classes : " . $e->getMessage());
}

// Vérifier si une classe a été sélectionnée
$etudiants = [];
if (!empty($_POST['classe_id'])) {
    $classe_id = $_POST['classe_id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE classe_id = ?");
        $stmt->execute([$classe_id]);
        $etudiants = $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Erreur lors du chargement des étudiants : " . $e->getMessage());
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
        <h2>Gestion des Notes</h2>

        <!-- Sélection d'une classe -->
        <form action="gestion_notes.php" method="POST">
            <label for="classe_id">Choisir une classe :</label>
            <select name="classe_id" required onchange="this.form.submit()">
                <option value="">-- Sélectionnez une classe --</option>
                <?php foreach ($classes as $classe) { ?>
                    <option value="<?= $classe['id'] ?>" <?= (!empty($classe_id) && $classe_id == $classe['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($classe['nom']) ?>
                    </option>
                <?php } ?>
            </select>
        </form>

        <!-- Affichage des étudiants de la classe sélectionnée -->
        <?php if (!empty($etudiants)) { ?>
            <h3>Liste des étudiants</h3>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($etudiants as $etudiant) { ?>
                    <tr>
                        <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                        <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                        <td>
                            <a href="voir_notes.php?id=<?= $etudiant['id'] ?>" class="btn">Voir Notes</a>
                            <a href="modifier_note.php?id=<?= $etudiant['id'] ?>" class="btn btn-secondary">Modifier</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else if (!empty($classe_id)) { ?>
            <p>Aucun étudiant trouvé pour cette classe.</p>
        <?php } ?>
    </div>

</body>
</html>
