<?php
session_start();
if (!isset($_SESSION['membre_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
</head>
<body class="bg-light">

<header class="bg-white shadow-sm py-3 mb-4">
  <div class="container d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0 text-primary"><i class="bi bi-speedometer2 me-2"></i>Tableau de bord</h1>
    <nav>
      <a href="objets.php" class="btn btn-outline-primary me-2">
        <i class="bi bi-box-seam me-1"></i>Objets
      </a>
      <a href="logout.php" class="btn btn-outline-danger">
        <i class="bi bi-door-open me-1"></i>Déconnexion
      </a>
    </nav>
  </div>
</header>

<main class="container text-center">
  <h2 class="mb-4">Bienvenue 👋</h2>
  <p class="lead">Prêt·e à gérer vos objets ?</p>
  <a href="objets.php" class="btn btn-primary btn-lg mt-3">
    <i class="bi bi-box-seam me-2"></i> Voir les objets
  </a>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
