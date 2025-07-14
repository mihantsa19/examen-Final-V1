<?php
session_start();
if (!isset($_SESSION['membre_id'])) {
    header("Location: login.php");
    exit;
}

include(__DIR__ . '/../functions/functions.php');
$conn = connectDB();

$cat = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
$categories = getCategories($conn);
$objets = getObjets($conn, $cat);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Objets disponibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background: #f0f2f5;
        }
        header {
            background:rgb(119, 121, 122);
            color: white;
            padding: 1rem 0;
            margin-bottom: 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        header h1 {
            margin: 0;
        }
        .filter-btns .btn {
            margin: 0 5px 10px 0;
        }
        .card:hover {
            box-shadow: 0 0 15px rgba(170, 171, 172, 0.5);
            transform: translateY(-5px);
            transition: 0.3s ease;
        }
        .badge-available {
            background-color:rgb(0, 0, 0);
            font-weight: 600;
        }
        .badge-unavailable {
            background-color:rgb(97, 79, 79);
            font-weight: 600;
        }
        .logout-btn {
            position: absolute;
            right: 20px;
            top: 15px;
        }
    </style>
</head>
<body>

<header class="text-center position-relative">
    <h1>Objets disponibles</h1>
    <a href="logout.php" class="btn btn-light btn-sm logout-btn">Se déconnecter</a>

</header>

<div class="container">

    <div class="filter-btns mb-4">
        <strong>Filtrer par catégorie :</strong>
        <?php foreach ($categories as $c): ?>
            <a href="objets.php?cat=<?= $c['id_categorie'] ?>" class="btn btn-outline-primary btn-sm <?= ($cat === (int)$c['id_categorie']) ? 'active' : '' ?>">
                <?= $c['nom_categorie'] ?>
            </a>
        <?php endforeach; ?>
        <a href="objets.php" class="btn btn-outline-secondary btn-sm <?= ($cat === null) ? 'active' : '' ?>">Tout</a>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($objets as $o): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title"><?= $o['nom_objet'] ?></h5>
                        <?php if (!empty($o['date_retour'])): ?>
                            <span class="badge badge-unavailable p-2">Emprunté jusqu'au <?= $o['date_retour'] ?></span>
                        <?php else: ?>
                            <span class="badge badge-available p-2">Disponible</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-5 d-flex justify-content-between">
        <a href="accueil.php" class="btn btn-primary">🏠 Accueil</a>
        <div>
            <a href="#" class="btn btn-outline-secondary me-2">⬅️ Précédent</a>
            <a href="#" class="btn btn-outline-secondary">➡️ Suivant</a>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-VGPSpMVo9yVjF63oCMh4yVuIPwyoLU4EkApM2gSn2ZhcIWWy2Rk5tz3Gl+RIF6tM" crossorigin="anonymous"></script>

</body>
</html>
