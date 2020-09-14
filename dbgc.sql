-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 14 sep. 2020 à 13:43
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `dbgc`
--

-- --------------------------------------------------------

--
-- Structure de la table `compteur_conge`
--

DROP TABLE IF EXISTS `compteur_conge`;
CREATE TABLE IF NOT EXISTS `compteur_conge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id_id` int(11) DEFAULT NULL,
  `acquis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attente` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_236882C3714819A0` (`type_id_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `compteur_conge`
--

INSERT INTO `compteur_conge` (`id`, `type_id_id`, `acquis`, `restant`, `attente`) VALUES
(10, 3, '7', '3', '7');

-- --------------------------------------------------------

--
-- Structure de la table `conge`
--

DROP TABLE IF EXISTS `conge`;
CREATE TABLE IF NOT EXISTS `conge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_demande` datetime NOT NULL,
  `motif` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_deb` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `date_inclus` datetime DEFAULT NULL,
  `duree` int(11) NOT NULL,
  `qte_dispo` int(11) NOT NULL,
  `start` int(11) NOT NULL,
  `end` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commentaires` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `date_verif` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `conge`
--

INSERT INTO `conge` (`id`, `date_demande`, `motif`, `date_deb`, `date_fin`, `date_inclus`, `duree`, `qte_dispo`, `start`, `end`, `commentaires`, `status`, `date_verif`) VALUES
(7, '2020-09-14 00:00:00', 'Maladie professionnel', '2020-09-15 00:00:00', '2020-09-17 00:00:00', NULL, 2, 7, 1, '0', NULL, 1, '2020-09-14 08:56:30'),
(8, '2020-09-14 00:00:00', 'Maladie professionnel', '2020-09-14 00:00:00', '2020-09-16 00:00:00', NULL, 2, 5, 1, '0', 'néant', 0, '2020-09-14 08:58:55');

-- --------------------------------------------------------

--
-- Structure de la table `conge_user`
--

