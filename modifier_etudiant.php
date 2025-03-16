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
    header("Location: liste_etudiants.php");
    exit();
}

$etudiant_id = $_GET['id'];

try {
    // Récupérer les informations de l'étudiant
    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
    $stmt->execute([$etudiant_id]);
    $etudiant = $stmt->fetch();

    // Récupérer la liste des classes
    $stmt = $pdo->query("SELECT * FROM classes");
    $classes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $login = trim($_POST['login']);
    $classe_id = $_POST['classe_id'];
    $photo = $etudiant['photo']; // Conserver l'ancienne photo par défaut

    // Gérer l'upload d'une nouvelle photo
    if (!empty($_FILES["photo"]["name"])) {
        $dossier = "profils/";
        $nomFichier = basename($_FILES["photo"]["name"]);
        $cheminPhoto = $dossier . $nomFichier;
        $typeImage = strtolower(pathinfo($cheminPhoto, PATHINFO_EXTENSION));

        // Vérifier les formats acceptés
        if (in_array($typeImage, ["jpg", "jpeg", "png", "gif"])) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $cheminPhoto)) {
                $photo = $cheminPhoto; // Mettre à jour la photo avec le nouveau fichier
            } else {
                $error = "Erreur lors de l'upload de la photo.";
            }
        } else {
            $error = "Seuls les formats JPG, JPEG, PNG et GIF sont autorisés.";
        }
    }

    // Mise à jour des informations
    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("UPDATE etudiants SET nom = ?, prenom = ?, email = ?, login = ?, classe_id = ?, photo = ? WHERE id = ?");
            $stmt->execute([$nom, $prenom, $email, $login, $classe_id, $photo, $etudiant_id]);

            header("Location: liste_etudiants.php?success=modification");
            exit();
        } catch (PDOException $e) {
            $error = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Étudiant</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Modifier l'Étudiant</h2>

        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form action="modifier_etudiant.php?id=<?= $etudiant_id ?>" method="POST" enctype="multipart/form-data">
            <label>Nom :</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($etudiant['nom']) ?>" required>

            <label>Prénom :</label>
            <input type="text" name="prenom" value="<?= htmlspecialchars($etudiant['prenom']) ?>" required>

            <label>Email :</label>
            <input type="email" name="email" value="<?= htmlspecialchars($etudiant['email']) ?>" required>

            <label>Login :</label>
            <input type="text" name="login" value="<?= htmlspecialchars($etudiant['login']) ?>" required>

            <label>Classe :</label>
            <select name="classe_id" required>
                <?php foreach ($classes as $classe) { ?>
                    <option value="<?= $classe['id'] ?>" <?= ($etudiant['classe_id'] == $classe['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($classe['nom']) ?>
                    </option>
                <?php } ?>
            </select>

            <!-- Affichage de la photo actuelle -->
            <?php if (!empty($etudiant['photo'])) { ?>
                <div class="photo-container">
                    <p>Photo actuelle :</p>
                    <img src="<?= htmlspecialchars($etudiant['photo']) ?>" alt="Photo de profil" class="profil-photo">
                </div>
            <?php } ?>

            <label>Changer la photo :</label>
            <input type="file" name="photo">

            <button type="submit">Enregistrer les Modifications</button>
        </form>

        <a href="liste_etudiants.php" class="btn btn-secondary">Retour</a>
    </div>

</body>
</html>
