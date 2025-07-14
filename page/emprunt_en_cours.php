<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['membre_id'])) {
    header("Location: login.php");
    exit;
}

include(__DIR__ . '/../functions/functions.php');
$conn = connectDB();

$emprunts = getAllEmpruntsEnCours($conn); // fonction à créer dans functions.php
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Emprunts en cours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .badge-good {
            background-color: #198754;
            color: white;
        }
        .badge-broken {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-4">

    <h1 class="mb-4">Emprunts en cours</h1>
    <a href="fiche_membre.php" class="btn-outline-success btn-sm">Retour</a>

    <?php if (empty($emprunts)): ?>
        <p>Aucun emprunt en cours.</p>
    <?php else: ?>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Membre</th>
                    <th>Objet</th>
                    <th>Date emprunt</th>
                    <th>Date retour prévue</th>
                    <th>État actuel</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($emprunts as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['nom_membre']) ?></td>
                    <td><?= htmlspecialchars($e['nom_objet']) ?></td>
                    <td><?= htmlspecialchars($e['date_emprunt']) ?></td>
                    <td><?= htmlspecialchars($e['date_retour_prevue']) ?></td>
                    <td>
                        <span class="badge <?= $e['etat'] === 'abime' ? 'badge-broken' : 'badge-good' ?>">
                            <?= ucfirst($e['etat']) ?>
                        </span>
                    </td>
                    <td>
                        <form action="retourner_objet.php" method="post" class="d-flex align-items-center gap-2">
                            <input type="hidden" name="id_emprunt" value="<?= $e['id_emprunt'] ?>">
                            <select name="etat_retour" class="form-select form-select-sm" required>
                                <option value="" disabled selected>Choisir état</option>
                                <option value="bon">Bon état</option>
                                <option value="abime">Abîmé</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-success">Retourner</button>

                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
