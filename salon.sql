-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 08 sep. 2020 à 20:10
-- Version du serveur :  5.7.24
-- Version de PHP : 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `salon`
--

-- --------------------------------------------------------

--
-- Structure de la table `administration`
--

CREATE TABLE `administration` (
  `num_admin` int(11) NOT NULL,
  `nom_admin` varchar(50) NOT NULL,
  `pass_admin` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `administration`
--

INSERT INTO `administration` (`num_admin`, `nom_admin`, `pass_admin`) VALUES
(3, 'Admin', '@dmin');

-- --------------------------------------------------------

--
-- Structure de la table `coiffeurs`
--

CREATE TABLE `coiffeurs` (
  `num_coif` int(11) NOT NULL,
  `nom_coif` varchar(255) NOT NULL,
  `num_tel` char(8) NOT NULL,
  `sexe_coif` varchar(50) NOT NULL,
  `disponible` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `coiffeurs`
--

INSERT INTO `coiffeurs` (`num_coif`, `nom_coif`, `num_tel`, `sexe_coif`, `disponible`) VALUES
(1, 'Carine', '98023036', 'Féminin', 'oui'),
(2, 'Jeffrey', '90000000', 'Masculin', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `num_msg` int(11) NOT NULL,
  `nom_emetteur` varchar(50) NOT NULL,
  `prenoms_emetteur` varchar(70) NOT NULL,
  `objet` text NOT NULL,
  `num_Tel` char(8) NOT NULL,
  `adr_mail` varchar(150) NOT NULL,
  `message` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`num_msg`, `nom_emetteur`, `prenoms_emetteur`, `objet`, `num_Tel`, `adr_mail`, `message`) VALUES
(1, 'Bernard', 'Aleck', 'Encouragement', '98023036', 'aleckbernard9@gmail.com', 'What\'s up la famille! Juste pour vous dire bonjour et vous encouragez;)'),
(2, 'Walker', 'Winny', 'Plainte', '90023809', 'iamwinner@gmail.com', 'Bonjour! Je suis ne suis pas satisfait de vos prestation, vous m\'avez rendu chauve avec votre ...euh..je ne sais plus ..mais bref je rÃ©clame des dÃ©domagements (^_^)');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `num_reserv` int(11) NOT NULL,
  `date_reserv` date NOT NULL,
  `heure_reserv` time NOT NULL,
  `num_user` int(11) NOT NULL,
  `num_coif` int(11) NOT NULL,
  `num_type_service` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`num_reserv`, `date_reserv`, `heure_reserv`, `num_user`, `num_coif`, `num_type_service`) VALUES
(1, '2020-09-10', '19:09:00', 1, 1, 8),
(3, '2020-09-08', '15:20:00', 1, 1, 2),
(4, '2020-09-08', '13:47:00', 1, 2, 8);

-- --------------------------------------------------------

--
-- Structure de la table `type_service`
--

CREATE TABLE `type_service` (
  `num_type` int(11) NOT NULL,
  `libelle` varchar(255) NOT NULL DEFAULT '0',
  `prix` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `type_service`
--

INSERT INTO `type_service` (`num_type`, `libelle`, `prix`) VALUES
(2, 'Coiffure pour Homme', 1200),
(3, 'Coiffure pour Enfant', 800),
(4, 'Coiffure pour Dame', 1200),
(5, 'Lavage de cheuveux', 2500),
(6, 'Coloriation Homme', 2000),
(7, 'Coloriation sans Amoniaque', 1500),
(8, 'Brushing', 1000),
(9, 'Lissage et Défrisage Homme', 5000),
(10, 'Coiffure de mariage', 5500),
(11, 'Soins Capillaire', 6500),
(12, 'Coiffure pour Dame', 1300);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `num_user` int(11) NOT NULL,
  `nom_user` varchar(50) NOT NULL,
  `prenoms_user` varchar(70) NOT NULL,
  `numTel` char(8) NOT NULL,
  `adr_mail` varchar(100) NOT NULL,
  `mot_passe` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`num_user`, `nom_user`, `prenoms_user`, `numTel`, `adr_mail`, `mot_passe`) VALUES
(1, 'Aleck', 'Bernard', '98023036', 'aleckbernard9@gmail.com', '3a9a0a9c653a34d94c56353e8db5ff5bce31510c'),
(4, 'winner', 'iam', '90023809', 'iamwinner422@gmail.com', '3a9a0a9c653a34d94c56353e8db5ff5bce31510c'),
(5, 'Dzegle', 'Line ', '97685753', 'linedzegle@gmail.com', 'f997c83f115ee5d9c461d43c5d39342b22d09b9b'),
(6, 'Corporation', 'Weazel ', '22222222', 'infos@weazel.com', 'cd9f7f0089b6b072573118cb051ee1d09865d49f'),
(7, 'Sugar', 'Dady', '90032501', 'vincent2kdzegle@gmail.com', 'aea6488304412db2d5745a5d198c7621ace31b46'),
(8, 'Sugar', 'Momy', '99003644', 'momysugar@gmail.com', '34c81a57d928d648759d4ca6483bc2c821c012bc'),
(9, 'john', 'john', '98989898', 'john@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administration`
--
ALTER TABLE `administration`
  ADD PRIMARY KEY (`num_admin`);

--
-- Index pour la table `coiffeurs`
--
ALTER TABLE `coiffeurs`
  ADD PRIMARY KEY (`num_coif`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`num_msg`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`num_reserv`),
  ADD KEY `FK_reservations_utilisateurs` (`num_user`),
  ADD KEY `FK_reservations_coiffeurs` (`num_coif`),
  ADD KEY `FK_reservations_type_service` (`num_type_service`);

--
-- Index pour la table `type_service`
--
ALTER TABLE `type_service`
  ADD PRIMARY KEY (`num_type`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`num_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administration`
--
ALTER TABLE `administration`
  MODIFY `num_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `coiffeurs`
--
ALTER TABLE `coiffeurs`
  MODIFY `num_coif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `num_msg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `num_reserv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `type_service`
--
ALTER TABLE `type_service`
  MODIFY `num_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `num_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `FK_reservations_coiffeurs` FOREIGN KEY (`num_coif`) REFERENCES `coiffeurs` (`num_coif`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_reservations_type_service` FOREIGN KEY (`num_type_service`) REFERENCES `type_service` (`num_type`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_reservations_utilisateurs` FOREIGN KEY (`num_user`) REFERENCES `utilisateurs` (`num_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
