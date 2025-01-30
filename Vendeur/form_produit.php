<?php
	//Début de session
	session_start();
	require("../Connexion.php");

	$id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
	if (empty($id_agent)) {
		header("location:../Index.php");
	}

	//Inclusion du fichier contenant des fonctions
	require("../fonctions_paniers.php");

	//Récupération de l'action effectuée sur la page
	$action = (isset($_POST['action'])? $_POST['action']:  (isset($_GET['action'])? $_GET['action']:null )) ;
	$lp = (isset($_POST['lp'])? $_POST['lp']:  (isset($_GET['lp'])? $_GET['lp']:null )) ;

	if ($action !== null) {
		if ($action == "supprimer") {
			supprimerArticle($lp);
		}elseif($action == "vider") {
			supprimePanier();
		}
	}

?>
<!--Début de la structure de la page-->
<!DOCTYPE html>
<html lang="fr">
<!--Entête de la page-->
<head>
	<title>Gestion Produit</title>
	<link rel="stylesheet" type="text/css" href="../css/all.css">
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../accueil.css">
	<link rel="stylesheet" type="text/css" href="../menu.css">
	<link rel="stylesheet" type="text/css" href="../foot.css">
</head>
<!--Corps de la page-->
<body>
	<?php
		//Inclusion de la page contenant le menu
		require "../menu.php";

		//Déclaration des variables
		$Prod = $Qte = $Pu = $ErQte = $ValPro = "";
		$Tab=[];

		//Récupération du nombre de produit à afficher
		$Taille = (isset($_POST['Taille'])) ? $_POST['Taille'] : 2 ;

		//Récupération du numéro de la page
		$page = (isset($_GET['page'])) ? (!empty($_GET['page'])) ? (preg_match("/[0-9]/", $_GET['page']))? $_GET['page'] : 1 : 1 : 1 ;

		//Vérification : si la variable page contient une valeur entière
		if (preg_match("/[0-9]/", $page)) {
			//Calcul du saut de pas si la variable page contient une valeur entière
			$offset = ($page-1)*$Taille;
		} else {
			//Calcul du saut de pas si la variable page contient une valeur autre qu'une valeur entière
			$offset = 0;
		}

		//Vérification : de l'envoi du formulaire de recherche de produit
		if (!isset($_POST["BtnRecherche"])) {
			//Requête de sélection des produits dont la quantité en stock est différent de zéro
			$Chaine = "SELECT * FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE NOT Qte_Stock = 0 LIMIT $Taille OFFSET $offset";
			//Préparation de la requête de sélection des produits
			$Requete = $connexion->prepare($Chaine);

			//Préparation de la requête de récupération du nombre des produits
			$RequteNbre = $connexion->prepare("SELECT count(*) NbreProd FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE NOT Qte_Stock = 0 LIMIT $Taille");
		} else {
			//Vérification : si la variable de récupération de l'identifiant du produit n'est pas vide
			if (!empty($_POST["Prod"])) {
				//Affectation de la valeur de la variable de récupération à la variavle ValPro
				$ValPro = $_POST["Prod"];
				//Requête de sélection des produits dont la quantité en stock est différent de zéro mais par rapport à l'identfiant d'1 produit 
				$Chaine = "SELECT * FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE NOT Qte_Stock = 0 AND PK_Pro = $ValPro LIMIT $Taille OFFSET $offset";
				//Préparation de la requête
				$Requete = $connexion->prepare($Chaine);
					
				//Préparation de la requête de récupération du nombre des produits
				$RequteNbre = $connexion->prepare("SELECT count(*) NbreProd FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE NOT Qte_Stock = 0 AND PK_Pro = $ValPro");

			//Vérification : si la variable de récupération de l'identifiant du produit est vide	
			}else{
				//Requête de sélection des produits dont la quantité en stock est différent de zéro
				$Chaine = "SELECT * FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE NOT Qte_Stock = 0 LIMIT $Taille OFFSET $offset";
				//Préparation de la requête
				$Requete = $connexion->prepare($Chaine);

				//Préparation de la requête de récupération du nombre des produits
				$RequteNbre = $connexion->prepare("SELECT count(*) NbreProd FROM `produit` AS `P` INNER JOIN `photo_produit` AS `PP` ON PP.Produit = P.PK_Pro WHERE NOT Qte_Stock = 0 LIMIT $Taille");
			}
		}
		//Exécution des requêtes préparées
		$Requete->execute();
		$RequteNbre->execute();

		//Récupération des données de la base de données
		$ResProd = $RequteNbre->fetch(PDO::FETCH_ASSOC);
		//Récupération du nombre des produits
		$NbreProd = $ResProd["NbreProd"];
		//Calcul du reste des pages à parcourir
		$Reste = ($NbreProd % $Taille);
								
		if ($Reste == 0) {
			$NbrePage = ($NbreProd / $Taille);
		} else {
			$NbrePage = floor($NbreProd / $Taille) + 1;
		}
	?>
	<!--Premier titre-->
	<h3 class="Titre">
		Recherche produit
	</h3>
	<!--Formulaire de recherche des produits-->
	<form id="RechercheProduit" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<!--Zone d'affichage des produits existant dans la base de données et dont les quantité en stocks sont différents à zéro-->
		<div id="BoxSelectionProduit">
			<!--Liste de sélection-->
			<select id="Prod" name="Prod">
				<!--Premier élément de la liste-->
				<option value="">
					Aucun
				</option>

				<?php
					//Sélection des éléments restants de la liste
					$Ch="SELECT * FROM `produit` WHERE NOT Qte_Stock = 0";
					$RequeteP = $connexion->prepare($Ch);
					$RequeteP->execute();
					//Ajout d'élément tant que tous les produits ne sont pas affichés
					while ($ResultatP = $RequeteP->fetch(PDO::FETCH_ASSOC)) { ?>
						<option value="<?php echo $ResultatP['PK_Pro'] ?>" <?php if($ValPro == $ResultatP['PK_Pro']){echo "selected";} ?>>
							<?php echo $ResultatP['Lib_Pro'] ?>
						</option>
			<?php	}
				?>
			</select>
		</div>
		<!--Zone contenant le bouton recherchez-->
		<div id="BoxBtnRecherche">
			<input type="submit" name="BtnRecherche" value="Recherchez">
		</div>
	</form>
	<!--Formulaire comprenant le panier des biens-->
	<!--Formulaire comprenant le panier des biens-->
	<form <?php if (!array_key_exists("panier", $_SESSION)) {echo "style='display:none'";} ?> id='Panier' method='post' action="<?php echo htmlspecialchars('formulaire_commande.php')?>">
	    <table id="panierTable">
	        <tr>
	            <th>Produit</th>
	            <th>Prix</th>
	            <th>Quantité</th>
	            <th>Action</th>
	        </tr>

	    	<?php
	    	foreach ($_SESSION['panier']["libelleProduit"] as $key => $value) {
		        $sousTotal = $_SESSION['panier']['prixProduit'][$key] * $_SESSION['panier']['qteProduit'][$key];
		    ?>
			    <tr>
			        <td><?php echo $_SESSION['panier']['libelleProduit'][$key] ?></td>
			        <td><?php echo $_SESSION['panier']['prixProduit'][$key] ?> FC</td>
			        <td>
			            <input name='q[]' type='number' min='1' value="<?php echo $_SESSION['panier']['qteProduit'][$key] ?>" 
			                    data-id="<?php echo $_SESSION['panier']['libelleProduit'][$key] ?>" data-prix="<?php echo $_SESSION['panier']['prixProduit'][$key] ?>" class="quantite" oninput="mettreAJourTotal()">
			        </td>
			        <td style="display: none;">
			        <input type="number" name="sousTotal" class='sousTotal' value="<?php echo $sousTotal ?>">
			        </td>
			        <td>
			        	<a style="color: red; text-decoration: none;" href="form_produit.php?action=<?php echo 'supprimer&lp='.$_SESSION['panier']['libelleProduit'][$key] ?>">Supprimer</a>
			        </td>
			    </tr>
			<?php
		    }
		    ?>
	    	<tr>
	            <td colspan='2'>Total</td>
	            <td colspan='2'>
	            	<input type="text" name="totalPanier" id='totalPanier' value="" readonly="">
	            </td>
	        </tr>
	    	<tr>
				<td colspan='4'>
					<input type='submit' value='Commandez' name="btn_commande">
				</td>
			</tr>
	    </table>
	</form>

	<form <?php if (array_key_exists("panier", $_SESSION)) {echo "style='display:none'";} ?> id='Panier' method='post' action="<?php echo htmlspecialchars('formulaire_commande.php')?>">
		<table>
			<tr>
				<th>
					Votre panier est vide
				</th>
			</tr>
		</table>
	</form>

	<p <?php if (!array_key_exists("panier", $_SESSION)) {echo "style='display:none'";} ?> id="btn_sup"><a href="form_produit.php?action=vider">Vider le panier</a></p>

	<script>
	function mettreAJourTotal() {
	    const lignesProduits = document.querySelectorAll("#panierTable .quantite");
	    let total = 0;

	    lignesProduits.forEach(function(input) {
	        const prix = parseFloat(input.getAttribute('data-prix'));
	        const quantite = parseInt(input.value);
	        const sousTotal = prix * quantite;

	        // Mettre à jour le sous-total pour ce produit
	        const celluleSousTotal = input.closest("tr").querySelector(".sousTotal");
	        celluleSousTotal.value = sousTotal.toFixed(2) + " FC";

	        // Ajouter au total général
	        if (isNaN(sousTotal)) {
	        	total = 0;
	        }else{
	        	total += sousTotal;
	    	}
	    });

	    // Mettre à jour le total général du panier
	    document.getElementById('totalPanier').value = total.toFixed(2) + " FC";
	}

	// Appeler la fonction une fois au chargement de la page pour afficher le bon total
	window.onload = mettreAJourTotal;
	</script>
	<!--Deuxième titre pour les produits disponibles-->
	<h3 class="Titre">
		Liste des produits disponibles
	</h3>
	<!--Boite des produits disponibles-->
	<div id="BoxProd">
		<?php
			//Boucle pour parcourir tous les produits
			while($Resultat = $Requete->fetch(PDO::FETCH_ASSOC)){
				$Prod = $Resultat["Lib_Pro"];
				$IdProd = $Resultat['PK_Pro'];
				$Pu = $Resultat["P_U"];
				$source = $Resultat['Source_Photo_Produit'];
				?>
				
				<div id="Produit">
					<div>
						<img src="<?php echo $source ?>" >
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
							<p>
								<a href="ajout_produit.php?pr=<?php echo $IdProd; ?>">
									Ajouter au panier
								</a>
							</p>
						</div>
					</div>
				</div>
	<?php	

			}

		?>
	</div>
	<!--Zone contenant les boutons de défilement des produits disponibles-->
	<div id="BoxBtnDefilement">
		<p>
			<ul>
			<?php
				//Vérification : si la variable NbrePage contient une valeur supérieure à 1
				if ($NbrePage > 1) {
					//Vérification : si la variable Page contient une valeur supérieure à 1
					if ($page > 1) { ?>
						<li>
							<a href="form_produit.php?page=<?php echo ($page - 1) ?>" class="fas fa-arrow-left" title='LienPage'>
							</a>
						</li>

						<li>
							<a href="form_produit.php?page=<?php echo ($page + 1) ?>" <?php if($NbrePage == $page){ echo "style='display:none;'";} ?> class="fas fa-arrow-right" title='LienPage'>
							</a>
						</li>

			<?php	
					//Vérification : si la variable Page contient une valeur inférieure à 1
					} else { ?>
						<li>
							<a href="form_produit.php?page=<?php echo ($page + 1) ?>" class="fas fa-arrow-right" title='LienPage'>
							</a>
						</li>
			<?php	}
				} ?>
			</ul>
		</p>
	</div>
	<!--Inclusion du pied de la page-->	
	<?php require "../foot.php"; ?>
</body>
</html>