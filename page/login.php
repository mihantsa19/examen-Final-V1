<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include(__DIR__ . '/../functions/functions.php');

$error = '';
if (isset($_POST['email'])) {
    $user = connecterMembre($_POST['email'], $_POST['mdp']);
    if ($user) {
        $_SESSION['membre_id'] = $user['id_membre'];
        header("Location: accueil.php");
        exit;
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-container {
      max-width: 420px;
      margin: 60px auto;
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0,0,0,0.05);
    }
    .login-title {
      font-weight: bold;
      text-align: center;
      margin-bottom: 1.5rem;
      color: #343a40;
    }
    .btn-primary {
      background-color: #0d6efd;
      border: none;
    }
    .btn-primary:hover {
      background-color: #0b5ed7;
    }
    .form-label {
      font-weight: 500;
    }
    .small-link {
      text-align: center;
      margin-top: 1rem;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="login-container">
    <h2 class="login-title">🔐 Connexion</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label for="email" class="form-label">Adresse email</label>
        <input type="email" class="form-control" id="email" name="email" required placeholder="ex: nom@exemple.com">
      </div>
      <div class="mb-3">
        <label for="mdp" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="mdp" name="mdp" required placeholder="••••••••">
      </div>
      <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    </form>

    <div class="small-link">
      <p class="mt-3">Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
