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

$message = '';
if (isset($_GET['message']) && $_GET['message'] === 'emprunt_ok') {
    $message = "✅ Emprunt enregistré avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Liste des objets</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
      color: #333;
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background-color: #dee2e6;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .navbar-brand {
      font-weight: bold;
    }
    .card {
      background-color: #fff;
      border: none;
      transition: transform 0.3s ease;
    }
    .card:hover {
      transform: scale(1.02);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .card img {
      object-fit: cover;
      height: 200px;
      width: 100%;
      border-radius: 6px 6px 0 0;
    }
    .badge-dispo {
      background-color: #198754;
    }
    .badge-indispo {
      background-color: #dc3545;
    }
    .filter-section {
      background-color: #e9ecef;
      padding: 1rem;
      border-radius: 8px;
      margin-top: 5rem;
      margin-bottom: 2rem;
    }
    .input-group > input {
      max-width: 120px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="accueil.php">📦 Gestion Objets</a>
    <div class="d-flex gap-2">
      <a href="add_objet.php" class="btn btn-outline-success btn-sm">➕ Ajouter</a>
      <a href="logout.php" class="btn btn-outline-danger btn-sm">🚪 Déconnexion</a>
      <a href="index.php" class="btn btn-outline btn-sm">🏠 Accueil</a>
      <a href="fiche_membre.php" class="btn-outline-success btn-sm">Mon profil</a>
    </div>
  </div>
</nav>

<div class="container">

  <?php if ($message): ?>
    <div class="alert alert-success mt-4"><?= $message ?></div>
  <?php endif; ?>

  <h2 class="mt-4 mb-3">🔍 Liste des objets</h2>

  <form method="get" class="row g-3 filter-section">
    <div class="col-md-4">
      <select name="cat" class="form-select">
        <option value="">-- Catégorie --</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id_categorie'] ?>" <?= $cat == $c['id_categorie'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['nom_categorie']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <input type="text" name="nom" class="form-control" placeholder="Nom de l’objet..." value="<?= htmlspecialchars($nom) ?>">
    </div>
    <div class="col-md-2 form-check mt-2">
      <input class="form-check-input" type="checkbox" name="dispo" id="dispo" <?= $dispo ? 'checked' : '' ?>>
      <label class="form-check-label" for="dispo">Disponible</label>
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary w-100" type="submit">🔎 Rechercher</button>
    </div>
  </form>

  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($objets as $o): ?>
      <?php 
        $img = !empty($o['image_principale']) ? '../uploads/' . $o['image_principale'] : '../uploads/default.jpg';
        $dateRetour = !empty($o['date_retour']) ? $o['date_retour'] : null;
      ?>
      <div class="col">
        <div class="card h-100 d-flex flex-column">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($o['nom_objet']) ?>" class="card-img-top">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">
              <a href="fiche_objet.php?id_objet=<?= $o['id_objet'] ?>" class="text-decoration-none text-primary fw-bold">
                <?= htmlspecialchars($o['nom_objet']) ?>
              </a>
            </h5>
            
            <?php if ($dateRetour): ?>
              <p class="text-danger mb-2">Indisponible jusqu’au <strong><?= date('d F Y', strtotime($dateRetour)) ?></strong></p>
              <button class="btn btn-secondary" disabled>Emprunter</button>
            <?php else: ?>
              <p class="text-success mb-2">Disponible maintenant</p>
              <form action="emprunter.php" method="post" class="mt-auto">
                <input type="hidden" name="id_objet" value="<?= $o['id_objet'] ?>">
                <div class="input-group mb-0">
                  <input type="number" name="duree_jours" class="form-control" min="1" max="30" placeholder="Durée (jours)" required>
                  <button type="submit" class="btn btn-primary">Emprunter</button>
                </div>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
