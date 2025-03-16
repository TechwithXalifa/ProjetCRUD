<?php
session_start();
include 'config.php';

// Vérifier si un ID est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: liste_etudiants.php");
    exit();
}

$etudiant_id = $_GET['id'];

try {
    // Supprimer l'étudiant
    $stmt = $pdo->prepare("DELETE FROM etudiants WHERE id = ?");
    $stmt->execute([$etudiant_id]);

    // Redirection après suppression
    header("Location: liste_etudiants.php?success=suppression");
    exit();
} catch (PDOException $e) {
    die("Erreur lors de la suppression : " . $e->getMessage());
}
?>
