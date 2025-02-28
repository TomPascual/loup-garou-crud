-- Création de la base de données si elle n'existe pas
CREATE DATABASE IF NOT EXISTS loup_garou CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE loup_garou;

-- Table `utilisateurs`
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'utilisateur') DEFAULT 'utilisateur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table `cartes`
CREATE TABLE IF NOT EXISTS cartes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    photo VARCHAR(255),
    categorie ENUM('villageois', 'neutre', 'loup') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table `compositions`
CREATE TABLE IF NOT EXISTS compositions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    nombre_joueurs INT NOT NULL,
    utilisateur_id INT,
    cartes JSON, -- Pour    stocker la composition des cartes en format JSON
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table `likes`
CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    composition_id INT,
    user_id INT,
    type ENUM('like', 'dislike') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (composition_id) REFERENCES compositions(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
