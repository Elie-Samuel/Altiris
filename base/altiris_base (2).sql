-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 08 juil. 2025 à 07:45
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `altiris_base`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

DROP TABLE IF EXISTS `annonces`;
CREATE TABLE IF NOT EXISTS `annonces` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `url_media` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `statut` enum('brouillon','publie','archive') DEFAULT 'brouillon',
  `categorie` varchar(50) DEFAULT NULL,
  `cree_par` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cree_par` (`cree_par`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `annonces`
--

INSERT INTO `annonces` (`id`, `titre`, `description`, `url_media`, `date_debut`, `date_fin`, `statut`, `categorie`, `cree_par`) VALUES
(1, 'gfdgfdgd', 'xcvcbdffh', 'fghfghfgh', '2025-07-04', '2025-07-06', 'brouillon', 'fghgf', 456),
(2, 'image', 'sadsd', 'https://example.com/images/pub1.jpg', '2025-07-05', '2025-07-06', 'publie', 'UNITE CENTRALE AX', 1);

-- --------------------------------------------------------

--
-- Structure de la table `image_acceuil`
--

DROP TABLE IF EXISTS `image_acceuil`;
CREATE TABLE IF NOT EXISTS `image_acceuil` (
  `image1` mediumblob NOT NULL,
  `image2` mediumblob NOT NULL,
  `image3` mediumblob NOT NULL,
  `image4` mediumblob NOT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `image5` mediumblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `parametres`
--

DROP TABLE IF EXISTS `parametres`;
CREATE TABLE IF NOT EXISTS `parametres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cle` varchar(50) NOT NULL,
  `valeur` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `service_acceuill`
--

DROP TABLE IF EXISTS `service_acceuill`;
CREATE TABLE IF NOT EXISTS `service_acceuill` (
  `image1` mediumblob NOT NULL,
  `image2` mediumblob NOT NULL,
  `image3` mediumblob NOT NULL,
  `image4` mediumblob NOT NULL,
  `id` int DEFAULT NULL,
  `text1` text NOT NULL,
  `text2` text NOT NULL,
  `text3` text NOT NULL,
  `text4` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `statistiques`
--

DROP TABLE IF EXISTS `statistiques`;
CREATE TABLE IF NOT EXISTS `statistiques` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_annonce` int DEFAULT NULL,
  `vues` int DEFAULT '0',
  `clics` int DEFAULT '0',
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_annonce` (`id_annonce`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_utilisateur` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('administrateur','redacteur','analyste') DEFAULT 'redacteur',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom_utilisateur` (`nom_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom_utilisateur`, `email`, `mot_de_passe`, `role`, `date_creation`) VALUES
(1, 'elie', 'rafanomezantsoaherindrainyelie@gmail.com', '1234', 'redacteur', '2025-07-04 13:43:21');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
