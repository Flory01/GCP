<?php
	session_start();
	require("../Connexion.php");

	$id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
	if (empty($id_agent)) {
		header("location:../Index.php");
	}
	
	if ($_SERVER["REQUEST_METHOD"]=="POST") {
		$PK = $_POST["IdPro"];
	}
	
	$IdP = (isset($_GET['pr'])) ? (!empty($_GET['pr'])) ? (preg_match("/^[0-9]*$/", $_GET['pr'])) ? $_GET['pr'] : header("location:gestion_produit.php") : header("location:gestion_produit.php") : $PK ;

	$ChaineProd = "SELECT * FROM `produit` WHERE PK_Pro=$IdP";
	$RequeteProd = $connexion->prepare($ChaineProd);
	$RequeteProd->execute();
	$ResultatProd = $RequeteProd->fetch(PDO::FETCH_ASSOC);
	$NPro = $ResultatProd["Lib_Pro"];
	$PKPro = $ResultatProd["PK_Pro"];
	$PuPro = $ResultatProd["P_U"];
	$QtePro = $ResultatProd["Qte_Stock"];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Suppression du produit</title>
	<link rel="stylesheet" type="text/css" href="modifier_produit.css">
	<link rel="stylesheet" type="text/css" href="../css/all.css">
	<link rel="stylesheet" type="text/css" href="../menu.css">
	<link rel="stylesheet" type="text/css" href="../foot.css">
</head>
<body>
	<?php
		require "../menu.php";
		$NomProd = $ErrNomProd = $PuProd = $ErrPuProd = $Qte = $ErrQte = "";
		if ($_SERVER["REQUEST_METHOD"]=="POST") {
			if (empty($_POST["LibProd"])) {
				$ErrNomProd = "<p class='Erreur'>Veuillez remplir ce champ</p>";
			} else {
				if (preg_match("/^[a-zA-Zé ]*$/", $_POST["LibProd"])) {
					$NomProd = trim($_POST["LibProd"]);
				} else {
					$ErrNomProd = "<p class='Erreur'>Evitez les caractères spéciaux</p>";
				}	
			}

			if (empty($_POST["P_U"])) {
				$ErrPuProd = "<p class='Erreur'>Veuillez remplir ce champ</p>";
			} else {
				if (preg_match("/^[0-9]*$/", $_POST["P_U"])) {
					$PuProd = $_POST["P_U"];
				} else {
					$ErrPuProd = "<p class='Erreur'>Evitez les caractères spéciaux</p>";
				}	
			}

			if (!empty($NomProd) AND !empty($PuProd)) {
				//Insertion
				$RequeteInsertProd = $connexion->prepare("UPDATE `produit` SET Lib_Pro = '$NomProd', P_U = $PuProd WHERE PK_Pro = $IdP");
				$RequeteInsertProd->execute();
				header("refresh: 5; url=gestion_produit.php");
			}	
			
		}
		
	?>
	<div id="BoxForm">
	<form id="Formulaire_InsertProd" <?php if (!empty($NomProd) AND !empty($PuProd)){echo "Style='display:none'";} ?> action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div class="LibProd">
			<input type="number" name="IdPro" style="display: none;" value="<?php echo $PKPro; ?>">
			<label for="LibProd">Nom du produit</label>
			<input type="text" name="LibProd" id="LibProd" value="<?php if(empty($NomProd)){echo $NPro;}else{echo $NomProd;} ?>">
			<?php echo $ErrNomProd; ?>
		</div>
		
		<div class="LibProd">
			<label for="P_U">Prix unitaire</label>
			<input type="number" name="P_U" id="P_U" value="<?php if(empty($PuProd)){echo $PuPro; }else{echo $PuProd;} ?>">
			<?php echo $ErrPuProd ?>
		</div>

		<div id="BoxBtnValide">
			<input type="submit" name="BtnValider" value="Valider">	
		</div>
		
	</form>
	<div id="MsgSucces" <?php if (!empty($NomProd) AND !empty($PuProd)){echo "Style='display:flex'";} ?>>
		<p>
			Produit modifié  avec succès.
		</p>
	</div>
</div>
<?php require "../foot.php"; ?>
</body>
</html>