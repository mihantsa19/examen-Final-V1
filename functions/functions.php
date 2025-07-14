<?php
include 'db.php';

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
