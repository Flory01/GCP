-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : ven. 07 fév. 2025 à 20:20
-- Version du serveur : 5.7.39
-- Version de PHP : 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_vente`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `IdAdmin` int(10) UNSIGNED NOT NULL,
  `Nom` varchar(20) NOT NULL,
  `MotDePasse` varchar(255) NOT NULL,
  `PasseAssocie` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `administrateur`
--

INSERT INTO `administrateur` (`IdAdmin`, `Nom`, `MotDePasse`, `PasseAssocie`) VALUES
(1, 'MUPILA', '$2y$10$4lGB3a5ABbhvPm/nmrRmee241azmiSf9OUUqhbCcWOtxsRbzrKQrK', 'HFM110899');

-- --------------------------------------------------------

--
-- Structure de la table `agent`
--

CREATE TABLE `agent` (
  `PK_Agent` int(10) UNSIGNED NOT NULL,
  `Nom_Agent` varchar(20) NOT NULL,
  `PostNom_Agent` varchar(20) NOT NULL,
  `Tel_Agent` varchar(10) NOT NULL,
  `Genre_Agent` int(10) UNSIGNED NOT NULL,
  `MotDePasse` varchar(255) DEFAULT NULL,
  `PasseAssoc` varchar(255) DEFAULT NULL,
  `Etat` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `agent`
--

INSERT INTO `agent` (`PK_Agent`, `Nom_Agent`, `PostNom_Agent`, `Tel_Agent`, `Genre_Agent`, `MotDePasse`, `PasseAssoc`, `Etat`) VALUES
(1, 'NGONGO', 'FLORY', '0846677060', 1, '$2y$10$EziksxVb7fXZu0VdaE6hU.OshM4wU6dOCIWIboaUwIyuIHPxTHrXq', '0000123', 'A'),
(2, 'BIONGO', 'MPIA', '0825041747', 1, '$2y$10$6WN9gS1y3ALS9GXCoqQuee5glPUgjgkK/xGGfvWMZ8piiQuUSYD56', '0000123', 'A');

-- --------------------------------------------------------

--
-- Structure de la table `cat_produit`
--

CREATE TABLE `cat_produit` (
  `PK_Cat_Produit` int(10) UNSIGNED NOT NULL,
  `Lib_Cat_Produit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `cat_produit`
--

INSERT INTO `cat_produit` (`PK_Cat_Produit`, `Lib_Cat_Produit`) VALUES
(1, 'Pains');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `PK_Cl` int(10) UNSIGNED NOT NULL,
  `Nom_Cl` varchar(20) NOT NULL,
  `PostNom_Cl` varchar(20) NOT NULL,
  `Tel_Cl` varchar(10) NOT NULL,
  `Genre_Cl` int(10) UNSIGNED NOT NULL,
  `adresse` varchar(200) NOT NULL,
  `PasseAssocie` varchar(200) NOT NULL,
  `MotDePasse` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`PK_Cl`, `Nom_Cl`, `PostNom_Cl`, `Tel_Cl`, `Genre_Cl`, `adresse`, `PasseAssocie`, `MotDePasse`) VALUES
(1, 'BIONGO', 'JONATHAN', '0825041747', 1, 'C/NGALIEMA, Q/BUMBA, AV/KINSHASA 18', 'HFM110899', '$2y$10$4lGB3a5ABbhvPm/nmrRmee241azmiSf9OUUqhbCcWOtxsRbzrKQrK'),
(2, 'SOPO', 'JULLY', '0980935686', 2, 'C/NGALIEMA, Q/BUMBA, AV/LIBERTE 07', '', ''),
(3, 'NDJEKA', 'HELENE', '0935478965', 2, 'C/NGALIEMA, Q/BUMBA, AV/KINSHASA 18', '', ''),
(4, 'LAURA', 'MANGAYE', '0896754321', 2, 'C/NGALIEMA, Q/BUMBA, AV/LIBERTE 07', '', ''),
(5, 'GOMBO', 'NATHAN', '0987654433', 1, 'C/NGALIEMA, Q/BUMBA, AV/LIBERTE 07', '', ''),
(6, 'LAURA', 'NGONGO', '0928754224', 2, 'C/NGALIEMA, Q/BUMBA, AV/LIBERTE 07', '', ''),
(7, 'ROHI', 'KQSHQLE', '0987654432', 1, 'C/NGALIEMA, Q/BUMBA, AV/KINSHASA 18', '', ''),
(8, 'GRADI', 'MBIYA', '0876543222', 1, 'C/NGALIEMA, Q/BUMBA, AV/KINSHASA 18', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `PK_Cmd` int(10) UNSIGNED NOT NULL,
  `Client` int(10) UNSIGNED NOT NULL,
  `Date_Cmd` int(10) UNSIGNED NOT NULL,
  `Agent` int(10) UNSIGNED DEFAULT NULL,
  `Qte_Cmd` int(10) UNSIGNED DEFAULT '0',
  `livraison` varchar(7) NOT NULL DEFAULT 'NON'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`PK_Cmd`, `Client`, `Date_Cmd`, `Agent`, `Qte_Cmd`, `livraison`) VALUES
