<?php
session_start();
if (!isset($_SESSION['membre_id'])) {
    header("Location: login.php");
    exit;
}

include(__DIR__ . '/../functions/functions.php');
$conn = connectDB();

$id_membre = $_SESSION['membre_id'];
$membre = getMembreById($conn, $id_membre);
$mesObjetsParCategorie = getObjetsByMembreGroupedByCategorie($conn, $id_membre);

if (!$membre) {
    die("Membre introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Fiche membre - <?= htmlspecialchars($membre['nom']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body { background-color: #f8f9fa; color: #333; font-family: 'Segoe UI', sans-serif; }
        h2 { margin-top: 1.5rem; }
        .card { margin-bottom: 1rem; }
        .categorie-title { background-color: #e9ecef; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
  <div class="container">
    <a class="navbar-brand" href="accueil.php">🏠 Accueil</a>
    <a href="objets.php" class="btn btne ms"><---Retour</a>
    <a href="logout.php" class="btn btn-outline-danger ms-auto">Déconnexion</a>
     
  </div>
</nav>

<div class="container">

    <h1>Profil de <?= htmlspecialchars($membre['nom']) ?></h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h3>Informations personnelles</h3>
                <p><strong>Nom :</strong> <?= htmlspecialchars($membre['nom']) ?></p>
                <p><strong>Date de naissance :</strong> <?= htmlspecialchars($membre['date_naissance']) ?></p>
                <p><strong>Genre :</strong> <?= htmlspecialchars($membre['genre']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($membre['email']) ?></p>
                <p><strong>Ville :</strong> <?= htmlspecialchars($membre['ville']) ?></p>
            </div>
        </div>

        <div class="col-md-8">
            <h3>Mes objets</h3>
            <?php if (empty($mesObjetsParCategorie)): ?>
                <p>Aucun objet trouvé.</p>
            <?php else: ?>
                <?php foreach ($mesObjetsParCategorie as $categorie => $objets): ?>
                    <div class="categorie-title"><?= htmlspecialchars($categorie) ?></div>
                    <div class="row row-cols-1 row-cols-md-2 g-3 mb-4">
                        <?php foreach ($objets as $objet): ?>
                            <div class="col">
                                <div class="card shadow-sm">
                                    <?php 
                                    $img = !empty($objet['image_principale']) ? '../uploads/' . $objet['image_principale'] : '../uploads/default.jpg';
                                    ?>
                                    <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" alt="<?= htmlspecialchars($objet['nom_objet']) ?>" style="height: 150px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($objet['nom_objet']) ?></h5>
                                        <a href="fiche_objet.php?id_objet=<?= $objet['id_objet'] ?>" class="btn btn-primary btn-sm">Voir détail</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
