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
		<link rel="stylesheet" type="text/css" href="gestion_commande_en_livraison.css">
		<link rel="stylesheet" type="text/css" href="../menu.css">
		<link rel="stylesheet" type="text/css" href="../foot.css">
	</head>
	<body>
		
	<?php require "../menu.php"; ?>

		<?php
			$val_commande = "";
			$taille_commande = (isset($_POST['taille_commande'])) ? $_POST['taille_commande'] : 2 ;
			$page_commande = (isset($_GET['page_commande'])) ? (!empty($_GET['page_commande'])) ? (preg_match("/[0-9]/", $_GET['page_commande']))? $_GET['page_commande'] : 1 : 1 : 1 ;
			if (preg_match("/[0-9]/", $page_commande)) {
				$offset_commande = ($page_commande-1)*$taille_commande;
			} else {
				$offset_commande = 0;
			}
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (!empty($_POST["nv_commande"])) {
					if (preg_match("/^[a-zA-Z]*$/", $_POST["nv_commande"])) {
						$val_commande = strtoupper($_POST["nv_commande"]);
						$chaine_commande = "SELECT * FROM `commande` AS `V` INNER JOIN `paiement` AS `P` ON P.Commande = V.PK_Cmd INNER JOIN `temps` AS `T` ON T.PK_Temps = V.Date_Cmd INNER JOIN `client` AS `Cl` ON P.Client = Cl.PK_Cl WHERE P.Observation='NON PAYE' AND CL.Nom_Cl = '$val_commande' AND V.livraison='ENCOURS' AND V.Agent = $id_agent LIMIT $taille_commande OFFSET $offset_commande";
						$rq_sel_commande = $connexion->prepare($chaine_commande);

						$rq_sel_nbre_commande = $connexion->prepare("SELECT count(*) nbre_commande FROM `commande` AS `V` INNER JOIN `paiement` AS `P` ON P.Commande = V.PK_Cmd INNER JOIN `temps` AS `T` ON T.PK_Temps = V.Date_Cmd INNER JOIN `client` AS `Cl` ON P.Client = Cl.PK_Cl WHERE P.Observation='NON PAYE' AND CL.Nom_Cl = '$val_commande' AND V.livraison='ENCOURS' AND V.Agent = $id_agent LIMIT $taille_commande OFFSET $offset_commande");
					}else{
						$chaine_commande = "SELECT * FROM `commande` AS `V` INNER JOIN `paiement` AS `P` ON P.Commande = V.PK_Cmd INNER JOIN `temps` AS `T` ON T.PK_Temps = V.Date_Cmd INNER JOIN `client` AS `Cl` ON P.Client = Cl.PK_Cl WHERE P.Observation='NON PAYE' AND V.livraison='ENCOURS' LIMIT $taille_commande OFFSET $offset_commande";
						$rq_sel_commande = $connexion->prepare($chaine_commande);
						$rq_sel_nbre_commande = $connexion->prepare("SELECT count(*) nbre_commande FROM `commande` AS `V` INNER JOIN `paiement` AS `P` ON P.Commande = V.PK_Cmd INNER JOIN `temps` AS `T` ON T.PK_Temps = V.Date_Vte INNER JOIN `client` AS `Cl` ON P.Client = Cl.PK_Cl WHERE P.Observation='NON PAYE' AND V.livraison='ENCOURS' AND V.Agent = $id_agent LIMIT $taille_commande");
					}
				}else{
					$chaine_commande = "SELECT * FROM `commande` AS `V` INNER JOIN `paiement` AS `P` ON P.Commande = V.PK_Cmd INNER JOIN `temps` AS `T` ON T.PK_Temps = V.Date_Cmd INNER JOIN `client` AS `Cl` ON P.Client = Cl.PK_Cl WHERE P.Observation='NON PAYE' AND V.livraison='ENCOURS' AND V.Agent = $id_agent LIMIT $taille_commande OFFSET $offset_commande";
					$rq_sel_commande = $connexion->prepare($chaine_commande);

					$rq_sel_nbre_commande = $connexion->prepare("SELECT count(*) nbre_commande FROM `commande` AS `V` INNER JOIN `paiement` AS `P` ON P.Commande = V.PK_Cmd INNER JOIN `temps` AS `T` ON T.PK_Temps = V.Date_Cmd INNER JOIN `client` AS `Cl` ON P.Client = Cl.PK_Cl WHERE P.Observation='NON PAYE' AND V.livraison='ENCOURS' AND V.Agent = $id_agent LIMIT $taille_commande");
				}
			} else {
				$chaine_commande = "SELECT * FROM `commande` AS `V` INNER JOIN `paiement` AS `P` ON P.Commande = V.PK_Cmd INNER JOIN `temps` AS `T` ON T.PK_Temps = V.Date_Cmd INNER JOIN `client` AS `Cl` ON P.Client = Cl.PK_Cl WHERE P.Observation='NON PAYE' AND V.livraison='ENCOURS' AND V.Agent = $id_agent LIMIT $taille_commande OFFSET $offset_commande";
				$rq_sel_commande = $connexion->prepare($chaine_commande);

				$rq_sel_nbre_commande = $connexion->prepare("SELECT count(*) nbre_commande FROM `commande` AS `V` INNER JOIN `paiement` AS `P` ON P.Commande = V.PK_Cmd INNER JOIN `temps` AS `T` ON T.PK_Temps = V.Date_Cmd INNER JOIN `client` AS `Cl` ON P.Client = Cl.PK_Cl WHERE P.Observation='NON PAYE' AND V.livraison='ENCOURS' AND V.Agent = $id_agent LIMIT $taille_commande");
			}
			$rq_sel_commande->execute();
			$rq_sel_nbre_commande->execute();
			$rs_commande = $rq_sel_nbre_commande->fetch(PDO::FETCH_ASSOC);
			$nbre_commande = $rs_commande["nbre_commande"];
			$reste_commande = ($nbre_commande % $taille_commande);
										
			if ($reste_commande == 0) {
				$nbre_page_commande = ($nbre_commande / $taille_commande);
			} else {
				$nbre_page_commande = floor($nbre_commande / $taille_commande) + 1;
			}
		?>
		<div <?php if(empty($nbre_commande)){echo "style='display:none'";} ?> id="BoxBtnTel">
			<p>
				<a href="impression_commande_en_cours_de_livraison.php" class="fas fa-download" title="Liste des commandes en cours de livraison" target="_blank" rel="noopener noreferrer"></a>
			</p>
		</div>

		<form id="RechercheVte" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		
		<div id="BoxSelectionVte">
			<input type="text" id="nv_commande" name="nv_commande" placeholder="Ex:Biongo">
		</div>

		<div id="BoxVteSelect">
			<input type="submit" name="BtnRechercheVteNV" value="Recherchez">
		</div>
	</form>

	


		<h3 class="Titre">
			Liste des commandes en cours de livraison
		</h3>
		<div id="BoxVte">
		<?php
					while ($rs_sel_commande = $rq_sel_commande->fetch(PDO::FETCH_ASSOC)){
						$id_cmd = $rs_sel_commande["PK_Cmd"];
						if (empty($rs_sel_commande["Qte_Cmd"])) {
							# code...
							$rq_suppression_vente = $connexion->prepare("DELETE FROM `paiement` WHERE Commande = $id_cmd");
							$rq_suppression_vente->execute();

							$rq_suppression_vente = $connexion->prepare("DELETE FROM `commande` WHERE PK_Cmd = $id_cmd");
							$rq_suppression_vente->execute();
						}else{ ?>
							<div class="BoxVteSelect">
								<div id="Vte">
									<div>
										<h3 style="color: white">
											<?php echo "Commande N° : ".$rs_sel_commande["PK_Cmd"] ?>
										</h3>
										<div>
											<p>
												Date de commande : <?php echo $rs_sel_commande["Jour"]." / ".$rs_sel_commande["Mois"]." / ".$rs_sel_commande["Annee"]; ?>
											</p>

											<p>
												Client : <?php echo $rs_sel_commande["Nom_Cl"]." ".$rs_sel_commande["PostNom_Cl"]; ?>
											</p>

											<p>
												Quantité : <?php echo $rs_sel_commande["Qte_Cmd"]; ?>
											</p>

											<p>
												<?php
													$rq_sel_produit = $connexion->prepare("SELECT * FROM `reference` AS `R` INNER JOIN `produit` AS `P` ON P.PK_Pro = R.Produit WHERE R.Commande=$id_cmd");
													$rq_sel_produit->execute();

													$rq_sel_count_produit = $connexion->prepare("SELECT count(*) nbre_produit FROM `reference` AS `R` INNER JOIN `produit` AS `P` ON P.PK_Pro = R.Produit WHERE R.Commande=$id_cmd");
													$rq_sel_count_produit->execute();
													$rs_sel_count_produit = $rq_sel_count_produit->fetch(PDO::FETCH_ASSOC);

													if ($rs_sel_count_produit["nbre_produit"] > 1) {
														echo "Produits commandés : ";
														while ($rs_sel_produit = $rq_sel_produit->fetch(PDO::FETCH_ASSOC)) {
															echo " / ".$rs_sel_produit["Lib_Pro"]." : ".$rs_sel_produit["Qte_Ref"];
														}
													} else {
														echo "Produit commandé : ";
														while ($rs_sel_produit = $rq_sel_produit->fetch(PDO::FETCH_ASSOC)) {
															echo $rs_sel_produit["Lib_Pro"];
														}
													}

												?>
											</p>

											<p>
												Net à payer : <?php echo $rs_sel_commande["Mont_Paie"]." FC"; ?>
											</p>
											<p class="Btn">
												<a id="BtnSup" href="Annuler_commande.php?pr=<?php echo $rs_sel_commande['PK_Cmd'] ; ?>" onclick="return confirm('Êtes-vous sûr de pouvoir annuler la commande ?')">Annulez</a>
											</p>

											<p class="Btn">
												<a href="Valider_commande.php?pr=<?php echo $rs_sel_commande['PK_Cmd'] ; ?>">Valider</a>
											</p>

											<p class="Btn">
												<a href="impression_bon_de_commande.php?pr=<?php echo $rs_sel_commande['PK_Cmd'] ; ?>" target="_blank" rel="noopener noreferrer">Bon de commande</a>
											</p>
										</div>
									</div>
								</div>
							</div>
				<?php	}
						?>	
			<?php	}
				?>
		</div>

		<div id="BoxBtnDefilement">
			<p>
				<ul>
				<?php
					if ($nbre_page_commande > 1) {
						if ($page_commande > 1) { ?>
							<li>
								<a href="gestion_commande_en_livraison.php?page_commande=<?php echo ($page_commande - 1) ?>" class="fas fa-arrow-left">
								</a>
							</li>

							<li>
								<a href="gestion_commande_en_livraison.php?page_commande=<?php echo ($page_commande + 1) ?>" <?php if($nbre_page_commande == $page_commande){ echo "style='display:none;'";} ?> class="fas fa-arrow-right" title='LienPage'>
								</a>
							</li>
				<?php	} else { ?>
							<li>
								<a href="gestion_commande_en_livraison.php?page_commande=<?php echo ($page_commande + 1) ?>" class="fas fa-arrow-right" title='LienPage'>
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