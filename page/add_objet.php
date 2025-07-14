<?php
session_start();
include(__DIR__ . '/../functions/functions.php');

if (!isset($_SESSION['membre_id'])) {
    header("Location: login.php");
    exit;
}

$conn = connectDB();
$categories = getCategories($conn);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = ajouterObjetAvecImage($_POST, $_FILES['image'], $_SESSION['membre_id']);
    if ($result['success']) {
        $success = $result['message'];
    } else {
        $error = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Ajouter un objet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">
    <h2>Ajouter un objet</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nom_objet" class="form-label">Nom de l'objet :</label>
            <input type="text" id="nom_objet" name="nom_objet" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie :</label>
            <select id="categorie" name="categorie" class="form-select" required>
                <option value="">-- Choisissez --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image principale :</label>
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.gif" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
    </form>

    <a href="objets.php" class="btn btn-link mt-3">← Retour à la liste des objets</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
