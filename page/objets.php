<?php
session_start();
if (!isset($_SESSION['membre_id'])) {
    header("Location: login.php");
    exit;
}

include(__DIR__ . '/../functions/functions.php');
$conn = connectDB();

$cat = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
$nom = isset($_GET['nom']) ? trim($_GET['nom']) : '';
$dispo = isset($_GET['dispo']) ? true : false;

$categories = getCategories($conn);
$objets = searchObjets($conn, $cat, $nom, $dispo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Objets disponibles</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
      color: #212529;
    }
    .navbar {
      background-color: #dee2e6;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }
    .navbar-brand {
      font-weight: bold;
      color: #333;
    }
    .navbar .btn {
      font-size: 0.9rem;
    }
    .filter-section {
      background-color: #e9ecef;
      padding: 1rem;
      border-radius: 8px;
    }
    .card {
      border: none;
      transition: transform 0.2s;
      background-color: #fff;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .card img {
      object-fit: cover;
      height: 180px;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
    }
    .badge-dispo {
      background-color: #198754;
    }
    .badge-indispo {
      background-color: #dc3545;
    }
    footer {
      text-align: center;
      margin-top: 40px;
      color: #888;
      font-size: 0.85rem;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light">
  <div class="container-fluid">
    <h1><a class="navbar-brand" href="accueil.php">📦 Gestion Objets</a><h1>
    <div class="d-flex gap-2 align-items-center">
      <a href="fiche_membre.php" class="btn btn-outline-primary btn-sm">👤 Mon profil</a>
      <a href="add_objet.php" class="btn btn-outline-success btn-sm">➕ Ajouter</a>
      <a href="logout.php" class="btn btn-outline-danger btn-sm">🚪 Déconnexion</a>
      <a href=".php" class="btn btn-outline-secondary btn-sm">🏠 Accueil</a>
    </div>
  </div>
</nav>




<div class="container mt-4">
  <h3 class="mb-4">🔍 Rechercher un objet</h3>

  <!-- Filtres -->
  <form method="get" class="row g-3 filter-section mb-5">
    <div class="col-md-4">
      <select name="cat" class="form-select">
        <option value="">-- Toutes les catégories --</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id_categorie'] ?>" <?= $cat == $c['id_categorie'] ? 'selected' : '' ?>>
            <?= $c['nom_categorie'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <input type="text" name="nom" class="form-control" placeholder="Nom de l’objet..." value="<?= $nom ?>">
    </div>
    <div class="col-md-2 form-check d-flex align-items-center">
      <input class="form-check-input me-2" type="checkbox" name="dispo" id="dispo" <?= $dispo ? 'checked' : '' ?>>
      <label class="form-check-label" for="dispo">Seulement disponibles</label>
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary w-100" type="submit">🔎 Rechercher</button>
    </div>
  </form>

  
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($objets as $o): ?>
      <?php $img = !empty($o['image_principale']) ? '../uploads/' . $o['image_principale'] : '../uploads/default.jpg'; ?>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <img src="<?= $img ?>" class="card-img-top" alt="<?= $o['nom_objet'] ?>">
          <div class="card-body">
            <h5 class="card-title">
              <a href="fiche_objet.php?id_objet=<?= $o['id_objet'] ?>" class="text-decoration-none text-primary">
                <?= $o['nom_objet'] ?>
              </a>
            </h5>
            <?php if (!empty($o['date_retour'])): ?>
              <span class="badge badge-indispo">❌ Indisponible jusqu’au <?= $o['date_retour'] ?></span>
            <?php else: ?>
              <span class="badge badge-dispo">✅ Disponible</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<footer class="mt-5 mb-4">
  © <?= date('Y') ?> - Gestion des objets · Meilleur ami
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
