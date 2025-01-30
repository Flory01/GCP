<?php
	require("../Connexion.php");

	$id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
	if (empty($id_agent)) {
		header("location:../Index.php");
	}

	$NumClient= (isset($_GET["xxx"]))?$_GET["xxx"]:0;
	$requeteAgent = "SELECT * FROM `client` WHERE PK_Cl = '$NumClient'";
	$requeteSupAgent = $connexion->prepare($requeteAgent);
	$requeteSupAgent->execute();

	if ($resultatSupAgent = $requeteSupAgent->fetch(PDO::FETCH_ASSOC)) {
		$requete2 = "DELETE FROM `client` WHERE PK_Cl = '$NumClient'";
		$requeteSup2 = $connexion->prepare($requete2);
		$requeteSup2->execute();
		header('location:gestion_client.php');
	} else {
		echo"Il n'y a pas eu de suppression";
	}
?>