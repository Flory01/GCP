<?php 
	session_start();
	require("../Connexion.php");

	$id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
	if (empty($id_agent)) {
		header("location:../Index.php");
	}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Gestion Produit</title>
	<link rel="stylesheet" type="text/css" href="../css/all.css">
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="gestion_prod_rup.css">
	<link rel="stylesheet" type="text/css" href="../menu.css">
	<link rel="stylesheet" type="text/css" href="../foot.css">
</head>
<body>
		
	<?php require "../menu.php"; ?>

		<?php
			$ValRP = "";
			$TailleProdRup = (isset($_POST['TailleProdRup'])) ? $_POST['TailleProdRup'] : 1 ;
			$pageProdRup = (isset($_GET['pageProdRup'])) ? (!empty($_GET['pageProdRup'])) ? (preg_match("/[0-9]/", $_GET['pageProdRup']))? $_GET['pageProdRup'] : 1 : 1 : 1 ;
			if (preg_match("/[0-9]/", $pageProdRup)) {
				$offsetProdRup = ($pageProdRup-1)*$TailleProdRup;
			} else {
				$offsetProdRup = 0;
			}
			if (!isset($_POST["BtnRechercheRP"])) {
				
				$ChaineProdRup = "SELECT * FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE Qte_Stock = 0 LIMIT $TailleProdRup OFFSET $offsetProdRup";
				$RequeteSelProdRup = $connexion->prepare($ChaineProdRup);
				$RequteNbreProdRup = $connexion->prepare("SELECT count(*) NbreProd FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE Qte_Stock = 0 LIMIT $TailleProdRup");
			} else {
				if (!empty($_POST["ProdRP"])) {
					$ValRP = $_POST["ProdRP"];
					$ChaineProdRup = "SELECT * FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE Qte_Stock = 0 AND PK_Pro = $ValRP LIMIT $TailleProdRup OFFSET $offsetProdRup";
					$RequeteSelProdRup = $connexion->prepare($ChaineProdRup);

					$RequteNbreProdRup = $connexion->prepare("SELECT count(*) NbreProd FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE Qte_Stock = 0 AND PK_Pro = $ValRP");
				}else{
					$ChaineProdRup = "SELECT * FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE Qte_Stock = 0 LIMIT $TailleProdRup OFFSET $offsetProdRup";
					$RequeteSelProdRup = $connexion->prepare($ChaineProdRup);
					$RequteNbreProdRup = $connexion->prepare("SELECT count(*) NbreProd FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE Qte_Stock = 0 LIMIT $TailleProdRup");
				}
			}
			$RequeteSelProdRup->execute();
			$RequteNbreProdRup->execute();
			$ResProdProdRup = $RequteNbreProdRup->fetch(PDO::FETCH_ASSOC);
			$NbreProdProdRup = $ResProdProdRup["NbreProd"];
			$ResteProdRup = ($NbreProdProdRup % $TailleProdRup);
										
			if ($ResteProdRup == 0) {
				$NbrePageProdRup = ($NbreProdProdRup / $TailleProdRup);
			} else {
				$NbrePageProdRup = floor($NbreProdProdRup / $TailleProdRup) + 1;
			}
		?>

		<form id="RechercheProduit" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		
		<div id="BoxSelectionProduit">
			<select id="ProdRP" name="ProdRP">
				<option value="">
					Aucun
				</option>
				<?php 
					$ChaineRP="SELECT * FROM `produit` WHERE Qte_Stock = 0";
					$RequeteRP = $connexion->prepare($ChaineRP);
					$RequeteRP->execute();

					while ($ResultatRP = $RequeteRP->fetch(PDO::FETCH_ASSOC)) { ?>
						<option value="<?php echo $ResultatRP['PK_Pro'] ?>" <?php if($ValRP == $ResultatRP['PK_Pro']){echo "selected";} ?>>
							<?php echo $ResultatRP['Lib_Pro'] ?>
						</option>
			<?php	}
				?>
			</select>
		</div>

		<div id="BoxProdSelect">
			<input type="submit" name="BtnRechercheRP" value="Recherchez">
		</div>
	</form>

	<div <?php if(empty($NbreProdProdRup)){echo "style='display:none'";} ?> id="BoxBtnTel">
		<p>
			<a href="impression_prodruit_en_rup.php" class="fas fa-download" title="Télécharez la liste des produits en rupture de stock" target="_blank" rel="noopener noreferrer"></a>
		</p>
	</div>


		<h3 class="Titre">
			Liste des produits en rupture de stock
		</h3>
		<div id="BoxProdSelect">
		<?php
			//$RequeteSelProdRup = $connexion->prepare("SELECT * FROM `produit` WHERE Qte_Stock = 0");
			//$RequeteSelProdRup->execute();
					//$ResultatSelProdRup = $RequeteSelProdRup->fetch(PDO::FETCH_ASSOC);
					while ($ResultatSelProdRup = $RequeteSelProdRup->fetch(PDO::FETCH_ASSOC)){ ?>
						<div class="BoxProdSelect">
						<div id="Produit">
							<div>
								<img src="<?php echo $ResultatSelProdRup["Source_Photo_Produit"] ?>" >

								<h3 style="color: white">
									<?php echo $ResultatSelProdRup["Lib_Pro"] ?>
								</h3>
								<div style="background: #34495E;">
									<p>
										Quantité en stock : <?php echo $ResultatSelProdRup["Qte_Stock"]; ?>
									</p>

									<p>
										Prix unitaire : <?php echo $ResultatSelProdRup["P_U"]." FC"; ?>
									</p>
									<p class="Btn">
										<a href="Appro_produit.php?pr=<?php echo $ResultatSelProdRup['PK_Pro'] ; ?>">Approvisionner</a>
									</p>
									<p class="Btn">
										<a id="BtnSup" onclick="return confirm('Êtes-vous sûr de pouvoir supprimer ce produit ?')" href="supprimer_produit.php?pr=<?php echo $ResultatSelProdRup['PK_Pro']; ?>">Supprimer</a>
									</p>
								</div>
							</div>
						</div>
						</div>	
			<?php	}
				?>
		</div>

		<div id="BoxBtnDefilement">
			<p>
				<ul>
				<?php
					if ($NbrePageProdRup > 1) {
						if ($pageProdRup > 1) { ?>
							<li>
								<a href="gestion_prod_rup.php?pageProdRup=<?php echo ($pageProdRup - 1) ?>" class="fas fa-arrow-left">
								</a>
							</li>

							<li>
								<a href="gestion_prod_rup.php?pageProdRup=<?php echo ($pageProdRup + 1) ?>" <?php if($NbrePageProdRup == $pageProdRup){ echo "style='display:none;'";} ?> class="fas fa-arrow-right">
								</a>
							</li>
				<?php	} else { ?>
							<li>
								<a href="gestion_prod_rup.php?pageProdRup=<?php echo ($pageProdRup + 1) ?>" class="fas fa-arrow-right">
								</a>
							</li>
				<?php	}
					} ?>
				</ul>
			</p>
		</div>
		<?php require "../foot.php"; ?>
</body>
</html>