DROP DATABASE IF EXISTS `gestion_vente`;
CREATE DATABASE IF NOT EXISTS `gestion_vente` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gestion_vente`;

CREATE TABLE `administrateur` (
 `IdAdmin` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `Nom` varchar(20) NOT NULL,
 `MotDePasse` varchar(255) NOT NULL,
 `PasseAssocie` varchar(10) NOT NULL,
 PRIMARY KEY (`IdAdmin`)
) ENGINE=InnoDB;

CREATE TABLE `temps` (
 `PK_Temps` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `Jour` varchar(8) NOT NULL,
 `Mois` int(10) NOT NULL,
 `Annee` varchar(4) NOT NULL,
 `Heure` varchar(2) NOT NULL,
 `Minute` varchar(2) NOT NULL,
 `Seconde` varchar(2) NOT NULL,
 PRIMARY KEY (`PK_Temps`)
) ENGINE=InnoDB;

CREATE TABLE `genre` (
 `PK_Genre` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `Lib_Genre` varchar(1) NOT NULL,
 PRIMARY KEY (`PK_Genre`)
) ENGINE=InnoDB;

-- Chargement de la table genre--
INSERT INTO `genre` VALUES
(NULL, "M"),
(NULL, "F");

CREATE TABLE `cat_produit` (
 `PK_Cat_Produit` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `Lib_Cat_Produit` varchar(50) NOT NULL,
 PRIMARY KEY (`PK_Cat_Produit`)
) ENGINE=InnoDB;

INSERT INTO `cat_produit` VALUES 
(NULL, 'Pains');

CREATE TABLE `produit` (
 `PK_Pro` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `categorie` int(10) unsigned NOT NULL,
 `Lib_Pro` varchar(20) NOT NULL,
 `P_U` int(10) unsigned NOT NULL,
 `Qte_Stock` int(10) unsigned NOT NULL,
 PRIMARY KEY (`PK_Pro`),
 CONSTRAINT `produit_cle_etrangere_cat_produit` FOREIGN KEY (`categorie`) REFERENCES `cat_produit` (`PK_Cat_Produit`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `agent` (
 `PK_Agent` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `Nom_Agent` varchar(20) NOT NULL,
 `PostNom_Agent` varchar(20) NOT NULL,
 `Tel_Agent` varchar(10) NOT NULL,
 `Genre_Agent` int(10) unsigned NOT NULL,
 `MotDePasse` varchar(255) DEFAULT NULL,
 `PasseAssoc` varchar(255) DEFAULT NULL,
 `Etat` varchar(1) NOT NULL DEFAULT 'A',
 PRIMARY KEY (`PK_Agent`),
 CONSTRAINT `agent_fk_genre` FOREIGN KEY (`Genre_Agent`) REFERENCES `genre` (`PK_Genre`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `client` (
 `PK_Cl` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `Nom_Cl` varchar(20) NOT NULL,
 `PostNom_Cl` varchar(20) NOT NULL,
 `Tel_Cl` varchar(10) NOT NULL,
 `Genre_Cl` int(10) unsigned NOT NULL,
 `adresse` varchar(200) NOT NULL,
 PRIMARY KEY (`PK_Cl`),
 CONSTRAINT `client_fk_genre` FOREIGN KEY (`Genre_Cl`) REFERENCES `genre` (`PK_Genre`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `commande` (
 `PK_Cmd` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `Client` int(10) unsigned NOT NULL,
 `Date_Cmd` int(10) unsigned NOT NULL,
 `Agent` int(10) unsigned DEFAULT NULL,
 `Qte_Cmd` int(10) unsigned DEFAULT '0',
 `livraison` varchar(7) NOT NULL DEFAULT 'NON',
 PRIMARY KEY (`PK_Cmd`),
 CONSTRAINT `commande_fk_agent` FOREIGN KEY (`Agent`) REFERENCES `agent` (`PK_Agent`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `commande_fk_client` FOREIGN KEY (`Client`) REFERENCES `client` (`PK_Cl`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `commande_fk_temps` FOREIGN KEY (`Date_Cmd`) REFERENCES `temps` (`PK_Temps`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `reference` (
 `PK_Ref` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `Produit` int(10) unsigned NOT NULL,
 `Commande` int(10) unsigned NOT NULL,
 `Qte_Ref` int(10) unsigned NOT NULL,
 `Prix_Ref` int(10) unsigned DEFAULT '0',
 PRIMARY KEY (`PK_Ref`),
 CONSTRAINT `commande_fk_produit` FOREIGN KEY (`Produit`) REFERENCES `produit` (`PK_Pro`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `commande_fk_vente` FOREIGN KEY (`Commande`) REFERENCES `commande` (`PK_Cmd`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `paiement` (
 `PK_Paie` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `Agent` int(10) unsigned DEFAULT NULL,
 `Client` int(10) unsigned NOT NULL DEFAULT '1',
 `Commande` int(10) unsigned NOT NULL,
 `Mont_Paie` int(10) unsigned NOT NULL,
 `Observation` varchar(10) NOT NULL DEFAULT 'NON PAYE',
 PRIMARY KEY (`PK_Paie`),
 CONSTRAINT `paiement_fk_Agent` FOREIGN KEY (`Agent`) REFERENCES `agent` (`PK_Agent`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `paiement_fk_client` FOREIGN KEY (`Client`) REFERENCES `client` (`PK_Cl`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `paiement_fk_vente` FOREIGN KEY (`Commande`) REFERENCES `commande` (`PK_Cmd`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `photo_agent` (
 `PK_Photo_Agent` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `Agent` int(10) unsigned NOT NULL,
 `Nom_Photo_Agent` varchar(255) NOT NULL,
 `Source_Photo_Agent` varchar(255) NOT NULL,
 PRIMARY KEY (`PK_Photo_Agent`),
 CONSTRAINT `cle_etranger_photo_Agent` FOREIGN KEY (`Agent`) REFERENCES `agent` (`PK_Agent`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `photo_produit` (
 `PK_Photo_Produit` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `Produit` int(10) unsigned NOT NULL,
 `Nom_Photo_Produit` varchar(255) NOT NULL,
 `Source_Photo_Produit` varchar(255) NOT NULL,
 PRIMARY KEY (`PK_Photo_Produit`),
 CONSTRAINT `cle_etranger_photo_produit` FOREIGN KEY (`Produit`) REFERENCES `produit` (`PK_Pro`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

DROP TRIGGER IF EXISTS `mise_a_jour_stock_reference`;

DELIMITER $$
CREATE TRIGGER `mise_a_jour_stock_reference` AFTER INSERT ON `reference`
 FOR EACH ROW BEGIN
	UPDATE Produit
    SET Qte_Stock = (Qte_Stock - NEW.Qte_Ref)
    WHERE PK_Pro = NEW.Produit;
END
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `restore_a_jour_stock_reference`;
DELIMITER $$
CREATE TRIGGER `restore_a_jour_stock_reference` AFTER DELETE ON `reference`
 FOR EACH ROW BEGIN
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
