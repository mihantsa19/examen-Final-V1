<?php
session_start();
if (!isset($_SESSION['membre_id'])) {
    header("Location: login.php");
    exit;
}

include(__DIR__ . '/../functions/functions.php');
$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_emprunt = $_POST['id_emprunt'] ?? null;
    $etat_retour = $_POST['etat_retour'] ?? null;

    if ($id_emprunt && in_array($etat_retour, ['bon', 'abime'])) {
        $sql = "UPDATE emprunt SET date_retour = NOW(), etat = ? WHERE id_emprunt = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $etat_retour, $id_emprunt);
        mysqli_stmt_execute($stmt);
    }
}

header("Location: emprunts_en_cours.php");
exit;
