<?php
include 'functions.php';
session_start();

if (isset($_POST['email'])) {
    $user = connecterMembre($_POST['email'], $_POST['mdp']);
    if ($user) {
        $_SESSION['membre_id'] = $user['id_membre'];
        header("Location: accueil.php");
        exit;
    } else {
        echo "Identifiants incorrects.";
    }
}
?>

<form method="post">
    Email: <input type="email" name="email"><br>
    Mot de passe: <input type="password" name="mdp"><br>
    <button type="submit">Se connecter</button>
</form>
<a href="register.php">Pas encore inscrit ?</a>
