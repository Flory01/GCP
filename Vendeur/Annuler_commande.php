<?php
	//Ouverture de la session
	session_start();
	require("../Connexion.php");

	$id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
	if (empty($id_agent)) {
		header("location:../Index.php");
	}

	//Récupération de l'identifiant de la vente
	$IdVte = (isset($_GET['pr'])) ? (!empty($_GET['pr'])) ? (preg_match("/^[0-9]*$/", $_GET['pr'])) ? $_GET['pr'] : header("location:gestion_produit.php") : header("location:gestion_produit.php") : 0 ;
	//Déclaration de la variable TableauPKCmd
	$TableauPkCmd = [];
	//Requête de sélection des références dont la valeur de la colonne Commande à la valeur de la variable IdVte
	$ChaineCmd = "SELECT * FROM `reference` WHERE Commande = $IdVte";
	//Préparation de la requête
	$RequeteCmd = $connexion->prepare($ChaineCmd);
	//exécution de la requête
	$RequeteCmd->execute();
	//Boucle de récupération de tous les identifiants des commandes et leurs affectations dans le tableau TableauPkCmd
	while ($ResultatCmd= $RequeteCmd->fetch(PDO::FETCH_ASSOC)) {
		$IdCmd = $ResultatCmd["PK_Ref"];
		array_push($TableauPkCmd, $IdCmd);
	}

	$i = 0;
	//Boucle de supression de toutes les commandes récupérées ci-haut
	while ( $i<count($TableauPkCmd)) {
		$IdCmd = $TableauPkCmd[$i];
		$RequetSupCmd = $connexion->prepare("DELETE FROM `reference` WHERE PK_Ref = $IdCmd");
		$RequetSupCmd->execute();
		$i++;
	}

	//Suppression du paiement associé à la vente
	$RequetSupPaie = $connexion->prepare("DELETE FROM `paiement` WHERE Commande = $IdVte");
	$RequetSupPaie->execute();

	//Suppression de la vente
	$RequetSupCmd = $connexion->prepare("DELETE FROM `commande` WHERE PK_Cmd = $IdVte");
	$RequetSupCmd->execute();
	//Redirection
	header("refresh: 4; url=gestion_commande.php");
?>
<!--Début de la structure de la page-->
<!DOCTYPE html>
<html lang="fr">
<!--Entête de la page-->
<head>
	<title>Suppression du produit</title>
	<link rel="stylesheet" type="text/css" href="annulation_commande.css">
	<link rel="stylesheet" type="text/css" href="../css/all.css">
	<link rel="stylesheet" type="text/css" href="../menu.css">
	<link rel="stylesheet" type="text/css" href="../foot.css">
</head>
<!--Corps de la page-->
<body>
	<?php 
		//Inclusion du menu
		require "../menu.php"; 
	?>
	<!--Zone contenant le message de confirmation de l'annulation de la vente-->
	<div id="BoxMsg">
		<div id="MsgSucces" <?php if (!empty($IdVte)) {echo "style='display: flex;'";} ?>>
			<p>
				Annulation de la commande effectuée avec succès.
			</p>
		</div>
		
	</div>
	<!--Inclusion du pied de la page-->
	<?php require "../foot.php"; ?>
</body>
</html>