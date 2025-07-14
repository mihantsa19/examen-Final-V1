
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include(__DIR__ . '/../functions.php');


if (isset($_POST['nom'])) {
    inscrireMembre($_POST);
    header("Location: login.php");
    exit;
}
?>

<form method="post">
    Nom: <input type="text" name="nom"><br>
    Email: <input type="email" name="email"><br>
    Mot de passe: <input type="password" name="mdp"><br>
    Ville: <input type="text" name="ville"><br>
    Genre: <input type="text" name="genre"><br>
    Date de naissance: <input type="date" name="date_naissance"><br>
    <button type="submit">S'inscrire</button>
</form>
<a href="login.php">Déjà inscrit ? Se connecter</a>
