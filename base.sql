
CREATE DATABASE emprunt;
USE emprunt;


CREATE TABLE membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50),
    date_naissance DATE,
    genre VARCHAR(10),
    email VARCHAR(100),
    ville VARCHAR(50),
    mdp VARCHAR(255),
    image_profil VARCHAR(255)
);


CREATE TABLE categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50)
);


CREATE TABLE objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100),
    id_categorie INT,
    id_membre INT
);


CREATE TABLE images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255)
);


CREATE TABLE emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE,
    date_retour DATE

    
);


INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Alice', '2000-01-01', 'F', 'alice@mail.com', 'Tana', 'pass123', 'default.jpg'),
('Bob', '1998-05-21', 'M', 'bob@mail.com', 'Fianara', 'pass123', 'default.jpg'),
('Charlie', '1997-03-10', 'M', 'charlie@mail.com', 'Toamasina', 'pass123', 'default.jpg'),
('Dina', '2001-12-12', 'F', 'dina@mail.com', 'Majunga', 'pass123', 'default.jpg');


INSERT INTO categorie_objet (nom_categorie) VALUES
('Esthétique'),
('Bricolage'),
('Mécanique'),
('Cuisine');

-- Membres : 1 = Alice, 2 = Bob, 3 = Charlie, 4 = Dina
-- Répartis sur catégories 1 à 4

-- Objets d’Alice
INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES
('Sèche-cheveux', 1, 1),
('Fer à lisser', 1, 1),
('Marteau', 2, 1),
('Perceuse', 2, 1),
('Tournevis', 2, 1),
('Clé anglaise', 3, 1),
('Cric hydraulique', 3, 1),
('Robot pâtissier', 4, 1),
('Blender', 4, 1),
('Batteur électrique', 4, 1);

-- Objets de Bob
INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES
('Tondeuse', 1, 2),
('Épilateur', 1, 2),
('Scie sauteuse', 2, 2),
('Pistolet à colle', 2, 2),
('Visseuse', 2, 2),
('Pompe à vélo', 3, 2),
('Clé dynamométrique', 3, 2),
('Moule à gâteau', 4, 2),
('Couteau de chef', 4, 2),
('Mixeur', 4, 2);

-- Objets de Charlie
INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES
('Rasoir électrique', 1, 3),
('Brosse nettoyante visage', 1, 3),
('Niveau laser', 2, 3),
('Tournevis sans fil', 2, 3),
('Pince multiprise', 2, 3),
('Clé à molette', 3, 3),
('Compresseur', 3, 3),
('Grille-pain', 4, 3),
('Friteuse', 4, 3),
('Casserole inox', 4, 3);

-- Objets de Dina
INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES
('Fer à boucler', 1, 4),
('Pince à épiler', 1, 4),
('Scie circulaire', 2, 4),
('Ponceuse', 2, 4),
('Clé Allen', 2, 4),
('Cric manuel', 3, 4),
('Extracteur de roulement', 3, 4),
('Planche à découper', 4, 4),
('Fouet inox', 4, 4),
('Cuiseur vapeur', 4, 4);

INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2025-07-01', '2025-07-15'),   -- Bob emprunte Alice
(5, 3, '2025-07-03', '2025-07-20'),
(12, 1, '2025-07-02', '2025-07-14'),
(17, 4, '2025-07-05', '2025-07-18'),
(23, 2, '2025-07-06', '2025-07-17'),
(26, 1, '2025-07-08', '2025-07-22'),
(30, 3, '2025-07-09', '2025-07-23'),
(31, 4, '2025-07-10', '2025-07-25'),
(36, 2, '2025-07-11', '2025-07-26'),
(40, 1, '2025-07-12', '2025-07-27');
