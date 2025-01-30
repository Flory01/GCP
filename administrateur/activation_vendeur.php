<?php
	require("../Connexion.php");
	$NumAgent = (isset($_GET["xxx"]))? $_GET["xxx"]:0;
	$requete = "SELECT * FROM `agent` WHERE PK_Agent = '$NumAgent'";
	$requetAg = $connexion->prepare($requete);
	$requetAg->execute();
	$resultatAg = $requetAg->fetch(PDO::FETCH_ASSOC);

	if (is_array($resultatAg)) {
		if ($resultatAg["Etat"] === "A") {
			$newEtat = "D";
		} else {
			$newEtat = "A";
		}
	$requeteMiseAjourAg = $connexion->prepare("UPDATE `agent` SET Etat = '$newEtat' WHERE PK_Agent = '$NumAgent'");
	$requeteMiseAjourAg->execute();
	header("location:gestion_utilisateur.php");
		
	} else {
		header("location:gestion_utilisateur.php");
	}
	
?>