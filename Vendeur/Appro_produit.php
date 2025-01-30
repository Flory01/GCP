<?php
	//Ouverture de la session
	session_start();
	require("../Connexion.php");

	$id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
	if (empty($id_agent)) {
		header("location:../Index.php");
	}

	//Récupération de l'identifiant du produit renvoyé par le formulaire
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$IdProd = $_POST["IdProd"];
	}
	//Récupération de l'identifiant du produit 
	$IdP = (isset($_GET['pr'])) ? (!empty($_GET['pr'])) ? (preg_match("/^[0-9]*$/", $_GET['pr'])) ? $_GET['pr'] : header("location:gestion_produit.php") : header("location:gestion_produit.php") : $IdProd ;

	//Requête de sélection des produits 
	$Chaine = "SELECT * FROM `produit` WHERE PK_Pro=$IdP";
	//Préparation de la requête
	$Requete = $connexion->prepare($Chaine);
	//Exécution de la requête
	$Requete->execute();
	//Récupération des données de la table
	$ResultatProd = $Requete->fetch(PDO::FETCH_ASSOC);
	//Affectation du nom du produit
	$LibPro = $ResultatProd["Lib_Pro"];
?>
<!--Début de la structure de la page-->
<!DOCTYPE html>
<html lang="fr">
<!--Entête de la page-->
<head>
	<title>Approvisionnement produit</title>
	<link rel="stylesheet" type="text/css" href="approvisionnement_produit.css">
	<link rel="stylesheet" type="text/css" href="../css/all.css">
	<link rel="stylesheet" type="text/css" href="../menu.css">
	<link rel="stylesheet" type="text/css" href="../foot.css">
</head>
<!--Corps de la page-->
<body>
	<?php
		//Inclusion du menu
		require "../menu.php";
		//Déclaration des variables
		$QteStock = $ErrQteStock = $NomProd = $ErrNomProd = $QteFin = "";
		//Vérification : si le formulaire a été envoyé
		if ($_SERVER["REQUEST_METHOD"]=="POST") {
			//Vérification : si la variable de récupération de la quantité d'approvisionnement n'est pas vide
			if (!empty($_POST['QteStock'])) {
				//Vérification : si la variable de récupération de la quantité d'approvisionnement est un entier
				if (preg_match("/^[0-9]*$/", $_POST['QteStock'])) {
					$QteStock = $_POST['QteStock'];
				} else {
					//Affectation du message d'erreur au cas où la valeur de la quantité n'est pas un entier
					$ErrQteStock = "<p class='Erreur'>Evitez les caractères spéciaux</p>";
				}
			}else{
				//Affectation du message d'erreur au cas où aucune quantité d'approvisionnement n'est renseignée
				$ErrQteStock = "<p class='Erreur'>Veuillez remplir ce champ</p>";
			}
			//Vérification : si la variable de récupération du nom du produit n'est pas vide
			if (!empty($_POST['NomProd'])){
				//Vérification : si la variable de récupération correspont au REGEX défini
				if (preg_match("/^[a-zA-Zé ]*$/", $_POST['NomProd'])) {
					//Affectation du nom si le nom du produit correspond au REGEX
					$NomProd = $_POST['NomProd'];
				} else {
					//Affectation de l'erreur si le nom du produit ne correspond pas au REGEX
					$ErrNomProd = "<p class='Erreur'>Evitez les caractères spéciaux</p>";
				}
			} else {
				//Affectation de l'erreur si le nom du produit n'existe pas
				$ErrNomProd = "<p class='Erreur'>Veuillez remplir ce champ</p>";
			}
			
			//Vérification : si le nom et la quantité d'approvisionnement existent
			if (!empty($QteStock) AND !empty($NomProd)) {
				//Sélection Qte Produit
				$ChaineQteSt = "SELECT `Qte_Stock` FROM `produit` WHERE PK_Pro=$IdP";
				$RequeteQteSt = $connexion->prepare($ChaineQteSt);
				$RequeteQteSt->execute();
				$ResultatQteSt = $RequeteQteSt->fetch(PDO::FETCH_ASSOC);
				$QteSt = $ResultatQteSt["Qte_Stock"]; 
				//Addition de la quantité initiale à la quantité d'approvisionnement
				$QteFin = ($QteSt + $QteStock);

				//Approvionnement
				$RequetInsertCmd= $connexion->prepare("UPDATE `produit` SET Qte_Stock = $QteFin WHERE PK_Pro = $IdP");
				$RequetInsertCmd->BindParam(":agent",$QteStock);
				$RequetInsertCmd->execute();
				//Rédirection
				header("refresh: 4; url=gestion_produit.php");
			}	
		}
		
	?>
	<!--Zone contenant le formulaire d'approvisionnement-->
	<div id="BoxForm">
		<!--Formulaire d'approvisionnement-->
		<form id="Formulaire_commande" <?php if (!empty($QteStock) AND !empty($NomProd)) {echo "style='display: none;'";} else {echo "style='display: grid;'";} ?> action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<!--Titre du formulaire-->
			<h3>
				Approvionnement produit
			</h3>
			<!--Zone contenant le nom et l'identifiant du produit à approvisionner-->
			<div id="BoxQteStock">
				<input type="hidden" name="IdProd" id="IdProd" value="<?php echo $IdP ?>">
				<label for="QteStock">Nom du produit</label>
				<input type="text" name="NomProd" id="NomProd" value="<?php echo $LibPro ?>" readonly>
				<?php echo $ErrNomProd ?>
			</div>
			<!--Zone permettant d'insérer la quantité d'approvisionnement-->
			<div id="BoxQteStock">
				<label for="QteStock">Qantité appro</label>
				<input type="number" name="QteStock" id="QteStock" value="<?php echo $QteStock ?>">
				<?php echo $ErrQteStock ?>	
			</div>
			<!--Zone contenant contenant le bouton permettant de valider le formulaire-->
			<div id="BoxBtnValide">
				<input type="submit" name="BtnValider" value="Valider">	
			</div>
			
		</form>
		<!--Zone contenant le message de confirmation de l'approvisionnement-->
		<div id="MsgSucces" <?php if (!empty($QteStock) AND !empty($NomProd)) {echo "style='display: flex;'";} ?>>
			<p>
				Approvionnement effectué avec succès.
			</p>
		</div>
	</div>
	<!--Inclusion du pied de la page-->
	<?php require "../foot.php"; ?>
</body>
</html>