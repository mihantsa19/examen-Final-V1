<?php
$conn = mysqli_connect("localhost", "ETU004252", "b6gA1BQk", "emprunt");

if (!$conn) {
    die("Erreur : " . mysqli_connect_error());
} else {
    echo "Connexion OK ✅";
}
?>
