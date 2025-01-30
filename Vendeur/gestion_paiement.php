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
	<title>Gestion paiement</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/all.css">
	<link rel="stylesheet" type="text/css" href="../menu.css">
	<link rel="stylesheet" type="text/css" href="../foot.css">
	<link rel="stylesheet" type="text/css" href="gestion_paiement.css">
</head>
<body>
	<?php
		require "../menu.php";
	?>
	<div id="box_form">
		<form>
			
			<label for="jour">Jour</label>
			<input type="number" name="jour" id="jour" class="recherche_paie">

			<label for="mois">Mois</label>
			<input type="number" name="mois" id="mois" class="recherche_paie">

			<label for="mois">Année</label>
			<input type="number" name="annee" id="annee" class="recherche_paie">
			
		</form>
	</div>

	<div id="affiche_paie">
		
	</div>

	<script type="text/javascript">
		//Fonction de récupération des données
		function rec_donnees(){
			//Récupération des valeurs des champs
			let jour = document.getElementById("jour").value;
			let mois = document.getElementById("mois").value;
			let annee = document.getElementById("annee").value;

			//Instanciation de l'objet permettant de faire la requête
			let rq_http = new XMLHttpRequest();

			//Récupération du résultat venant du code php
			rq_http.onreadystatechange = function(){
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("affiche_paie").innerHTML = this.responseText;
				}
			};
			//Ouverture de la requête
			rq_http.open("POST", "affiche_paiement.php", true);
			rq_http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			rq_http.send("jour=" + jour + "&mois=" + mois + "&annee=" + annee);
		}

		function ecouteur_evenement_input(){
			//Récupération des propriétés des éléments ayant la même classe
			let elements = document.querySelectorAll(".recherche_paie");
			//Parcours de tous les éléments à l'insertion des valeurs
			elements.forEach(function(element) {

				element.addEventListener("input", function() {
                    rec_donnees(); // Envoyer les données à chaque changement
                    document.getElementById("tableau").style.display="none";
                    document.getElementById("BoxBtnTel").style.display="none";
                });	
			});
			
		}

		window.onload = function(){
			ecouteur_evenement_input();
		}
	</script>

	<?php
		date_default_timezone_set("Africa/Kinshasa");
		setlocale(LC_TIME, ["fr", 'fra', "fr_FR"]);
		$J = strftime("%d");
		$M = strftime("%m");
		$A = strftime("%Y");
		

		$pag = (isset($_GET['page'])) ? (!empty($_GET['page'])) ? (preg_match("/[0-9]/", $_GET['page']))? $_GET['page'] : 1 : 1 : 1 ;
		$Tail = (!empty($_POST['Taille'])) ? $_POST['Taille'] : 5 ;

		if (preg_match("/[0-9]/", $pag)) {
			$off = ($pag-1)*$Tail;
		} else {
			$off = 0;
		}

		$RequteNbrPaie = $connexion->prepare("SELECT count(*) NbrePaie FROM `paiement` AS `P` INNER JOIN `agent` AS `A` ON A.PK_Agent = P.Agent INNER JOIN `commande` AS `C` ON C.PK_Cmd = P.Commande INNER JOIN temps AS `T` ON T.PK_Temps = C.Date_Cmd WHERE P.Observation='PAYE' AND T.Jour = '$J' AND T.Mois = '$M' AND T.Annee = '$A' AND P.Agent = $id_agent LIMIT $Tail");
		$RequteNbrPaie->execute();
		$RePaie = $RequteNbrPaie->fetch(PDO::FETCH_ASSOC);
		$NbrPaie = $RePaie["NbrePaie"];
		$RestPaie = ($NbrPaie % $Tail);

		if ($RestPaie == 0) {
			$NbrPage = ($NbrPaie / $Tail);
		} else {
			$NbrPage = floor($NbrPaie / $Tail) + 1;
		}
	?>

	<div id="BoxBtnTel" <?php if(empty($NbrPaie)){echo "style='display:none'";} ?>>
		<p>
			<a href="Impression_Inventaire.php?jour=<?php echo $J ?>&mois=<?php echo $M ?>&annee=<?php echo $A ?>" class="fas fa-download" title="Téléchargement du rapport journalier de vente" target="_blank" rel="noopener noreferrer"></a>
		</p>
	</div>
		<table id="tableau">
			<thead>
				<tr>
					<th colspan="2">LISTE DES PAIEMENTS</th>
				</tr>
				<tr>
					<th>N° Paiement</th>
					<th>Montant Paiement</th>
				</tr>
			</thead>
			<tbody>

			<?php

			$RequeteP = $connexion->prepare("SELECT * FROM `paiement` AS `P` INNER JOIN `agent` AS `A` ON A.PK_Agent = P.Agent INNER JOIN `commande` AS `C` ON C.PK_Cmd = P.Commande INNER JOIN temps AS `T` ON T.PK_Temps = C.Date_Cmd WHERE P.Observation='PAYE' AND T.Jour = '$J' AND T.Mois = '$M' AND T.Annee = '$A' AND P.Agent = $id_agent ORDER BY P.PK_Paie LIMIT $Tail offset $off");
			$RequeteP->execute();

			$SelectionSom = $connexion->prepare("SELECT SUM(Mont_Paie) MontPaie FROM `paiement` AS `P` INNER JOIN `agent` AS `A` ON A.PK_Agent = P.Agent INNER JOIN `commande` AS `C` ON C.PK_Cmd = P.Commande INNER JOIN temps AS `T` ON T.PK_Temps = C.Date_Cmd WHERE P.Observation='PAYE' AND T.Jour = '$J' AND T.Mois = '$M' AND T.Annee = '$A' AND P.Agent = $id_agent");
			$SelectionSom->execute();

			$ResultMontPaie = $SelectionSom->fetch(PDO::FETCH_ASSOC);
				
			while ($ResultatP = $RequeteP->fetch(PDO::FETCH_ASSOC)) { 
				?>
				<tr>
					<td>
						<?php echo $ResultatP["PK_Paie"]; ?>
					</td>

					<td>
						<?php echo $ResultatP["Mont_Paie"]." FC"; ?>
					</td>
				</tr>
				<?php
			}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2">TOTAL : <?php if($ResultMontPaie["MontPaie"] < 1){echo 0;}else{ echo $ResultMontPaie["MontPaie"];} ?> FC</th>
				</tr>
			</tfoot>
		</table>

		<div id="BoxBtnDefilement">
			<p>
				<ul>
				<?php
					if ($NbrPage > 1) {
						if ($pag > 1) { ?>
							<li>
								<a href="gestion_paiement.php?page=<?php echo ($pag - 1) ?>" class="fas fa-arrow-left" title='LienPage'>
								</a>
							</li>

							<li>
								<a href="gestion_paiement.php?page=<?php echo ($pag + 1) ?>" <?php if($NbrPage == $pag){ echo "style='display:none;'";} ?> class="fas fa-arrow-right" title='LienPage'>
								</a>
							</li>
				<?php	} else { ?>
							<li>
								<a href="gestion_paiement.php?page=<?php echo ($pag + 1) ?>" class="fas fa-arrow-right">
								</a>
							</li>
				<?php	}
					} ?>
				</ul>
			</p>
		</div>
	<?php
		require "../foot.php";
	?>
</body>
</html>