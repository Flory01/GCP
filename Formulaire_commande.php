<?php
	//Ouverture de la session
	session_start();

	//Rédirection à la page d'accueil si un de produit comprend la quantité zéro
	if (in_array(0, $_SESSION['panier']['qteProduit'])) {
		header("location:Index.php");
	}
	require("fonctions_paniers.php");
	//Inclusion du fichier permettant de se connecter à la base de données
	require("Connexion.php");
?>
<!--Début de la structure de la page-->
<!DOCTYPE html>
<html>
<!--Entête de la page-->
<head>
	<title>Formulaire Client</title>
	<link rel="stylesheet" type="text/css" href="formulaire_commande.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<link rel="stylesheet" type="text/css" href="menu.css">
	<link rel="stylesheet" type="text/css" href="foot.css">
</head>
<!--Corps de la page-->
<body>

<?php
	
	if ($_SERVER["REQUEST_METHOD"]=="POST") {
		$_SESSION["qte"] = isset($_POST["q"]) ? $_POST["q"] : [];
		
		if (count($_SESSION["qte"]) > 0 ) {
		
			foreach ($_SESSION["qte"] as $key => $value) {
				$lib = $_SESSION['panier']['libelleProduit'][$key];
				$qte = $value;
				$qte_stock = $_SESSION['panier']['qteStock'][$key];
				modifierQTeArticle($lib,$qte,$qte_stock);
			}
		}
	}
		//Inclusion de la page contenant le menu
		require "menu.php";

		//Déclaration des variables
		$Nom_Cl = $PostNom_Cl = $Tel_Cl = $ErrNom = $ErrPostNom = $ErrTel = $Genre_Cl = $ErrAdresse = $Adresse = "";
		$NewTab = [];
		$Qte_total = $Mont_Paie = 0;
		for ($i=0; $i < count($_SESSION['panier']['libelleProduit']); $i++){ 
			$Qte_total += intval($_SESSION['panier']['qteProduit'][$i]);

		}
		//Affectation du montant total du panier à la variable Mont_paie
		$Mont_Paie = MontantGlobal();
		//Vérification : si le formulaire est envoyé
		if (isset($_POST["BtnValider"])) {
			//Vérification : si la variable de récupération du nom du client n'est pas vide
			if (!empty($_POST["Nom_Cl"])) {
				//Vérification : si le nom du client ne contient que des lettres
				if (preg_match("/^[a-zA-Z]*$/", $_POST['Nom_Cl'])) {
					//Affectation,convertion du nom en majuscul et suppression des espaces aux extrémités
					$Nom_Cl = trim(strtoupper($_POST['Nom_Cl']));
				} else {
					//Affectation de message d'erreur
					$ErrNom = "<p class='Erreur'>Evitez les caractères spéciaux</p>";
				}	
			} else {
				//Affectation de message d'erreur
				$ErrNom = "<p class='Erreur'>Veuillez remplir ce champ</p>";
			}
			//Vérification : si la variable de récupération du post nom du client n'est pas vide
			if (!empty($_POST["PostNom_Cl"])) {
				//Vérification : si le post nom du client ne contient que des lettres
				if (preg_match("/^[a-zA-Z]*$/", $_POST['PostNom_Cl'])) {
					//Affectation, convertion du nom en majuscul et suppression des espaces aux extrémités
					$PostNom_Cl = trim(strtoupper($_POST['PostNom_Cl']));
				} else {
					//Affectation de message d'erreur
					$ErrPostNom = "<p class='Erreur'>Evitez les caractères spéciaux</p>";
				}
				
			} else {
				//Affectation de message d'erreur
				$ErrPostNom = "<p class='Erreur'>Veuillez remplir ce champ</p>";
			}

			if (empty($_POST['Adresse'])) {
				$ErrAdresse = '<p class = "Erreur">Insérez l\'adresse Svp</p>';
			} else {
				if (preg_match("/^[0-9a-zA-Z,\/\s]*$/", $_POST['Adresse'])) {
					$Adresse = trim(strtoupper($_POST['Adresse']));
				} else {
					$ErrAdresse = '<p class = "Erreur">Evitez les caractères spéciaux</p>';
				}
			}
			//Vérification : si la variable de récupération du post numéro de téléphone du client n'est pas vide
			if (!empty($_POST["Tel_Cl"])) {
				//Vérification : si le numéro du client ne contient que des nombres entiers
				if (preg_match("/^[0-9]*$/", $_POST['Tel_Cl'])) {
					//Vérification : si la taille du numéro de téléphone est inférieure à 10
					if (strlen($_POST['Tel_Cl']) < 10 ) {
						//Affectation de message d'erreur
						$ErrTel = "<p class='Erreur'>Numéro incorrect</p>";
					//Vérification : si la taille du numéro de téléphone est supérieure à 10
					} else if(strlen($_POST['Tel_Cl']) > 10 ){
						//Affectation de message d'erreur
						$ErrTel = "<p class='Erreur'>Numéro incorrect</p>";
					}else{
						//Affectation du numéro de téléphone du client
						$Tel_Cl = trim($_POST['Tel_Cl']);
					}
				} else {
					//Affectation de message d'erreur
					$ErrTel = "<p class='Erreur'>Evitez les caractères spéciaux</p>";
				}
				
			} else {
				//Affectation de message d'erreur
				$ErrTel = "<p class='Erreur'>Veuillez remplir ce champ</p>";
			}
			//Vérification : si la variable de récupération du genre du client n'est pas vide
			if (!empty($_POST['Genre_Cl'])) {
				//Affectation du genre
				$Genre_Cl = $_POST['Genre_Cl'];
			}

			//Instructions à exécuter si tous les valeurs des variables ci-haut sont correctes
			if (!empty($Nom_Cl) AND !empty($PostNom_Cl)  AND !empty($Tel_Cl) AND !empty($Genre_Cl) AND !empty($Adresse) ) {

				//Insertion des informations du client
				$RequetInsertCl= $connexion->prepare("INSERT INTO `client` (Nom_Cl, PostNom_Cl,Tel_Cl,Genre_Cl, adresse) SELECT :Nom, :PostNom, :Tel, :Genre, :adresse FROM DUAL WHERE NOT  EXISTS(SELECT * FROM `client` WHERE Nom_Cl = '$Nom_Cl' AND Tel_Cl = '$Tel_Cl' AND PostNom_Cl = '$PostNom_Cl' AND Genre_Cl=$Genre_Cl AND adresse = '$Adresse')");
				$RequetInsertCl->BindParam(":Nom",$Nom_Cl);
				$RequetInsertCl->BindParam(":PostNom",$PostNom_Cl);
				$RequetInsertCl->BindParam(":Tel",$Tel_Cl);
				$RequetInsertCl->BindParam(":Genre",$Genre_Cl);
				$RequetInsertCl->BindParam(":adresse",$Adresse);
				$RequetInsertCl->execute();

				//Selection des informations du client
				$RequetSelectCl= $connexion->prepare("SELECT * FROM client WHERE Nom_Cl = '$Nom_Cl' AND Tel_Cl = '$Tel_Cl' AND PostNom_Cl = '$PostNom_Cl' AND Genre_Cl=$Genre_Cl AND adresse = '$Adresse'");
				$RequetSelectCl->execute();
				$ResultatSelectCl = $RequetSelectCl->fetch(PDO::FETCH_ASSOC);
				$IdCl= $ResultatSelectCl["PK_Cl"];

				//Insertion de la date se rapportant à la commande
				date_default_timezone_set("Africa/Kinshasa");
				setlocale(LC_TIME, ["fr", 'fra', "fr_FR"]);
				$Jour = strftime("%d");
				$Mois = strftime("%m");
				$Annee = strftime("%Y");
				$Heure = strftime("%H");
				$Minute = strftime("%M");
				$Seconde = strftime("%S");

				//Insertion Date vente
				$RequetInsertDateVte= $connexion->prepare("INSERT INTO `temps` SELECT NULL, :jour, :mois, :annee, :heure, :minute, :seconde FROM DUAL WHERE NOT  EXISTS(SELECT * FROM `temps` WHERE Jour='$Jour' AND Mois = '$Mois' AND Annee = '$Annee' AND Heure = '$Heure' AND Minute = '$Minute' AND Seconde = '$Seconde')");
				$RequetInsertDateVte->BindParam(":jour",$Jour);
				$RequetInsertDateVte->BindParam(":mois",$Mois);
				$RequetInsertDateVte->BindParam(":annee",$Annee);
				$RequetInsertDateVte->BindParam(":heure",$Heure);
				$RequetInsertDateVte->BindParam(":minute",$Minute);
				$RequetInsertDateVte->BindParam(":seconde",$Seconde);
				$RequetInsertDateVte->execute();

				//Selection Date commande
				$RequetSelectDateVte= $connexion->prepare("SELECT * FROM temps WHERE Jour='$Jour' AND Mois = '$Mois' AND Annee = '$Annee' AND Heure = '$Heure' AND Minute = '$Minute' AND Seconde = '$Seconde'");
				$RequetSelectDateVte->execute();
				$ResultatSelectDateVte = $RequetSelectDateVte->fetch(PDO::FETCH_ASSOC);
				$IdDateVte = $ResultatSelectDateVte["PK_Temps"];

				//Insertion Commande
				$RequetInsertVte= $connexion->prepare("INSERT INTO `commande`(Client, Date_Cmd, Qte_Cmd) SELECT :client, :DateCmd, :Qte_Cmd FROM DUAL WHERE NOT  EXISTS(SELECT * FROM `commande` WHERE Date_Cmd=$IdDateVte)");
				$RequetInsertVte->BindParam(":DateCmd",$IdDateVte);
				$RequetInsertVte->BindParam(":client",$IdCl);
				$RequetInsertVte->BindParam(":Qte_Cmd",$Qte_total);
				$RequetInsertVte->execute();

				//Selection Vente
				$RequetSelectVte= $connexion->prepare("SELECT * FROM commande AS V INNER JOIN `temps` AS `T` WHERE V.Date_Cmd=$IdDateVte AND T.Jour = '$Jour' AND T.Mois = '$Mois' AND T.Annee = '$Annee' AND T.Heure = '$Heure' AND T.Minute = '$Minute' AND T.Seconde = '$Seconde'");
				$RequetSelectVte->execute();
				$ResultatSelectVte = $RequetSelectVte->fetch(PDO::FETCH_ASSOC);
				$IdVte= $ResultatSelectVte["PK_Cmd"];

			    //Mise à jour, Selection du produit modifié & Insertion commande
			    for ($i=0; $i < count($_SESSION['panier']['libelleProduit']); $i++) { 
			    	$LibProd =  $_SESSION['panier']['libelleProduit'][$i];
			
			    	$QteProd =  $_SESSION['panier']['qteProduit'][$i];
			    	
			    	$PrixProd =  $QteProd*($_SESSION['panier']['prixProduit'][$i]);
			    	
			    	//Selection du produit avant modification
			    	$RequetSelect = $connexion->prepare("SELECT * FROM produit WHERE Lib_Pro = '$LibProd'");
			    	$RequetSelect->execute();
					$ResultatSelect = $RequetSelect->fetch(PDO::FETCH_ASSOC);
					$IdPro= $ResultatSelect["PK_Pro"];

			    	//Insertion de la commande
					$RequetInsertCmd = $connexion->prepare("INSERT INTO `reference` SELECT NULL, :Prod, :Commande, :Qte_Ref, :Prix_Ref FROM DUAL WHERE NOT  EXISTS(SELECT * FROM `reference` WHERE Produit=$IdPro AND Commande=$IdVte AND Qte_Ref = $QteProd)");
					$RequetInsertCmd->BindParam(":Prod",$IdPro);
					$RequetInsertCmd->BindParam(":Commande",$IdVte);
					$RequetInsertCmd->BindParam(":Qte_Ref",$QteProd);
					$RequetInsertCmd->BindParam(":Prix_Ref",$PrixProd);
					$RequetInsertCmd->execute();
			    }

			    //Insertion de la paiement
				$RequetInsertPaie= $connexion->prepare("INSERT INTO `paiement`(Client, Commande, Mont_Paie) SELECT :Cl, :Commande, :Montant FROM DUAL WHERE NOT  EXISTS(SELECT * FROM `paiement` WHERE Client = $IdCl AND Commande = $IdVte)");
				$RequetInsertPaie->BindParam(":Cl",$IdCl);
				$RequetInsertPaie->BindParam(":Commande",$IdVte);
				$RequetInsertPaie->BindParam(":Montant",$Mont_Paie);
				$RequetInsertPaie->execute();
				header("refresh: 6; url=index.php");
			}
		}
		
		//Rédirection à la page d'accueil si un de produit comprend la quantité zéro
		if (in_array(0, $_SESSION['panier']['qteProduit'])) {
			header("location:Index.php");
		}
	?>
	<!--Formulaire comprenant le panier des biens-->
	<div id="Panier" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<!--Début du panier des biens-->
		<table>
			<!--Ligne contenant l'entête du panier-->
			<tr>
			    <th colspan="4">Panier des biens</th>
			</tr>

			<?php
				//Vérification de l'existance du panier
				if (creationPanier()){ 
					//Affectation du nombre des produits existants dans le panier
				   	$nbArticles=count($_SESSION['panier']['libelleProduit']);
				   	//Vérification : si le nombre des produits existants dans le panier est inférieur ou égale à zéro
				    if ($nbArticles <= 0){
				       	//Rédirection vers la page d'accueil si la taille du tableau contenant les quantités des produits est inférieure ou égale à zéro
				       	if (count($_SESSION['panier']['qteProduit']) <= 0) {
				       			header("location:form_produit.php");
				       	}
				    }else{ ?>

				       	<tr>
						    <th>Libellé</th>
						    <th>Quantité</th>
						</tr>

				    <?php
				    	//Boucle pour parcourir tous les éléments du panier
				    	for ($i=0 ;$i < $nbArticles ; $i++){ 
				      		$NomProd = $_SESSION['panier']['libelleProduit'][$i];
				      	?>
				       		<tr>
				       			<th>
				       				<?php echo htmlspecialchars($_SESSION['panier']['libelleProduit'][$i]) ?>
				       			</th>

					            <td>
					            	<?php echo htmlspecialchars($_SESSION['panier']['qteProduit'][$i]) ?>
					            </td>
					            
					        </tr>
				<?php  	} ?>
				          	<tr>
				          		<td id='Total' colspan='4'>
				          			<?php echo "Total : ".MontantGlobal()." FC"; ?> 
				          		</td>
				          	</tr>
			<?php  	}
			    }
			?>
		</table>
	</div>

	<!--Zone contenant le formulaire du client-->
	<div id="BoxForm">
		<!--Formulaire du client-->
		<form id="Formulaire_commande" <?php if (!empty($Nom_Cl) AND !empty($PostNom_Cl)  AND !empty($Tel_Cl) AND !empty($Genre_Cl)) {echo "style='display: none;'";} else {echo "style='display: grid;'";} ?> action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<!--Zone contenant le nom du client-->
			<div class="Nom">
				<label for="Nom_Cl">Nom </label>
				<input type="text" name="Nom_Cl" id="Nom_Cl" maxlength="20" value="<?php echo $Nom_Cl; ?>">
				<?php echo $ErrNom; ?>
			</div>
			<!--Zone contenant le post nom du client-->
			<div class="Nom">
				<label for="PostNom_Cl">Post Nom</label>
				<input type="text" name="PostNom_Cl" id="PostNom_Cl" value="<?php echo $PostNom_Cl; ?>" maxlength="20">
				<?php echo $ErrPostNom; ?>
			</div>
			<!--Zone contenant le numéro de téléphone du client-->
			<div class="Nom">
				<label for="Tel_Cl">N° Tel</label>
				<input type="text" name="Tel_Cl" id="Tel_Cl" value="<?php echo $Tel_Cl; ?>" maxlength="10">
				<?php echo $ErrTel; ?>
			</div>

			<div class="Nom">
				<label for="Adresse">Adresse</label>
				<input  type="text" name="Adresse" id="Adresse" value="<?php echo $Adresse ?>">
				<?php echo $ErrAdresse ?>
			</div>
			<!--Zone contenant le genre du client-->
			<div id="Genre">
				<label for="Genre_Cl">Genre</label>
				<select name="Genre_Cl" id="Genre_Cl">
					<?php
						$RequeteGenre = $connexion->prepare("SELECT * FROM `genre`");
						$RequeteGenre->execute();
							
						while ($ResultatGenre = $RequeteGenre->fetch(PDO::FETCH_ASSOC)) { 
							$Genre = $ResultatGenre["PK_Genre"];
							?>
							<option value="<?php echo $Genre ; ?>" <?php if($Genre_Cl == $ResultatGenre["PK_Genre"]){echo "selected";} ?>>
									<?php echo $ResultatGenre["Lib_Genre"]; ?>
							</option>		
				<?php	}
						?>
				</select>	
			</div>
			<!--Zone contenant le bouton de validation du formulaire-->
			<div id="BoxBtnValide">
				<input type="submit" name="BtnValider" value="Valider">	
			</div>			
		</form>
		<!--Zone contenant le message confirmant l'envoi des informations du client-->
		<div id="MsgSucces" <?php if (!empty($Nom_Cl) AND !empty($PostNom_Cl)  AND !empty($Tel_Cl) AND !empty($Genre_Cl) AND !empty($Adresse)) {echo "style='display: flex;'";supprimePanier();} ?>>
				<p>
					Commande enregistrée avec succès.
				</p>
				<p>
					Vous serez livré dans 5h maximum
				</p>
				<p>
						
					<em style="font-weight: bolder;color: red">NB :</em> La commande sera invalidée si vous ne payez pas à la livraison.
				</p>
		</div>
	</div>
	<?php require "foot.php";?>
</body>
</html>