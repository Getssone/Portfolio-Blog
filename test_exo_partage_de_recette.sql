-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 29 mai 2023 à 00:36
-- Version du serveur : 5.7.24
-- Version de PHP : 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `test_exo_partage_de_recette`
--

-- --------------------------------------------------------

--
-- Structure de la table `recipes`
--

CREATE TABLE `recipes` (
  `recipe_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `recipe` text NOT NULL,
  `author` varchar(512) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `recipes`
--

INSERT INTO `recipes` (`recipe_id`, `title`, `recipe`, `author`, `is_enabled`) VALUES
(1, 'Cassoulet', 'Etape 1 : Des flageolets ! Etape 2 : Euh ...', 'contributeur@exemple.com', 1),
(6, 'Pomme d\'amour', 'Pomme plus sucre ', 'contributeur@exemple.com', 1),
(5, 'Poulet Curry', 'Suite dans le prochain épisode', 'contributeur@exemple.com', 1),
(7, 'Salade Mexicaine', 'haricot rouge + sauce piquante', 'user@exemple.com', 1),
(8, 'Rose des sables', 'Cornes Flakes avec chocolats', 'user@exemple.com', 1),
(13, 'Mumuti Brochette', 'Prendre un mumuti.\nLe caresser dans le sens du poil.\nPuis faire Cochon Pendu.', 'Getssone@mailo.com', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`recipe_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
