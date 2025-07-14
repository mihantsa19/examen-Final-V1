<?php
session_start();
include(__DIR__ . '/../functions/functions.php');

if (!isset($_SESSION['membre_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idObjet = (int)($_POST['id_objet'] ?? 0);
    $duree = (int)($_POST['duree_jours'] ?? 0);

    if ($idObjet <= 0 || $duree <= 0 || $duree > 30) {
        die("Erreur : données invalides.");
    }

    $dateRetour = date('Y-m-d', strtotime("+$duree days"));

    $conn = connectDB();


    $sqlCheck = "SELECT date_retour FROM objet WHERE id_objet = $idObjet";
    $res = $conn->query($sqlCheck);
    if ($res && $row = $res->fetch_assoc()) {
        if (!empty($row['date_retour']) && $row['date_retour'] > date('Y-m-d')) {
            die("Erreur : cet objet est déjà emprunté.");
        }
    } else {
        die("Erreur : objet non trouvé.");
    }


    $sqlUpdate = "UPDATE objet SET date_retour = '$dateRetour' WHERE id_objet = $idObjet";
    if (!$conn->query($sqlUpdate)) {
        die("Erreur lors de la mise à jour : " . $conn->error);
    }

 
    $idMembre = $_SESSION['membre_id'];
    $dateEmprunt = date('Y-m-d');

    $sqlInsert = "INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour_prevue) VALUES ($idObjet, $idMembre, '$dateEmprunt', '$dateRetour')";
    if (!$conn->query($sqlInsert)) {
        die("Erreur lors de l'insertion historique : " . $conn->error);
    }

    header("Location: objets.php?message=emprunt_ok");
    exit;
}
?>
