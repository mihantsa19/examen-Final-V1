<?php
include 'db.php';

function connectDB() {
    $conn = mysqli_connect("localhost", "root", "", "emprunt");
    if (!$conn) {
        die("Erreur de connexion");
    }
    return $conn;
}

function inscrireMembre($data) {
    $conn = connectDB();

    $nom = trim($data['nom']);
    $email = trim($data['email']);
    $mdp = trim($data['mdp']);
    $ville = trim($data['ville']);
    $genre = trim($data['genre']);
    $date_naissance = trim($data['date_naissance']);

    if (empty($nom) || empty($email) || empty($mdp) || empty($ville) || empty($genre) || empty($date_naissance)) {
        die("Tous les champs sont obligatoires.");
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_naissance)) {
        die("Date invalide. Format attendu : AAAA-MM-JJ.");
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp, image_profil)
                                   VALUES (?, ?, ?, ?, ?, ?, 'default.jpg')");
    mysqli_stmt_bind_param($stmt, "ssssss", $nom, $date_naissance, $genre, $email, $ville, $mdp);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function connecterMembre($email, $mdp) {
    $conn = connectDB();
    $sql = "SELECT * FROM membre WHERE email='$email' AND mdp='$mdp'";
    $res = mysqli_query($conn, $sql);
    if (mysqli_num_rows($res) == 1) {
        return mysqli_fetch_assoc($res);
    } else {
        return false;
    }
}

function getCategories($conn) {
    return mysqli_query($conn, "SELECT * FROM categorie_objet");
}

function getObjets($conn, $id_categorie = null) {
    $dateNow = date('Y-m-d');
    if ($id_categorie) {
        $sql = "SELECT o.*, e.date_retour FROM objet o
                LEFT JOIN emprunt e ON o.id_objet = e.id_objet AND e.date_retour >= '$dateNow'
                WHERE o.id_categorie = ?
                ORDER BY o.nom_objet";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_categorie);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    } else {
        $sql = "SELECT o.*, e.date_retour FROM objet o
                LEFT JOIN emprunt e ON o.id_objet = e.id_objet AND e.date_retour >= '$dateNow'
                ORDER BY o.nom_objet";
        return mysqli_query($conn, $sql);
    }
}

?>
