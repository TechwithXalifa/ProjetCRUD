<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est bien un admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

// Récupérer la liste des étudiants avec leur classe
try {
    $stmt = $pdo->query("SELECT e.id, e.nom, e.prenom, e.email, e.login, c.nom AS classe 
                         FROM etudiants e
                         JOIN classes c ON e.classe_id = c.id");
    $etudiants = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors du chargement des étudiants : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Étudiants</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Liste des Étudiants</h2>
    
        <?php if (isset($_GET['success']) && $_GET['success'] == 'suppression') { ?>
            <p class="success">L'étudiant a été supprimé avec succès.</p>
        <?php } ?>


        <!-- Bouton pour ajouter un étudiant -->
        <a href="ajouter_etudiant.php" class="btn btn-primary">Ajouter un Étudiant</a>

        <table>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Login</th>
                <th>Classe</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($etudiants as $etudiant) { ?>
                <tr>
                    <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                    <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                    <td><?= htmlspecialchars($etudiant['email']) ?></td>
                    <td><?= htmlspecialchars($etudiant['login']) ?></td>
                    <td><?= htmlspecialchars($etudiant['classe']) ?></td>
                    <td>
                        <a href="voir_etudiant.php?id=<?= $etudiant['id'] ?>" class="btn">Voir</a>
                        <a href="modifier_etudiant.php?id=<?= $etudiant['id'] ?>" class="btn btn-secondary">Modifier</a>
                        <a href="supprimer_etudiant.php?id=<?= $etudiant['id'] ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">
                           Supprimer
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <a href="accueil_admin.php" class="btn btn-secondary">Retour</a>
    </div>

</body>
</html>
