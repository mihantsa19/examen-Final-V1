<?php
include 'db.php';

function connectDB() {
    $conn = mysqli_connect("localhost", "root", "", "emprunt");
    if (!$conn) {
        die("Erreur de connexion");
    }
    return $conn;
}

function inscrireMembre($data) {
    $conn = connectDB();

    $nom = trim($data['nom']);
    $email = trim($data['email']);
    $mdp = trim($data['mdp']);
    $ville = trim($data['ville']);
    $genre = trim($data['genre']);
    $date_naissance = trim($data['date_naissance']);

    if (empty($nom) || empty($email) || empty($mdp) || empty($ville) || empty($genre) || empty($date_naissance)) {
        die("Tous les champs sont obligatoires.");
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_naissance)) {
        die("Date invalide. Format attendu : AAAA-MM-JJ.");
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp, image_profil)
                                   VALUES (?, ?, ?, ?, ?, ?, 'default.jpg')");
    mysqli_stmt_bind_param($stmt, "ssssss", $nom, $date_naissance, $genre, $email, $ville, $mdp);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function connecterMembre($email, $mdp) {
    $conn = connectDB();
    $sql = "SELECT * FROM membre WHERE email='$email' AND mdp='$mdp'";
    $res = mysqli_query($conn, $sql);
    if (mysqli_num_rows($res) == 1) {
        return mysqli_fetch_assoc($res);
    } else {
        return false;
    }
}

function getCategories($conn) {
    return mysqli_query($conn, "SELECT * FROM categorie_objet");
}

function getObjets($conn, $id_categorie = null) {
    $dateNow = date('Y-m-d');
    if ($id_categorie) {
        $sql = "SELECT o.*, e.date_retour FROM objet o
                LEFT JOIN emprunt e ON o.id_objet = e.id_objet AND e.date_retour >= '$dateNow'
                WHERE o.id_categorie = ?
                ORDER BY o.nom_objet";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_categorie);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    } else {
        $sql = "SELECT o.*, e.date_retour FROM objet o
                LEFT JOIN emprunt e ON o.id_objet = e.id_objet AND e.date_retour >= '$dateNow'
                ORDER BY o.nom_objet";
        return mysqli_query($conn, $sql);
    }
}
function ajouterObjetAvecImage($postData, $fileData, $id_membre) {
    $conn = connectDB();

    $nom_objet = trim($postData['nom_objet'] ?? '');
    $id_categorie = (int)($postData['categorie'] ?? 0);

    if (empty($nom_objet) || $id_categorie === 0) {
        return ['success' => false, 'message' => 'Le nom de l\'objet et la catégorie sont obligatoires.'];
    }

    // Insert dans la table objet
    $stmt = mysqli_prepare($conn, "INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sii", $nom_objet, $id_categorie, $id_membre);
    if (!mysqli_stmt_execute($stmt)) {
        return ['success' => false, 'message' => 'Erreur lors de l\'ajout de l\'objet.'];
    }
    $id_objet = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // Gestion de l'image
    if (isset($fileData) && $fileData['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $originalName = basename($fileData['name']);
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($extension, $allowed)) {
            return ['success' => false, 'message' => 'Type d\'image non autorisé.'];
        }

        $newFileName = uniqid('img_') . '.' . $extension;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($fileData['tmp_name'], $destination)) {
         
            $stmtImg = mysqli_prepare($conn, "INSERT INTO images_objet (id_objet, nom_image) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmtImg, "is", $id_objet, $newFileName);
            if (!mysqli_stmt_execute($stmtImg)) {
                return ['success' => false, 'message' => 'Erreur lors de l\'enregistrement de l\'image.'];
            }
            mysqli_stmt_close($stmtImg);
        } else {
            return ['success' => false, 'message' => 'Erreur lors du téléchargement de l\'image.'];
        }
    } else {
        
    }

    mysqli_close($conn);
    return ['success' => true, 'message' => 'Objet ajouté avec succès !'];
}
function getObjetById($conn, $id_objet) {
    $sql = "SELECT o.*, c.nom_categorie
            FROM objet o
            JOIN categorie_objet c ON o.id_categorie = c.id_categorie
            WHERE o.id_objet = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_objet);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}
function getHistoriqueEmprunts($conn, $id_objet) {
    $sql = "SELECT e.date_emprunt, e.date_retour, m.nom
            FROM emprunt e
            JOIN membre m ON e.id_membre = m.id_membre
            WHERE e.id_objet = ?
            ORDER BY e.date_emprunt DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_objet);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
function getImagesByObjet($conn, $id_objet) {
    $sql = "SELECT nom_image FROM images_objet WHERE id_objet = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_objet);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
function searchObjets($conn, $categorie = null, $nom = '', $disponible = false) {
    $sql = "SELECT o.*, e.date_retour, c.nom_categorie
            FROM objet o
            LEFT JOIN emprunt e ON o.id_objet = e.id_objet AND (e.date_retour IS NULL OR e.date_retour >= CURDATE())
            INNER JOIN categorie_objet c ON o.id_categorie = c.id_categorie
            WHERE 1";

    $params = [];
    if ($categorie) {
        $sql .= " AND o.id_categorie = ?";
        $params[] = $categorie;
    }
    if (!empty($nom)) {
        $sql .= " AND o.nom_objet LIKE ?";
        $params[] = '%' . $nom . '%';
    }
    if ($disponible) {
        $sql .= " AND e.date_retour IS NULL";
    }

    $stmt = mysqli_prepare($conn, $sql);
    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
function getMembreById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM membre WHERE id_membre = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getObjetsByMembreGroupedByCategorie($conn, $id_membre) {
    $sql = "SELECT c.nom_categorie, o.* 
            FROM objet o
            JOIN categorie_objet c ON o.id_categorie = c.id_categorie
            WHERE o.id_membre = ?
            ORDER BY c.nom_categorie, o.nom_objet";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_membre);
    $stmt->execute();
    $result = $stmt->get_result();

    $grouped = [];
    while ($row = $result->fetch_assoc()) {
        $grouped[$row['nom_categorie']][] = $row;
    }
    return $grouped;
}



?>
