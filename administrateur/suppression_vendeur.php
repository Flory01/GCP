<?php
	require("../Connexion.php");

	$NumAgent= (isset($_GET["xxx"]))?$_GET["xxx"]:0;
	$requeteAgent = "SELECT * FROM `agent` WHERE PK_Agent = '$NumAgent'";
	$requeteSupAgent = $connexion->prepare($requeteAgent);
	$requeteSupAgent->execute();
	$resultatSupAgent = $requeteSupAgent->fetch(PDO::FETCH_ASSOC);

	if (is_array($resultatSupAgent)) {
		$requete2 = "DELETE FROM `agent` WHERE PK_Agent = '$NumAgent'";
		$requeteSup2 = $connexion->prepare($requete2);
		$requeteSup2->execute();
		header('location:gestion_utilisateur.php');
	} else {
		echo"Il n'y a pas eu de suppression";
	}
?>