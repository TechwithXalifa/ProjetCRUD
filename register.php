<?php
include 'config.php'; // Connexion à la base de données
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $login = trim($_POST['login']);
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
    $classe_id = $_POST['classe_id'];

    // Dossier où seront stockées les photos
    $dossier =  "profils/";
    $nomFichier = basename($_FILES["photo"]["name"]);
    $cheminPhoto = $dossier . $nomFichier;
    $uploadOk = 1;
    $typeImage = strtolower(pathinfo($cheminPhoto, PATHINFO_EXTENSION));

    // Vérification du format de l'image
    if ($typeImage != "jpg" && $typeImage != "png" && $typeImage != "jpeg" && $typeImage != "gif") {
        $erreur = "Seuls les fichiers JPG, JPEG, PNG & GIF sont autorisés.";
        $uploadOk = 0;
    }

    // Si tout est bon, on tente d'uploader l'image
    if ($uploadOk && move_uploaded_file($_FILES["photo"]["tmp_name"], $cheminPhoto)) {
        try {
            // Vérifier si l'email ou le login existe déjà
            $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE email = ? OR login = ?");
            $stmt->execute([$email, $login]);
            $existingUser = $stmt->fetch();

            if ($existingUser) {
                $erreur = "Cet email ou login est déjà utilisé.";
            } else {
                // Insérer le nouvel étudiant avec la photo
                $stmt = $pdo->prepare("INSERT INTO etudiants (nom, prenom, email, login, motdepasse, classe_id, photo) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$nom, $prenom, $email, $login, $motdepasse, $classe_id, $cheminPhoto])) {
                    $_SESSION['success'] = "Inscription réussie ! Connectez-vous.";
                    header("Location: login.php");
                    exit();
                } else {
                    $erreur = "Erreur lors de l'inscription.";
                }
            }
        } catch (PDOException $e) {
            $erreur = "Erreur : " . $e->getMessage();
        }
    } else {
        $erreur = "Erreur lors de l'upload de la photo.";
    }
}

// Récupérer la liste des classes
try {
    $stmt = $pdo->query("SELECT * FROM classes");
    $classes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors du chargement des classes : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Gestion des Notes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Inscription Étudiant</h2>

        <?php if (!empty($erreur)) { echo "<p class='error'>$erreur</p>"; } ?>

        <form action="register.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="login" placeholder="Nom d'utilisateur" required>
            <input type="password" name="motdepasse" placeholder="Mot de passe" required>

            <label for="classe">Sélectionnez votre classe :</label>
            <select name="classe_id" required>
                <option value="">-- Choisir une classe --</option>
                <?php foreach ($classes as $classe) { ?>
                    <option value="<?= $classe['id'] ?>"><?= $classe['nom'] ?></option>
                <?php } ?>
            </select>

            <label for="photo">Sélectionnez une photo de profil :</label>
            <input type="file" name="photo" id="photo" required>

            <button type="submit">S'inscrire</button>
        </form>

        <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a></p>
    </div>

</body>
</html>
