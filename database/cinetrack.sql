-- =====================================================
-- CinéTrack - Script SQL complet
-- =====================================================

CREATE DATABASE IF NOT EXISTS cinetrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cinetrack;

-- Table des films
CREATE TABLE IF NOT EXISTS films (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    synopsis TEXT,
    realisateur VARCHAR(150),
    annee INT,
    image VARCHAR(255) DEFAULT 'default.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table watchlist / wishlist
CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    film_id INT NOT NULL,
    statut ENUM('a_voir', 'vu') DEFAULT 'a_voir',
    note TINYINT DEFAULT NULL CHECK (note BETWEEN 1 AND 5),
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE,
    UNIQUE KEY unique_watchlist (utilisateur_id, film_id)
) ENGINE=InnoDB;

-- Table des avis / commentaires
CREATE TABLE IF NOT EXISTS avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    film_id INT NOT NULL,
    note TINYINT NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    date_avis TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE,
    UNIQUE KEY unique_avis (utilisateur_id, film_id)
) ENGINE=InnoDB;

-- =====================================================
-- Données de test
-- Mot de passe pour tous les comptes : password
-- Hash bcrypt de "password"
-- =====================================================
INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES
('Admin CinéTrack', 'admin@cinetrack.com', '$2y$10$TKh8H1.PfYAsokSb/Mc.0ehhYQiEnLj2x5ZTXgFPlQ.sVZpzXAF1.', 'admin'),
('Jean Dupont', 'jean@example.com', '$2y$10$TKh8H1.PfYAsokSb/Mc.0ehhYQiEnLj2x5ZTXgFPlQ.sVZpzXAF1.', 'user');

-- Films de démonstration (images = nom du fichier dans assets/img/)
INSERT INTO films (titre, genre, synopsis, realisateur, annee, image) VALUES
('Inception', 'Science-Fiction', 'Un voleur qui s''infiltre dans les rêves des gens pour voler des secrets se voit offrir une chance de rédemption : implanter une idée dans l''esprit d''une cible.', 'Christopher Nolan', 2010, 'default.jpg'),
('Interstellar', 'Science-Fiction', 'Un groupe d''explorateurs voyage à travers un trou de ver dans l''espace afin d''assurer la survie de l''humanité.', 'Christopher Nolan', 2014, 'default.jpg'),
('Le Parrain', 'Drame', 'Le patriarche vieillissant d''une dynastie du crime organisé transfère le contrôle de son empire clandestin à son fils réticent.', 'Francis Ford Coppola', 1972, 'default.jpg'),
('Pulp Fiction', 'Policier', 'Les histoires entrelacées de deux tueurs à gages, d''un boxeur, d''un gangster et de sa femme, et d''un couple de braqueurs de restaurant.', 'Quentin Tarantino', 1994, 'default.jpg'),
('The Dark Knight', 'Action', 'Batman doit faire face au chaos semé par le Joker, un criminel anarchiste qui veut plonger Gotham City dans la terreur.', 'Christopher Nolan', 2008, 'default.jpg'),
('Parasite', 'Thriller', 'La famille Ki-taek, sans emploi et vivant dans un sous-sol, s''infiltre progressivement dans la vie d''une riche famille.', 'Bong Joon-ho', 2019, 'default.jpg'),
('Avengers: Endgame', 'Action', 'Après les événements dévastateurs d''Infinity War, les Avengers s''assemblent une dernière fois pour inverser les actions de Thanos.', 'Anthony et Joe Russo', 2019, 'default.jpg'),
('Forrest Gump', 'Drame', 'Les aventures extraordinaires d''un homme simple qui traverse les grandes étapes de l''histoire américaine du XXe siècle.', 'Robert Zemeckis', 1994, 'default.jpg'),
('Matrix', 'Science-Fiction', 'Un programmeur informatique découvre que la réalité telle qu''il la connaît est une simulation créée par des machines.', 'Les Wachowski', 1999, 'default.jpg'),
('Titanic', 'Romance', 'Un jeune artiste pauvre et une aristocrate tombent amoureux à bord du paquebot Titanic lors de son voyage inaugural de 1912.', 'James Cameron', 1997, 'default.jpg');

-- Quelques avis de démonstration
INSERT INTO avis (utilisateur_id, film_id, note, commentaire) VALUES
(2, 1, 5, 'Un chef-d''œuvre absolu ! La complexité du scénario est époustouflante.'),
(2, 2, 5, 'Visuellement magnifique et émotionnellement bouleversant. Hans Zimmer au sommet.'),
(2, 3, 5, 'Le meilleur film de gangsters de tous les temps, sans aucun doute.'),
(2, 5, 5, 'Heath Ledger est simplement brillant dans le rôle du Joker.');

-- Watchlist de démonstration pour l'utilisateur 2
INSERT INTO wishlist (utilisateur_id, film_id, statut) VALUES
(2, 1, 'vu'),
(2, 2, 'vu'),
(2, 4, 'a_voir'),
(2, 6, 'a_voir');
