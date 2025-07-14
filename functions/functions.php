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
    $nom = $data['nom'];
    $email = $data['email'];
    $mdp = $data['mdp'];
    $ville = $data['ville'];
    $genre = $data['genre'];
    $date_naissance = $data['date_naissance'];

    $sql = "INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp, image_profil)
            VALUES ('$nom', '$date_naissance', '$genre', '$email', '$ville', '$mdp', 'default.jpg')";
    mysqli_query($conn, $sql);
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
    $sql = "SELECT * FROM categorie_objet";
    return mysqli_query($conn, $sql);
}

function getObjets($conn, $id_categorie = null) {
    if ($id_categorie) {
        $sql = "SELECT * FROM objet WHERE id_categorie = $id_categorie";
    } else {
        $sql = "SELECT * FROM objet";
    }
    return mysqli_query($conn, $sql);
}
?>