DROP TABLE IF EXISTS `conge_user`;
CREATE TABLE IF NOT EXISTS `conge_user` (
  `conge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`conge_id`,`user_id`),
  KEY `IDX_9F5D9634CAAC9A59` (`conge_id`),
  KEY `IDX_9F5D9634A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `conge_user`
--

INSERT INTO `conge_user` (`conge_id`, `user_id`) VALUES
(7, 3);

-- --------------------------------------------------------

--
-- Structure de la table `gestion_conge`
--

DROP TABLE IF EXISTS `gestion_conge`;
CREATE TABLE IF NOT EXISTS `gestion_conge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `valeur` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `de` datetime NOT NULL,
  `date_inclus` datetime DEFAULT NULL,
  `commentaires` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_63C7FCABA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `gestion_conge`
--

INSERT INTO `gestion_conge` (`id`, `user_id`, `valeur`, `date`, `de`, `date_inclus`, `commentaires`) VALUES
(10, 5, 7, '2020-09-14 00:00:00', '2020-09-21 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `gestion_conge_compteur_conge`
--

DROP TABLE IF EXISTS `gestion_conge_compteur_conge`;
CREATE TABLE IF NOT EXISTS `gestion_conge_compteur_conge` (
  `gestion_conge_id` int(11) NOT NULL,
  `compteur_conge_id` int(11) NOT NULL,
  PRIMARY KEY (`gestion_conge_id`,`compteur_conge_id`),
  KEY `IDX_9B167601E658E70` (`gestion_conge_id`),
  KEY `IDX_9B167601BF74FEA9` (`compteur_conge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `gestion_conge_compteur_conge`
--

INSERT INTO `gestion_conge_compteur_conge` (`gestion_conge_id`, `compteur_conge_id`) VALUES
(10, 10);

-- --------------------------------------------------------

--
-- Structure de la table `parameter`
--

DROP TABLE IF EXISTS `parameter`;
CREATE TABLE IF NOT EXISTS `parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `parameter`
--

INSERT INTO `parameter` (`id`, `name`, `code`, `value`) VALUES
(2, 'EMAIL', 'PARAMETER.EMAIL', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id`, `name`) VALUES
(4, 'RH'),
(5, 'Financier'),
(6, 'DCVD');

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type`
--

INSERT INTO `type` (`id`, `name`) VALUES
(3, 'Maladie professionnel');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) DEFAULT NULL,
  `matricule` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  KEY `IDX_8D93D649ED5CA9E6` (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `service_id`, `matricule`, `image`, `username`, `email`, `roles`, `password`, `reset_token`, `name`, `lastname`, `tel`) VALUES
(3, 4, '437AEF', 'https://cdn4.iconfinder.com/data/icons/e-commerce-icon-set/48/Username_2-512.png', 'root', 'admin@default.mg', '[\"ROLE_SUPER_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$U2l6VlFwVHFjYlJvaEQ5dg$69qW4MlzorX08VGxQRGHBUPKmGHzT8wvRciTnSQmL9A', NULL, NULL, NULL, NULL),
(4, 5, '4B9B79', 'https://cdn4.iconfinder.com/data/icons/e-commerce-icon-set/48/Username_2-512.png', 'Ravaka', 'ravaka@gmail.com', '[\"ROLE_EMPLOYER\"]', '$argon2id$v=19$m=65536,t=4,p=1$eVhJTkZJb0U1aWNhRTZxUg$aC77CkVnymUjyuPK/68nSgFxR2gWohrpz2S4yA+5HtY', NULL, NULL, NULL, NULL),
(5, 6, '2FF1DD', 'https://cdn4.iconfinder.com/data/icons/e-commerce-icon-set/48/Username_2-512.png', 'Nina', 'nina65@gmail.com', '[\"ROLE_EMPLOYER\"]', '$argon2id$v=19$m=65536,t=4,p=1$clRoTUkwcm9RdjltZENvdA$nHiPDq5W9tF7K0D+PVQFni+odQXDp3ITdaK7JAxVNSQ', NULL, 'Razanakoto', 'Nina Ethienne', '0329678654'),
(6, 5, 'C7DCBC', 'https://cdn4.iconfinder.com/data/icons/e-commerce-icon-set/48/Username_2-512.png', 'Toto', 'agent@gmail.com', '[\"ROLE_EMPLOYER\"]', '$argon2id$v=19$m=65536,t=4,p=1$RGlSTW1obFp1Mjg4VHJ2NA$Fj46LKHcjFPm3ufgal8NPzhxhLzJXT6lounCuXW9b44', NULL, 'Razanakoto', 'Toto Raz', '0329678654');

-- --------------------------------------------------------

--
-- Structure de la table `user_conge`
--

DROP TABLE IF EXISTS `user_conge`;
CREATE TABLE IF NOT EXISTS `user_conge` (
  `user_id` int(11) NOT NULL,
  `conge_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`conge_id`),
  KEY `IDX_CC1A6810A76ED395` (`user_id`),
  KEY `IDX_CC1A6810CAAC9A59` (`conge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_conge`
--

INSERT INTO `user_conge` (`user_id`, `conge_id`) VALUES
(5, 7),
(5, 8);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `compteur_conge`
--
ALTER TABLE `compteur_conge`
  ADD CONSTRAINT `FK_236882C3714819A0` FOREIGN KEY (`type_id_id`) REFERENCES `type` (`id`);

--
-- Contraintes pour la table `conge_user`
--
ALTER TABLE `conge_user`
  ADD CONSTRAINT `FK_9F5D9634A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_9F5D9634CAAC9A59` FOREIGN KEY (`conge_id`) REFERENCES `conge` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `gestion_conge`
--
ALTER TABLE `gestion_conge`
  ADD CONSTRAINT `FK_63C7FCABA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `gestion_conge_compteur_conge`
--
ALTER TABLE `gestion_conge_compteur_conge`
  ADD CONSTRAINT `FK_9B167601BF74FEA9` FOREIGN KEY (`compteur_conge_id`) REFERENCES `compteur_conge` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_9B167601E658E70` FOREIGN KEY (`gestion_conge_id`) REFERENCES `gestion_conge` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649ED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Contraintes pour la table `user_conge`
--
ALTER TABLE `user_conge`
  ADD CONSTRAINT `FK_CC1A6810A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_CC1A6810CAAC9A59` FOREIGN KEY (`conge_id`) REFERENCES `conge` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
