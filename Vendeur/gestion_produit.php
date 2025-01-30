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
	<link rel="stylesheet" type="text/css" href="gestion_produit.css">
	<link rel="stylesheet" type="text/css" href="../menu.css">
	<link rel="stylesheet" type="text/css" href="../foot.css">
</head>
<body>
	<?php
		require "../menu.php";
		$Prod = $Qte = $Pu = $ErQte = $ValPro = $ValRP = "";
			$Tab=[];
			$Taille = (isset($_POST['Taille'])) ? $_POST['Taille'] : 2 ;
			$page = (isset($_GET['page'])) ? (!empty($_GET['page'])) ? (preg_match("/[0-9]/", $_GET['page']))? $_GET['page'] : 1 : 1 : 1 ;
			if (preg_match("/[0-9]/", $page)) {
				$offset = ($page-1)*$Taille;
			} else {
				$offset = 0;
			}
			if (!isset($_POST["BtnRecherche"])) {
				
				$Chaine = "SELECT * FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE NOT Qte_Stock = 0 LIMIT $Taille OFFSET $offset";
				$Requete = $connexion->prepare($Chaine);
				$RequteNbre = $connexion->prepare("SELECT count(*) NbreProd FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE NOT Qte_Stock = 0 LIMIT $Taille");
			} else {
				if (!empty($_POST["Prod"])) {
					$ValPro = $_POST["Prod"];
					$Chaine = "SELECT * FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE NOT Qte_Stock = 0 AND PK_Pro = $ValPro LIMIT $Taille OFFSET $offset";
					$Requete = $connexion->prepare($Chaine);
					$RequteNbre = $connexion->prepare("SELECT count(*) NbreProd FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE NOT Qte_Stock = 0 AND PK_Pro = $ValPro");
				}else{
					$Chaine = "SELECT * FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE NOT Qte_Stock = 0 LIMIT $Taille OFFSET $offset";
					$Requete = $connexion->prepare($Chaine);
					$RequteNbre = $connexion->prepare("SELECT count(*) NbreProd FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE NOT Qte_Stock = 0 LIMIT $Taille");
				}
			}
			$Requete->execute();
			$RequteNbre->execute();
			$ResProd = $RequteNbre->fetch(PDO::FETCH_ASSOC);
			$NbreProd = $ResProd["NbreProd"];
			$Reste = ($NbreProd % $Taille);
										
			if ($Reste == 0) {
				$NbrePage = ($NbreProd / $Taille);
			} else {
				$NbrePage = floor($NbreProd / $Taille) + 1;
			}
	?>
	<div id="AffNomAgent">
		<?php
			$id_ag = $_SESSION['v'];
			$rq_sel_photo = $connexion->prepare("SELECT * FROM `photo_agent` WHERE Agent = $id_ag");
			$rq_sel_photo->execute();
			$rs_sel_photo = $rq_sel_photo->fetch(PDO::FETCH_ASSOC);
	
			if ($_SESSION['utilisateurs']["v_".$_SESSION['v']]["genre"] == 2) {
				# code...
				echo "<p>Bienvenue Mme ".$_SESSION['utilisateurs']["v_".$_SESSION['v']]["nom"]." ".$_SESSION['utilisateurs']["v_".$_SESSION['v']]["post_nom"]."</p>";
			}else{
				echo "<p>Bienvenue M. ".$_SESSION['utilisateurs']["v_".$_SESSION['v']]["nom"]." ".$_SESSION['utilisateurs']["v_".$_SESSION['v']]["post_nom"]."</p>";
			}
			$Source = $rs_sel_photo["Source_Photo_Agent"];
			echo "<img src='../{$Source}'>";
		?>
		
	</div>
	<h3 class="Titre">
		Recherche produits disponibles
	</h3>
	<form id="RechercheProduit" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		
		<div id="BoxSelectionProduit">
			<select id="Prod" name="Prod">
				<option value="">
					Aucun
				</option>
				<?php 
					$Ch="SELECT * FROM `produit` WHERE NOT Qte_Stock = 0";
					$RequeteP = $connexion->prepare($Ch);
					$RequeteP->execute();

					while ($ResultatP = $RequeteP->fetch(PDO::FETCH_ASSOC)) { ?>
						<option value="<?php echo $ResultatP['PK_Pro'] ?>" <?php if($ValPro == $ResultatP['PK_Pro']){echo "selected";} ?>>
							<?php echo $ResultatP['Lib_Pro'] ?>
						</option>
			<?php	}
				?>
			</select>
		</div>

		<div id="BoxProdSelect">
			<input type="submit" name="BtnRecherche" value="Recherchez">
		</div>
	</form>

	<div id="BoxBtnTel">
		<p>
			<a href="form_produit.php" class="fas fa-cart-shopping" title="Ajout commande"></a>
		</p>
		<p>
			<a href="Insert_produit.php" class="fas fa-add" title="Ajout produit"></a>
		</p>
		<p>
			<a href="Impression_produit.php" class="fas fa-download" title="Téléchargement de la liste des produits" target="_blank" rel="noopener noreferrer"></a>
		</p>
		<p>
			<a href='gestion_prod_rup.php' class='fas fa-list' title="Liste des prodruits en rupture de stock"></a>
		</p>
	</div>

	<h3 class="Titre">
		Liste des produits disponibles
	</h3>

		<div id="BoxProd">
		<?php
			
			while($Resultat = $Requete->fetch(PDO::FETCH_ASSOC)){
				$Prod = $Resultat["Lib_Pro"];
				$IdProd = $Resultat['PK_Pro'];
				$Pu = $Resultat["P_U"];
				?>
				<div class="BoxProdSelect">
				<div id="Produit">
					<div>
						<img src="<?php echo $Resultat["Source_Photo_Produit"] ?>" >
						<h3 style="color: white">
							<?php echo $Prod ?>
						</h3>
						<div>
							<p>
								Quantité en stock : <?php echo $Resultat["Qte_Stock"]; ?>
							</p>

							<p>
								Prix unitaire : <?php echo $Pu." FC"; ?>
							</p>
							<p class="Btn">
								<a href="Appro_produit.php?pr=<?php echo $IdProd; ?>"> Approvisionner</a>
							</p>
							<p class="Btn">
								<a href="Modifier_produit.php?pr=<?php echo $IdProd; ?>">Modifier</a>
							</p>
						</div>
					</div>
				</div>
				</div>

	<?php	
				$Tab[$IdProd] = $Resultat;

			}

			$_SESSION["TableauProd"] = $Tab;
		?>
		</div>
		<div id="BoxBtnDefilement">
			<p>
				<ul>
				<?php
					if ($NbrePage > 1) {
						if ($page > 1) { ?>
							<li>
								<a href="gestion_produit.php?page=<?php echo ($page - 1) ?>" class="fas fa-arrow-left">
								</a>
							</li>

							<li>
								<a href="gestion_produit.php?page=<?php echo ($page + 1) ?>" <?php if($NbrePage == $page){ echo "style='display:none;'";} ?> class="fas fa-arrow-right" title='LienPage'>
								</a>
							</li>
				<?php	} else { ?>
							<li>
								<a href="gestion_produit.php?page=<?php echo ($page + 1) ?>" class="fas fa-arrow-right">
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