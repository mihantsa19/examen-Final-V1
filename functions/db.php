<?php
$conn = mysqli_connect("localhost", "ETU004252", "motdepasse", "emprunt");

if (!$conn) {
    die("Erreur : " . mysqli_connect_error());
} else {
    echo "Connexion OK ✅";
}
?>
