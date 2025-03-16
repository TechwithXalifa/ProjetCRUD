<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est bien un admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

// Vérifier si un ID de note est fourni
if (!isset($_GET['id']) || empty($_GET['id']) || !isset($_GET['etudiant_id']) || empty($_GET['etudiant_id'])) {
    header("Location: gestion_notes.php");
    exit();
}

$note_id = $_GET['id'];
$etudiant_id = $_GET['etudiant_id'];

try {
    // Supprimer la note
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
    $stmt->execute([$note_id]);

    // Rediriger vers la page de modification avec un message de succès
    header("Location: modifier_note.php?id=$etudiant_id&success=suppression");
    exit();
} catch (PDOException $e) {
    die("Erreur lors de la suppression : " . $e->getMessage());
}
?>
