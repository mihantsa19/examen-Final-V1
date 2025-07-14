<?php
session_start();
if (!isset($_SESSION['membre_id'])) {
    header("Location: login.php");
    exit;
}

include(__DIR__ . '/../functions/functions.php');
$conn = connectDB();

$id_objet = isset($_GET['id_objet']) ? (int)$_GET['id_objet'] : 0;

$objet = getObjetById($conn, $id_objet);
$images = getImagesByObjet($conn, $id_objet);
$historique = getHistoriqueEmprunts($conn, $id_objet);

if (!$objet) {
    die("Objet introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?= $objet['nom_objet'] ?> | Fiche objet</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
      color: #212529;
    }
    .card {
      background-color: #ffffff;
      border: none;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .card img {
      object-fit: cover;
      height: 240px;
      border-radius: 8px;
    }
    .thumbnail img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border: 1px solid #dee2e6;
      border-radius: 4px;
    }
    .badge-success {
      background-color: #198754;
    }
    .badge-danger {
      background-color: #dc3545;
    }
    .btn-back {
      margin-bottom: 1.5rem;
    }
    table thead {
      background-color: #dee2e6;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <a href="objets.php" class="btn btn-outline-secondary btn-sm btn-back">← Retour à la liste</a>

  <h2 class="mb-4"><?= $objet['nom_objet'] ?></h2>

  <div class="row g-4">
    <!-- Images -->
    <div class="col-md-6">
      <div class="card p-3">
        <?php if (!empty($images)) : ?>
          <img src="../uploads/<?= $images[0]['nom_image'] ?>" class="w-100 mb-3" alt="Image principale">
          <?php if (count($images) > 1): ?>
            <div class="d-flex flex-wrap gap-2 thumbnail">
              <?php foreach ($images as $key => $img): if ($key === 0) continue; ?>
                <img src="../uploads/<?= $img['nom_image'] ?>" alt="Image supplémentaire">
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        <?php else : ?>
          <img src="../uploads/default.jpg" class="w-100" alt="Image par défaut">
        <?php endif; ?>
      </div>
    </div>

    <!-- Infos objet -->
    <div class="col-md-6">
      <div class="card p-4">
        <h4>Détails</h4>
        <p><strong>Catégorie :</strong> <?= $objet['nom_categorie'] ?></p>
        <p><strong>Disponibilité :</strong>
          <?php if (!empty($objet['date_retour'])): ?>
            <span class="badge badge-danger">Emprunté jusqu'au <?= $objet['date_retour'] ?></span>
          <?php else: ?>
            <span class="badge badge-success">Disponible</span>
          <?php endif; ?>
        </p>
      </div>

      <!-- Historique -->
      <div class="card p-4 mt-4">
        <h5>Historique des emprunts</h5>
        <?php if (!empty($historique)): ?>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm mt-3">
              <thead>
                <tr>
                  <th>Nom du membre</th>
                  <th>Date emprunt</th>
                  <th>Date retour</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($historique as $h): ?>
                  <tr>
                    <td><?= $h['nom'] ?></td>
                    <td><?= $h['date_emprunt'] ?></td>
                    <td><?= $h['date_retour'] ?? '-' ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="text-muted">Aucun emprunt enregistré pour cet objet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
