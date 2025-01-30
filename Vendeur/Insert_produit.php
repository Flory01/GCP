<?php
	session_start();
	require("../Connexion.php");

	$id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
	if (empty($id_agent)) {
		header("location:../Index.php");
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Formulaire Client</title>
	<link rel="stylesheet" type="text/css" href="insert_produit.css">
	<link rel="stylesheet" type="text/css" href="../css/all.css">
	<link rel="stylesheet" type="text/css" href="../menu.css">
	<link rel="stylesheet" type="text/css" href="../foot.css">
</head>
<body>
	<?php
		require "../menu.php";
		$NomProd = $ErrNomProd = $PuProd = $ErrPuProd = $Qte = $ErrQte = $NomPhoto = $ErrCatProd = $CatProd = "";
		if ($_SERVER["REQUEST_METHOD"]=="POST") {
			if (empty($_POST["categorie"])) {
				$ErrCatProd = "<p class='Erreur'>Veuillez sélectionner une catégorie</p>";
			} else {
				$CatProd = trim($_POST["categorie"]);	
			}
			if (empty($_POST["Prod"])) {
				$ErrNomProd = "<p class='Erreur'>Veuillez remplir ce champ</p>";
			}else{
				if (preg_match("/^[a-zA-Zé0-9ü\s]*$/", $_POST["Prod"])) {
					# code...
					if (trim($_POST["Prod"]) == "") {
						# code...
						$ErrNomProd = "<p class='Erreur'>Veuillez remplir ce champ</p>";
					}else{
						$NomProd = trim($_POST["Prod"]);
					}
				}else{
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

			if (empty($_POST["Qte"])) {
				$ErrQte = "<p class='Erreur'>Veuillez remplir ce champ</p>";
			} else {
				if (preg_match("/^[0-9]*$/", $_POST["Qte"])) {
					$Qte = $_POST["Qte"];
				} else {
					$ErrQte = "<p class='Erreur'>Evitez les caractères spéciaux</p>";
				}	
			}

			if (!empty($_FILES["Photo"])) {
				$_FILES["Photo"]["type"]."<br>";
				$Type = ["image/jpeg", "image/png", "image/Gif"];

				if (in_array($_FILES["Photo"]["type"], $Type)) {
					$NomPhoto = $_FILES["Photo"]["name"];
					$ImgDir = "Photos/".$_FILES["Photo"]["name"];
					$SourcePhoto = "Photos/".$_FILES["Photo"]["name"];
					move_uploaded_file($_FILES["Photo"]["tmp_name"], $ImgDir);
				}
												
			}

			if (!empty($NomProd) AND !empty($PuProd) AND !empty($Qte) AND !empty($NomPhoto) AND !empty($CatProd)) {
				//Insertion
				$RequeteInsertProd = $connexion->prepare("INSERT INTO `produit` (Categorie,Lib_Pro, P_U, Qte_Stock) SELECT :Categorie, :LibProduit, :Pu, :QteStock FROM DUAL WHERE NOT EXISTS(SELECT * FROM `produit` WHERE Lib_Pro = '$NomProd' AND P_U = '$PuProd')");
				$RequeteInsertProd->BindParam(":LibProduit", $NomProd);
				$RequeteInsertProd->BindParam(":Categorie", $CatProd);
				$RequeteInsertProd->BindParam(":Pu", $PuProd);
				$RequeteInsertProd->BindParam(":QteStock", $Qte);
				$RequeteInsertProd->execute();

				$RequeteSelctionProduit = $connexion->prepare("SELECT * FROM `produit` WHERE Lib_Pro = '$NomProd' AND P_U = '$PuProd'");
				$RequeteSelctionProduit->execute();
				$ResultatSelectionProduit = $RequeteSelctionProduit->fetch(PDO::FETCH_ASSOC);
				$IdProduit = $ResultatSelectionProduit["PK_Pro"];

				$Requete_photo = $connexion->prepare("INSERT INTO `photo_produit` VALUES (NULL,:Produit, :Nom, :Source)");
				$Requete_photo->BindParam(":Produit", $IdProduit);
				$Requete_photo->BindParam(":Nom", $NomPhoto);
				$Requete_photo->BindParam(":Source", $SourcePhoto);
				$Requete_photo->execute();
				header("refresh: 5; url=gestion_produit.php");
			}	
			
		}
		
	?>
	<div id="BoxForm">
	<form id="Formulaire_InsertProd" <?php if (!empty($NomProd) AND !empty($PuProd) AND !empty($Qte) AND !empty($NomPhoto) AND !empty($CatProd)){echo "Style='display:none'";} ?> action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
		<div class="LibProd">
			<select name="categorie" id="categorie" style="display: none;">
				<?php
					$rq_sel_cat = $connexion->prepare("SELECT * FROM `cat_produit`");
					$rq_sel_cat->execute();
					while ($rs_sel_cat = $rq_sel_cat->fetch(PDO::FETCH_ASSOC)) {
						$lib_cat_prod = str_replace("-", " ", $rs_sel_cat["Lib_Cat_Produit"]);
					?>
						<option value="<?php echo $rs_sel_cat["PK_Cat_Produit"]; ?>" <?php if($CatProd == $rs_sel_cat["PK_Cat_Produit"]){echo "selected";} ?>><?php echo $lib_cat_prod; ?></option>	
					<?php	
					}
				?>
			</select>
			<?php echo $ErrCatProd; ?>
		</div>
		<div class="LibProd">
			<label for="Prod">Nom du produit</label>
			<input type="text" name="Prod" id="Prod" value="<?php echo $NomProd ?>" maxlength="20">
			<?php echo $ErrNomProd; ?>
		</div>
		
		<div class="LibProd">
			<label for="P_U">Prix unitaire</label>
			<input type="number" name="P_U" id="P_U" value="<?php echo $PuProd ?>" maxlength="10">
			<?php echo $ErrPuProd ?>
		</div>

		<div class="LibProd">
			<label for="Qte">Qantité</label>
			<input type="number" name="Qte" id="Qte" value="<?php echo $Qte ?>" maxlength="10">
			<?php echo $ErrQte ?>
		</div>

		<div class="LibProd">
			<label for="Photo">Choisissez une photo:</label>
			<input type="file" name="Photo" id="Photo" value="" multiple="" style="font-size: 14px">
		</div>

		<div id="BoxBtnValide">
			<input type="submit" name="BtnValider" value="Valider">	
		</div>
		
	</form>
	<div id="MsgSucces" <?php if (!empty($NomProd) AND !empty($PuProd) AND !empty($Qte) AND !empty($NomPhoto) AND !empty($CatProd)){echo "Style='display:flex'";} ?>>
		<p>
			Produit Inséré  avec succès.
		</p>
	</div>
</div>
<?php require "../foot.php"; ?>
</body>
</html>