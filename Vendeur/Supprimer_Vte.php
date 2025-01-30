<?php
	session_start();
	require("../Connexion.php");

	$id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
	if (empty($id_agent)) {
		header("location:../Index.php");
	}
	
	$IdVte = (isset($_GET['pr'])) ? (!empty($_GET['pr'])) ? (preg_match("/^[0-9]*$/", $_GET['pr'])) ? $_GET['pr'] : header("location:gestion_produit.php") : header("location:gestion_produit.php") : 0 ;

	$TableauPkCmd = [];
	$ChaineCmd = "SELECT * FROM `commande` WHERE Vente = $IdVte";
	$RequeteCmd = $connexion->prepare($ChaineCmd);
	$RequeteCmd->execute();
	while ($ResultatCmd = $RequeteCmd->fetch(PDO::FETCH_ASSOC)) {
		$IdCmd = $ResultatCmd["PK_Cmd"];
		array_push($TableauPkCmd, $IdCmd);
	}

	$i = 0;
	while ( $i<count($TableauPkCmd)) {
		$IdCmd = $TableauPkCmd[$i];
		$RequetUpCmd = $connexion->prepare("UPDATE `commande` SET Qte_Cmd = 0 WHERE PK_Cmd = $IdCmd");
		$RequetUpCmd->execute();

		$RequetSupCmd = $connexion->prepare("DELETE FROM `commande` WHERE PK_Cmd = $IdCmd");
		$RequetSupCmd->execute();
		$i++;
	}

	//Suppression du paiement associé à la vente
	$RequetSupPaie = $connexion->prepare("DELETE FROM `paiement` WHERE Vente = $IdVte");
	$RequetSupPaie->execute();

	//Suppression de la vente
	$RequetSupVte = $connexion->prepare("DELETE FROM `vente` WHERE PK_Vte = $IdVte");
	$RequetSupVte->execute();
	header("refresh: 4; url=gestion_Vente.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Suppression du produit</title>
	<link rel="stylesheet" type="text/css" href="suppression_commande.css">
	<link rel="stylesheet" type="text/css" href="../css/all.css">
	<link rel="stylesheet" type="text/css" href="../menu.css">
	<link rel="stylesheet" type="text/css" href="../foot.css">
</head>
<body>
	<?php require "../menu.php"; ?>
	<div id="BoxMsg">
		<div id="MsgSucces" <?php if (!empty($IdVte)) {echo "style='display: flex;'";} ?>>
			<p>
				Suppression effectuée avec succès.
			</p>
		</div>
		
	</div>
	<?php require "../foot.php"; ?>
</body>
</html>