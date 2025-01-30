<?php
	session_start();
	require("../Connexion.php");

	$id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
	if (empty($id_agent)) {
		header("location:../Index.php");
	}
	
	$TableauPkRef  = array();
	$IdP = (isset($_GET['pr'])) ? (!empty($_GET['pr'])) ? (preg_match("/^[0-9]*$/", $_GET['pr'])) ? $_GET['pr'] : header("location:gestion_produit.php") : header("location:gestion_produit.php") : 0 ;

	//echo "{$IdP}";
	//Récupération des tous es réferences du produit
	$ChaineRecRef = "SELECT * FROM `reference` WHERE Produit=$IdP";
	$RequeteRecRef = $connexion->prepare($ChaineRecRef);
	$RequeteRecRef->execute();
	while ($ResultatRecRef = $RequeteRecRef->fetch(PDO::FETCH_ASSOC)) {
		$IdRef = $ResultatRecRef["PK_Ref"];
		$IdCmd = $ResultatRecRef["Commande"];
		array_push( $TableauPkRef,$IdRef);
	}
	$cpt = 0;
	while ($cpt < count($TableauPkRef)) {
		//Suppression de la référence
		$IdRef = $TableauPkRef[$cpt];
		$RequeteSupRef = $connexion->prepare("DELETE FROM `reference` WHERE PK_Ref = $IdRef");
		$RequeteSupRef->execute();
		$cpt++;
	}

	//Suppression produit
	$RequeteSupProd = $connexion->prepare("DELETE FROM `produit` WHERE PK_Pro = $IdP");
	$RequeteSupProd->execute();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Suppression du produit</title>
	<link rel="stylesheet" type="text/css" href="suppression_produit.css">
	<link rel="stylesheet" type="text/css" href="../css/all.css">
	<link rel="stylesheet" type="text/css" href="../menu.css">
	<link rel="stylesheet" type="text/css" href="../foot.css">
</head>
<body>
	<?php require "../menu.php"; ?>
	<div id="BoxMsg">
		<div id="MsgSucces" <?php if (!empty($IdP)) {echo "style='display: flex;'";header("refresh: 4; url=gestion_produit.php");} ?>>
			<p>
				Suppression effectuée avec succès.
			</p>
		</div>
		
	</div>
	<?php require "../foot.php"; ?>
</body>
</html>