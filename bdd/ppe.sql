-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 17 nov. 2019 à 18:06
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `ppe`
--

-- --------------------------------------------------------

--
-- Structure de la table `agence`
--

DROP TABLE IF EXISTS `agence`;
CREATE TABLE IF NOT EXISTS `agence` (
  `numero_agence` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `code_region` int(11) NOT NULL,
  PRIMARY KEY (`numero_agence`),
  KEY `FK1` (`code_region`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `agence`
--

INSERT INTO `agence` (`numero_agence`, `nom`, `adresse`, `telephone`, `fax`, `code_region`) VALUES
(1, 'Cashcash Lille ', '35 rue de la place', '0602588255', '0323258230', 1);

-- --------------------------------------------------------

--
-- Structure de la table `assistant`
--

DROP TABLE IF EXISTS `assistant`;
CREATE TABLE IF NOT EXISTS `assistant` (
  `matricule` varchar(50) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `date_embauche` date NOT NULL,
  `code_region` int(11) NOT NULL,
  PRIMARY KEY (`matricule`),
  KEY `FK1` (`code_region`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `assistant`
--

INSERT INTO `assistant` (`matricule`, `nom`, `prenom`, `adresse`, `date_embauche`, `code_region`) VALUES
('MA01', 'Boumehdi', 'Zeyd', '12 rue de la Paix', '2019-08-07', 1);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `numero_client` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `raison_sociale` varchar(255) NOT NULL,
  `siren` varchar(255) NOT NULL,
  `code_APE` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `duree_deplacement` varchar(255) NOT NULL,
  `distance_km` int(11) NOT NULL,
  `numero_agence` int(11) NOT NULL,
  PRIMARY KEY (`numero_client`),
  KEY `FK1` (`numero_agence`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`numero_client`, `prenom`, `nom`, `raison_sociale`, `siren`, `code_APE`, `adresse`, `telephone`, `fax`, `email`, `duree_deplacement`, `distance_km`, `numero_agence`) VALUES
(1, 'Gerard', 'Dupont', '  ', '458451484', 'pipi', '2 rue de la paix', '06055285233', '0335282005', 'marc@gmail.fr', '15', 10, 1),
(2, 'Laurent', 'FranÃ§ois', '   ', 'ghf58', 'gfj5285248', '20 rue de Lille', '060258566', '031589435', 'eric@orange.fr', '45', 50, 2);

-- --------------------------------------------------------

--
-- Structure de la table `contrat_maintenance`
--

DROP TABLE IF EXISTS `contrat_maintenance`;
CREATE TABLE IF NOT EXISTS `contrat_maintenance` (
  `numero_contrat` int(11) NOT NULL AUTO_INCREMENT,
  `date_signature` date NOT NULL,
  `date_echeance` date NOT NULL,
  `numero_client` int(11) NOT NULL,
  `refTypeContrat` varchar(50) NOT NULL,
  PRIMARY KEY (`numero_contrat`),
  KEY `FK1` (`numero_client`),
  KEY `FK2` (`refTypeContrat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `controler`
--

DROP TABLE IF EXISTS `controler`;
CREATE TABLE IF NOT EXISTS `controler` (
  `numero_serie` int(11) NOT NULL,
  `numero_intervention` int(11) NOT NULL,
  `temps_passer` varchar(255) NOT NULL,
  `commentaire` varchar(255) NOT NULL,
  PRIMARY KEY (`numero_serie`,`numero_intervention`),
  KEY `FK1` (`numero_serie`),
  KEY `FK2` (`numero_intervention`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `controler`
--

INSERT INTO `controler` (`numero_serie`, `numero_intervention`, `temps_passer`, `commentaire`) VALUES
(1, 1, '1h30', 'izi'),
(1, 2, '1h30', 'izi');

-- --------------------------------------------------------

--
-- Structure de la table `famille`
--

DROP TABLE IF EXISTS `famille`;
CREATE TABLE IF NOT EXISTS `famille` (
  `code_famille` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY (`code_famille`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `famille_produit`
--

DROP TABLE IF EXISTS `famille_produit`;
CREATE TABLE IF NOT EXISTS `famille_produit` (
  `code_famille` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY (`code_famille`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `intervention`
--

DROP TABLE IF EXISTS `intervention`;
CREATE TABLE IF NOT EXISTS `intervention` (
  `numero_intervention` int(11) NOT NULL AUTO_INCREMENT,
  `date_visite` date NOT NULL,
  `heure_visite` varchar(255) NOT NULL,
  `matricule_technicien` varchar(50) NOT NULL,
  `numero_client` int(11) NOT NULL,
  `validation` int(11) NOT NULL,
  PRIMARY KEY (`numero_intervention`),
  KEY `FK1` (`matricule_technicien`),
  KEY `FK_NumClient` (`numero_client`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `intervention`
--

INSERT INTO `intervention` (`numero_intervention`, `date_visite`, `heure_visite`, `matricule_technicien`, `numero_client`, `validation`) VALUES
(1, '2019-11-12', '12h30', 'MT01', 1, 1),
(4, '2019-11-13', '18h15', 'MT01', 2, 0),
(2, '2019-11-08', '16h', 'MT01', 2, 1),
(3, '2019-11-26', '15h', 'MT01', 1, 0),
(5, '2019-11-20', '18h15', 'MT01', 2, 0),
(6, '2019-11-20', '8h15', 'MT01', 1, 0),
(7, '2019-11-28', '5h', 'MT01', 1, 0),
(8, '2019-11-28', '5h', 'MT01', 1, 0),
(9, '2019-11-13', '18h15', 'MT01', 1, 0),
(10, '2019-11-17', '14h30', 'MT02', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `materiel`
--

DROP TABLE IF EXISTS `materiel`;
CREATE TABLE IF NOT EXISTS `materiel` (
  `numero_serie` int(11) NOT NULL AUTO_INCREMENT,
  `date_de_vente` date NOT NULL,
  `date_installation` date NOT NULL,
  `prix_vente` varchar(50) NOT NULL,
  `emplacement` varchar(255) NOT NULL,
  `reference_type` varchar(50) NOT NULL,
  `numero_client` int(11) NOT NULL,
  PRIMARY KEY (`numero_serie`),
  KEY `FK1` (`reference_type`),
  KEY `FK2` (`numero_client`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `region`
--

DROP TABLE IF EXISTS `region`;
CREATE TABLE IF NOT EXISTS `region` (
  `code_region` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`code_region`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `region`
--

INSERT INTO `region` (`code_region`, `nom`) VALUES
(1, 'Nord');

-- --------------------------------------------------------

--
-- Structure de la table `technicien`
--

DROP TABLE IF EXISTS `technicien`;
CREATE TABLE IF NOT EXISTS `technicien` (
  `matricule` varchar(50) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `date_embauche` date NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `qualification` varchar(255) NOT NULL,
  `date_obtention` date NOT NULL,
  `numero_agence` int(11) NOT NULL,
  PRIMARY KEY (`matricule`),
  KEY `FK1` (`numero_agence`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `technicien`
--

INSERT INTO `technicien` (`matricule`, `nom`, `prenom`, `adresse`, `date_embauche`, `telephone`, `qualification`, `date_obtention`, `numero_agence`) VALUES
('MT01', 'Chassaing', 'Julien', '69 rue du Cul', '2019-10-01', '06050402', 'Aucune', '2019-05-06', 1),
('MT02', 'Caron', 'Michel', '20 rue de la rue', '2018-12-25', '0604282561', 'bts', '2018-02-12', 1);

-- --------------------------------------------------------

--
-- Structure de la table `type_contrat`
--

DROP TABLE IF EXISTS `type_contrat`;
CREATE TABLE IF NOT EXISTS `type_contrat` (
  `refTypeContrat` varchar(50) NOT NULL,
  `delai_intervention` varchar(255) NOT NULL,
  `Taux_applicable` int(11) NOT NULL,
  PRIMARY KEY (`refTypeContrat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `type_materiel`
--

DROP TABLE IF EXISTS `type_materiel`;
CREATE TABLE IF NOT EXISTS `type_materiel` (
  `reference` varchar(50) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `code_famille` int(11) NOT NULL,
  PRIMARY KEY (`reference`),
  KEY `FK1` (`code_famille`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `mdp` varchar(50) NOT NULL,
  `statut` varchar(50) NOT NULL,
  `matricule` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matricule` (`matricule`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `login`, `mdp`, `statut`, `matricule`) VALUES
(1, 'test', 'test', 'Technicien', 'MT01'),
(2, 'test1', 'test1', 'Assistant', 'MA01');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
