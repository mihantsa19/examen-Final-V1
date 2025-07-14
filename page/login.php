<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include(__DIR__ . '/../functions/functions.php');

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background-color: #1e1e1e;
            color: #e0e0e0;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            background-color: #2c2c2c;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255,255,255,0.05);
            margin-top: 5%;
        }

        h2 {
            color: #cccccc;
        }

        label {
            color: #bbbbbb;
        }

        .form-control {
            background-color: #3a3a3a;
            border: 1px solid #555;
            color: #f0f0f0;
        }

        .form-control:focus {
            background-color: #444;
            border-color: #888;
            color: #fff;
            outline: none;
        }

        .btn-primary {
            background-color: #5a5a5a;
            border-color: #6e6e6e;
        }

        .btn-primary:hover {
            background-color: #777;
            border-color: #999;
        }

        .alert-danger {
            background-color: #661111;
            color: #f88;
            border: 1px solid #992222;
        }

        a {
            color: #a0a0a0;
        }

        a:hover {
            color: #fff;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Se connecter</h2>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="mdp" class="form-label">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    </form>
    <p class="mt-3 text-center">Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
