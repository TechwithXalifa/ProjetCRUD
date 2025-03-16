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

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $login = trim($_POST['login']);
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
    $classe_id = $_POST['classe_id'];
    $photo = NULL;

    // Gérer l'upload d'une photo de profil
    if (!empty($_FILES["photo"]["name"])) {
        $dossier = "profils/";
        $nomFichier = basename($_FILES["photo"]["name"]);
        $cheminPhoto = $dossier . $nomFichier;
        $typeImage = strtolower(pathinfo($cheminPhoto, PATHINFO_EXTENSION));

        if (in_array($typeImage, ["jpg", "jpeg", "png", "gif"])) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $cheminPhoto)) {
                $photo = $cheminPhoto;
            } else {
                $error = "Erreur lors de l'upload de la photo.";
            }
        } else {
            $error = "Seuls les formats JPG, JPEG, PNG et GIF sont autorisés.";
        }
    }

    // Ajouter l'étudiant dans la base de données
    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO etudiants (nom, prenom, email, login, motdepasse, classe_id, photo) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $email, $login, $motdepasse, $classe_id, $photo]);

            header("Location: liste_etudiants.php?success=ajout");
            exit();
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout de l'étudiant : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Étudiant</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Ajouter un Nouvel Étudiant</h2>

        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form action="ajouter_etudiant.php" method="POST" enctype="multipart/form-data">
            <label>Nom :</label>
            <input type="text" name="nom" required>

            <label>Prénom :</label>
            <input type="text" name="prenom" required>

            <label>Email :</label>
            <input type="email" name="email" required>

            <label>Login :</label>
            <input type="text" name="login" required>

            <label>Mot de passe :</label>
            <input type="password" name="motdepasse" required>

            <label>Classe :</label>
            <select name="classe_id" required>
                <option value="">-- Sélectionnez une classe --</option>
                <?php foreach ($classes as $classe) { ?>
                    <option value="<?= $classe['id'] ?>"><?= htmlspecialchars($classe['nom']) ?></option>
                <?php } ?>
            </select>

            <label>Photo de profil :</label>
            <input type="file" name="photo">

            <button type="submit">Ajouter l'Étudiant</button>
        </form>

        <a href="liste_etudiants.php" class="btn btn-secondary">Retour</a>
    </div>

</body>
</html>