(1, 1, 1, 1, 7, 'OUI'),
(3, 2, 3, 1, 95, 'OUI'),
(4, 1, 4, 1, 0, 'OUI'),
(5, 1, 5, 2, 10, 'OUI'),
(6, 3, 6, 1, 1000, 'OUI'),
(7, 3, 7, 1, 60, 'OUI'),
(8, 4, 8, 1, 17, 'OUI'),
(9, 5, 9, 1, 25, 'OUI'),
(10, 6, 10, 1, 6, 'OUI'),
(11, 7, 11, 1, 5, 'OUI'),
(12, 7, 12, 2, 11, 'ENCOURS'),
(13, 8, 13, 1, 8, 'OUI');

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

CREATE TABLE `genre` (
  `PK_Genre` int(10) UNSIGNED NOT NULL,
  `Lib_Genre` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `genre`
--

INSERT INTO `genre` (`PK_Genre`, `Lib_Genre`) VALUES
(1, 'M'),
(2, 'F');

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `PK_Paie` int(10) UNSIGNED NOT NULL,
  `Agent` int(10) UNSIGNED DEFAULT NULL,
  `Client` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `Commande` int(10) UNSIGNED NOT NULL,
  `Mont_Paie` int(10) UNSIGNED NOT NULL,
  `Observation` varchar(10) NOT NULL DEFAULT 'NON PAYE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`PK_Paie`, `Agent`, `Client`, `Commande`, `Mont_Paie`, `Observation`) VALUES
(1, 1, 1, 1, 4200, 'PAYE'),
(3, 1, 2, 3, 66500, 'PAYE'),
(4, 1, 1, 4, 0, 'PAYE'),
(5, 2, 1, 5, 3500, 'PAYE'),
(6, 1, 3, 6, 515000, 'PAYE'),
(7, 1, 3, 7, 30000, 'PAYE'),
(8, 1, 4, 8, 9100, 'PAYE'),
(9, 1, 5, 9, 12700, 'PAYE'),
(10, 1, 6, 10, 2450, 'PAYE'),
(11, 1, 7, 11, 3500, 'PAYE'),
(12, NULL, 7, 12, 5950, 'NON PAYE'),
(13, 1, 8, 13, 5600, 'PAYE');

-- --------------------------------------------------------

--
-- Structure de la table `photo_agent`
--

CREATE TABLE `photo_agent` (
  `PK_Photo_Agent` int(10) UNSIGNED NOT NULL,
  `Agent` int(10) UNSIGNED NOT NULL,
  `Nom_Photo_Agent` varchar(255) NOT NULL,
  `Source_Photo_Agent` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `photo_agent`
--

INSERT INTO `photo_agent` (`PK_Photo_Agent`, `Agent`, `Nom_Photo_Agent`, `Source_Photo_Agent`) VALUES
(1, 1, 'FLO.jpg', 'Photos/FLO.jpg'),
(2, 2, 'IMG_1469.JPG', 'Photos/IMG_1469.JPG');

-- --------------------------------------------------------

--
-- Structure de la table `photo_produit`
--

CREATE TABLE `photo_produit` (
  `PK_Photo_Produit` int(10) UNSIGNED NOT NULL,
  `Produit` int(10) UNSIGNED NOT NULL,
  `Nom_Photo_Produit` varchar(255) NOT NULL,
  `Source_Photo_Produit` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `photo_produit`
--

INSERT INTO `photo_produit` (`PK_Photo_Produit`, `Produit`, `Nom_Photo_Produit`, `Source_Photo_Produit`) VALUES
(1, 1, 'PISTOLET.jpg', 'Photos/PISTOLET.jpg'),
(3, 3, 'CARRE.jpg', 'Photos/CARRE.jpg'),
(4, 4, 'KANGA_JOURNEE.jpg', 'Photos/KANGA_JOURNEE.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `PK_Pro` int(10) UNSIGNED NOT NULL,
  `categorie` int(10) UNSIGNED NOT NULL,
  `Lib_Pro` varchar(20) NOT NULL,
  `P_U` int(10) UNSIGNED NOT NULL,
  `Qte_Stock` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`PK_Pro`, `categorie`, `Lib_Pro`, `P_U`, `Qte_Stock`) VALUES
(1, 1, 'PAIN PISTOLET', 350, 450),
(3, 1, 'PAIN CARRE', 700, 405),
(4, 1, 'PAIN BAGUETTE', 500, 901);

-- --------------------------------------------------------

--
-- Structure de la table `reference`
--

CREATE TABLE `reference` (
  `PK_Ref` int(10) UNSIGNED NOT NULL,
  `Produit` int(10) UNSIGNED NOT NULL,
  `Commande` int(10) UNSIGNED NOT NULL,
  `Qte_Ref` int(10) UNSIGNED NOT NULL,
  `Prix_Ref` int(10) UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `reference`
--

INSERT INTO `reference` (`PK_Ref`, `Produit`, `Commande`, `Qte_Ref`, `Prix_Ref`) VALUES
(1, 1, 1, 2, 700),
(3, 3, 1, 5, 3500),
(6, 3, 3, 95, 66500),
(8, 1, 5, 10, 3500),
(9, 1, 6, 500, 175000),
(10, 3, 6, 450, 315000),
(11, 4, 6, 50, 25000),
(12, 1, 7, 20, 7000),
(13, 3, 7, 15, 10500),
(14, 4, 7, 25, 12500),
(15, 1, 8, 8, 2800),
(16, 3, 8, 9, 6300),
(17, 3, 9, 1, 700),
(18, 4, 9, 24, 12000),
(19, 3, 10, 1, 700),
(20, 1, 10, 5, 1750),
(21, 3, 11, 5, 3500),
(22, 1, 12, 5, 1750),
(23, 3, 12, 6, 4200),
(24, 3, 13, 8, 5600);

--
-- Déclencheurs `reference`
--
DELIMITER $$
CREATE TRIGGER `mise_a_jour_stock_reference` AFTER INSERT ON `reference` FOR EACH ROW BEGIN
	UPDATE Produit
    SET Qte_Stock = (Qte_Stock - NEW.Qte_Ref)
    WHERE PK_Pro = NEW.Produit;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `restore_a_jour_stock_reference` AFTER DELETE ON `reference` FOR EACH ROW BEGIN
	UPDATE Produit
    SET Qte_Stock = (Qte_Stock + OLD.Qte_Ref)
    WHERE PK_Pro = OLD.Produit;

    UPDATE commande
    SET Qte_Cmd = (Qte_Cmd - OLD.Qte_Ref)
    WHERE PK_Cmd = OLD.Commande;

    UPDATE paiement
    SET Mont_Paie = (Mont_Paie - OLD.Prix_Ref)
    WHERE Commande = OLD.Commande;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `temps`
--

CREATE TABLE `temps` (
  `PK_Temps` int(10) UNSIGNED NOT NULL,
  `Jour` varchar(8) NOT NULL,
  `Mois` int(10) NOT NULL,
  `Annee` varchar(4) NOT NULL,
  `Heure` varchar(2) NOT NULL,
  `Minute` varchar(2) NOT NULL,
  `Seconde` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `temps`
--

INSERT INTO `temps` (`PK_Temps`, `Jour`, `Mois`, `Annee`, `Heure`, `Minute`, `Seconde`) VALUES
(1, '09', 1, '2025', '14', '48', '28'),
(2, '09', 1, '2025', '14', '51', '54'),
(3, '09', 1, '2025', '15', '02', '13'),
(4, '09', 1, '2025', '15', '07', '22'),
(5, '09', 1, '2025', '15', '15', '07'),
(6, '09', 1, '2025', '21', '11', '57'),
(7, '09', 1, '2025', '21', '19', '40'),
(8, '12', 1, '2025', '12', '22', '55'),
(9, '16', 1, '2025', '20', '24', '40'),
(10, '19', 1, '2025', '17', '25', '39'),
(11, '21', 1, '2025', '14', '12', '46'),
(12, '21', 1, '2025', '14', '19', '56'),
(13, '21', 1, '2025', '14', '32', '39');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`IdAdmin`);

--
-- Index pour la table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`PK_Agent`),
  ADD KEY `agent_fk_genre` (`Genre_Agent`);

--
-- Index pour la table `cat_produit`
--
ALTER TABLE `cat_produit`
  ADD PRIMARY KEY (`PK_Cat_Produit`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`PK_Cl`),
  ADD KEY `client_fk_genre` (`Genre_Cl`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`PK_Cmd`),
  ADD KEY `commande_fk_agent` (`Agent`),
  ADD KEY `commande_fk_client` (`Client`),
  ADD KEY `commande_fk_temps` (`Date_Cmd`);

--
-- Index pour la table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`PK_Genre`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`PK_Paie`),
  ADD KEY `paiement_fk_Agent` (`Agent`),
  ADD KEY `paiement_fk_client` (`Client`),
  ADD KEY `paiement_fk_vente` (`Commande`);

--
-- Index pour la table `photo_agent`
--
ALTER TABLE `photo_agent`
  ADD PRIMARY KEY (`PK_Photo_Agent`),
  ADD KEY `cle_etranger_photo_Agent` (`Agent`);

--
-- Index pour la table `photo_produit`
--
ALTER TABLE `photo_produit`
  ADD PRIMARY KEY (`PK_Photo_Produit`),
  ADD KEY `cle_etranger_photo_produit` (`Produit`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`PK_Pro`),
  ADD KEY `produit_cle_etrangere_cat_produit` (`categorie`);

--
-- Index pour la table `reference`
--
ALTER TABLE `reference`
  ADD PRIMARY KEY (`PK_Ref`),
  ADD KEY `commande_fk_produit` (`Produit`),
  ADD KEY `commande_fk_vente` (`Commande`);

--
-- Index pour la table `temps`
--
ALTER TABLE `temps`
  ADD PRIMARY KEY (`PK_Temps`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `IdAdmin` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `agent`
--
ALTER TABLE `agent`
  MODIFY `PK_Agent` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `cat_produit`
--
ALTER TABLE `cat_produit`
  MODIFY `PK_Cat_Produit` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `PK_Cl` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `PK_Cmd` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `genre`
--
ALTER TABLE `genre`
  MODIFY `PK_Genre` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `PK_Paie` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `photo_agent`
--
ALTER TABLE `photo_agent`
  MODIFY `PK_Photo_Agent` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `photo_produit`
--
ALTER TABLE `photo_produit`
  MODIFY `PK_Photo_Produit` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `PK_Pro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `reference`
--
ALTER TABLE `reference`
  MODIFY `PK_Ref` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `temps`
--
ALTER TABLE `temps`
  MODIFY `PK_Temps` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `agent_fk_genre` FOREIGN KEY (`Genre_Agent`) REFERENCES `genre` (`PK_Genre`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `client_fk_genre` FOREIGN KEY (`Genre_Cl`) REFERENCES `genre` (`PK_Genre`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_fk_agent` FOREIGN KEY (`Agent`) REFERENCES `agent` (`PK_Agent`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commande_fk_client` FOREIGN KEY (`Client`) REFERENCES `client` (`PK_Cl`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commande_fk_temps` FOREIGN KEY (`Date_Cmd`) REFERENCES `temps` (`PK_Temps`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `paiement_fk_Agent` FOREIGN KEY (`Agent`) REFERENCES `agent` (`PK_Agent`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paiement_fk_client` FOREIGN KEY (`Client`) REFERENCES `client` (`PK_Cl`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paiement_fk_vente` FOREIGN KEY (`Commande`) REFERENCES `commande` (`PK_Cmd`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `photo_agent`
--
ALTER TABLE `photo_agent`
  ADD CONSTRAINT `cle_etranger_photo_Agent` FOREIGN KEY (`Agent`) REFERENCES `agent` (`PK_Agent`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `photo_produit`
--
ALTER TABLE `photo_produit`
  ADD CONSTRAINT `cle_etranger_photo_produit` FOREIGN KEY (`Produit`) REFERENCES `produit` (`PK_Pro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_cle_etrangere_cat_produit` FOREIGN KEY (`categorie`) REFERENCES `cat_produit` (`PK_Cat_Produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reference`
--
ALTER TABLE `reference`
  ADD CONSTRAINT `commande_fk_produit` FOREIGN KEY (`Produit`) REFERENCES `produit` (`PK_Pro`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commande_fk_vente` FOREIGN KEY (`Commande`) REFERENCES `commande` (`PK_Cmd`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
