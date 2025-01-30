<?php
session_start();
	require("fonctions_paniers.php");
	require("connexion.php");

	$lib_prod = $prix_prod = $qte_prod = $qte_stock = "";

	$id_prod = (isset($_GET["pr"])) ? $_GET["pr"]: 0;

	if (!empty($id_prod)) {
		if (preg_match("/^[0-9]*$/", $id_prod)) {
			$Chaine = "SELECT * FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE P.PK_Pro = $id_prod";
				//Préparation de la requête
			$Requete = $connexion->prepare($Chaine);
			$Requete->execute();

			if ($rs_sel_produit = $Requete->fetch(PDO::FETCH_ASSOC)) {
				$lib_prod = $rs_sel_produit["Lib_Pro"];
				$prix_prod = $rs_sel_produit["P_U"];
				$qte_stock = $rs_sel_produit["Qte_Stock"];

				ajouterArticle($lib_prod,1,$prix_prod,$qte_stock);
				header("location:Index.php");
			} else {
				header("location:Index.php");
			}
		} else {
			header("location:Index.php");
		}	
	} else {
		header("location:Index.php");
	}
?>