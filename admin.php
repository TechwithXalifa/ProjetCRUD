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

// Récupérer la liste des matières
try {
    $stmt = $pdo->query("SELECT * FROM matieres");
    $matieres = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors du chargement des matières : " . $e->getMessage());
}

// Vérifier si une classe a été sélectionnée pour afficher les étudiants
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

// Ajouter une note si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['etudiant_id'], $_POST['matiere_id'], $_POST['note'])) {
    $etudiant_id = $_POST['etudiant_id'];
    $matiere_id = $_POST['matiere_id'];
    $note = $_POST['note'];

    try {
        $stmt = $pdo->prepare("INSERT INTO notes (etudiant_id, matiere_id, note) VALUES (?, ?, ?)");
        $stmt->execute([$etudiant_id, $matiere_id, $note]);
        $success = "Note ajoutée avec succès !";
    } catch (PDOException $e) {
        $error = "Erreur lors de l'ajout de la note : " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des Notes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Tableau de Bord - Administrateur</h2>

        <p>Bienvenue, <strong><?= htmlspecialchars($_SESSION['login']) ?></strong></p>

        <?php if (!empty($success)) { echo "<p class='success'>$success</p>"; } ?>
        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>

        <h3>Ajouter une Note</h3>

        <!-- Sélectionner une classe -->
        <form action="admin.php" method="POST">
            <label for="classe_id">Choisir une classe :</label>
            <select name="classe_id" onchange="this.form.submit()">
                <option value="">-- Sélectionnez une classe --</option>
                <?php foreach ($classes as $classe) { ?>
                    <option value="<?= $classe['id'] ?>" <?= (!empty($classe_id) && $classe_id == $classe['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($classe['nom']) ?>
                    </option>
                <?php } ?>
            </select>
        </form>

        <?php if (!empty($etudiants)) { ?>
            <form action="admin.php" method="POST">
                <input type="hidden" name="classe_id" value="<?= $classe_id ?>">

                <label for="etudiant_id">Choisir un étudiant :</label>
                <select name="etudiant_id" required>
                    <option value="">-- Sélectionnez un étudiant --</option>
                    <?php foreach ($etudiants as $etudiant) { ?>
                        <option value="<?= $etudiant['id'] ?>"><?= $etudiant['prenom'] . " " . $etudiant['nom'] ?></option>
                    <?php } ?>
                </select>

                <label for="matiere_id">Choisir une matière :</label>
                <select name="matiere_id" required>
                    <option value="">-- Sélectionnez une matière --</option>
                    <?php foreach ($matieres as $matiere) { ?>
                        <option value="<?= $matiere['id'] ?>"><?= $matiere['nom'] ?></option>
                    <?php } ?>
                </select>

                <label for="note">Note :</label>
                <input type="number" step="0.05" name="note" required>

                <button type="submit">Ajouter la Note</button>
            </form>
        <?php } ?>
        
        <a href="accueil_admin.php" class="btn btn-secondary">Retour à l'acceuil</a>
        <a href="logout.php" class="btn">Se déconnecter</a>
    </div>

</body>
</html>
