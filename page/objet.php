<?php
include 'functions.php';

$cat = isset($_GET['cat']) ? $_GET['cat'] : null;
$categories = getCategories($conn);
$objets = getObjets($conn, $cat);
?>

<html>
<head>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Liste des objets</h2>


<p>Filtrer par catégorie :</p>
<?php while ($c = mysqli_fetch_assoc($categories)) { ?>
    <a href="objets.php?cat=<?= $c['id_categorie'] ?>"><?= $c['nom_categorie'] ?></a> |
<?php } ?>
<a href="objets.php">Tout afficher</a>

<hr>


<ul>
<?php while ($o = mysqli_fetch_assoc($objets)) { ?>
    <li><?= $o['nom_objet'] ?> (catégorie <?= $o['id_categorie'] ?>)</li>
<?php } ?>
</ul>

<hr>


<a href="index.php">🏠 Accueil</a> |
<a href="objets.php">⬅️ Page précédente</a> |
<a href="objets.php">➡️ Page suivante</a>

</body>
</html>
