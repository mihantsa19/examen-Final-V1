<?php
include 'functions.php';
session_start();

if (!isset($_SESSION['membre_id'])) {
    header("Location: login.php");
    exit;
}

$conn = connectDB();
$cat = isset($_GET['cat']) ? $_GET['cat'] : null;

$categories = getCategories($conn);
$objets = getObjets($conn, $cat);
?>

<h2>Objets disponibles</h2>


<p>Catégories :</p>
<?php while ($c = mysqli_fetch_assoc($categories)) { ?>
    <a href="objets.php?cat=<?= $c['id_categorie'] ?>"><?= $c['nom_categorie'] ?></a> |
<?php } ?>
<a href="objets.php">Tout</a>

<hr>


<ul>
<?php while ($o = mysqli_fetch_assoc($objets)) { ?>
    <li><?= $o['nom_objet'] ?></li>
<?php } ?>
</ul>


<a href="accueil.php">🏠 Accueil</a> |
<a href="#">⬅️ Précédent</a> |
<a href="#">➡️ Suivant</a>
