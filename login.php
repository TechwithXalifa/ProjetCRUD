<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim($_POST['login']);
    $motdepasse = $_POST['motdepasse'];

    try {
        $isAdmin = false;

        // Vérifier si l'utilisateur est un étudiant
        $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        // Si l'utilisateur n'est pas trouvé en tant qu'étudiant, vérifier s'il est admin
        if (!$user) {
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE login = ?");
            $stmt->execute([$login]);
            $user = $stmt->fetch();
            $isAdmin = true;
        }

        // Vérification du mot de passe
        if ($user) {
            if (
                (!$isAdmin && password_verify($motdepasse, $user['motdepasse'])) || // Étudiant (password_hash)
                ($isAdmin && $user['motdepasse'] === sha1($motdepasse)) // Admin (SHA1)
            ) {
                // Création de la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['login'] = $user['login'];
                $_SESSION['is_admin'] = $isAdmin;

                // Redirection vers l'espace correspondant
                if ($isAdmin) {
                    header("Location: accueil_admin.php");
                } else {
                    header("Location: accueil.php");
                }
                exit();
            } else {
                $error = "Identifiants incorrects.";
            }
        } else {
            $error = "Identifiants incorrects.";
        }
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion des Notes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Connexion</h2>

        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
        
        <form action="login.php" method="POST">
            <input type="text" name="login" placeholder="Nom d'utilisateur" required>
            <input type="password" name="motdepasse" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>

        <p>Pas encore inscrit ? <a href="register.php">Créez un compte ici</a></p>
    </div>

</body>
</html>
