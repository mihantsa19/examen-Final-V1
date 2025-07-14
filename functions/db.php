<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "emprunt";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) 
{
    die("Erreur de connexion à la base !");
}
?>
