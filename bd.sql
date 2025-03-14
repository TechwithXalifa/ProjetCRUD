-- Création de la base de données
CREATE DATABASE IF NOT EXISTS gestion_notes;
USE gestion_notes;

-- Table des classes (doit être créée avant les étudiants)
CREATE TABLE IF NOT EXISTS classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) UNIQUE NOT NULL
);

-- Table des étudiants
CREATE TABLE IF NOT EXISTS etudiants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    login VARCHAR(50) UNIQUE NOT NULL,
    motdepasse VARCHAR(255) NOT NULL,
    classe_id INT NOT NULL,
    FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- Table des administrateurs
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) UNIQUE NOT NULL,
    motdepasse VARCHAR(255) NOT NULL
);

-- Table des matières
CREATE TABLE IF NOT EXISTS matieres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) UNIQUE NOT NULL
);

-- Table des notes
CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT NOT NULL,
    matiere_id INT NOT NULL,
    note DECIMAL(4,2) NOT NULL,
    FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
    FOREIGN KEY (matiere_id) REFERENCES matieres(id) ON DELETE CASCADE
);

-- Insertion des classes par défaut
INSERT INTO classes (nom) VALUES 
    ('DSTI1A'), ('DSTI1B'), ('DSTI1C'), ('DSTTR1A'), ('DSTTR1B'), 
    ('DSTI2A'), ('DSTI2B'), ('DSTTR2A'), ('L3 GLSI');

-- Insertion d'un administrateur par défaut (login: admin, mot de passe: admin123)
INSERT INTO admin (login, motdepasse) VALUES ('admin', SHA1('admin123'));

-- Insertion de matières par défaut
INSERT INTO matieres (nom) VALUES 
    ('Algèbre'), ('Economie'), ('Droit'), ('Architecture des ordinateurs'), 
    ('Technologie des ordinateurs'), ('Algorithmique et Programmation'), 
    ('Analyse'), ('Mathématiques Discrètes'), ('Système d\'exploitation'), 
    ('Langage C'), ('Recherche Opérationnelle'), ('SGBD'), 
    ('Programmation Backend'), ('Programmation Orientée Objet'), 
    ('MSI'), ('Statistiques'), ('Gestion de Projet'), 
    ('Gestion de l\'entreprise'), ('Réseaux');

-- Insertion d'un étudiant test (on s'assure que la classe existe avant)
    -- INSERT INTO etudiants (nom, prenom, email, login, motdepasse, classe_id)
    -- VALUES ('DIOUF', 'Khalifa Babacar', 'khalifababacar.diouf@esp.', 'xalifa', SHA1('passer123');
