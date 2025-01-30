<?php
	session_start();
	require('../Connexion.php');

	$id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
	if (empty($id_agent)) {
		header("location:../Index.php");
	}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Gestion des utilisateurs</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../css/all.css">
		<link rel="stylesheet" type="text/css" href="../menu.css">
		<link rel="stylesheet" type="text/css" href="gestion_client.css">
		<link rel="stylesheet" type="text/css" href="../foot.css">
	</head>
	<body>
		<?php
			require "../menu.php";
		?>
			<?php 
				$Nom = $PostNom = '';

				$TailleGest = (isset($_POST['Taille'])) ? $_POST['Taille'] : 5 ;
				$pageGest = (isset($_GET['page2'])) ? (!empty($_GET['page2'])) ? (preg_match("/[0-9]/", $_GET['page2']))? $_GET['page2'] : 1 : 1 : 1 ;

				if (preg_match("/[0-9]/", $pageGest)) {
					$offsetGest = ($pageGest-1)*$TailleGest;
				} else {
					$offsetGest = 0;
				}
												
				$requeteGest = $connexion->prepare("SELECT * FROM `client` AS C LIMIT $TailleGest OFFSET $offsetGest");

					$requeteGestionnaire = $connexion->prepare("SELECT count(*) NbreGest FROM `client`");
												
				$requeteGest->execute();
				$requeteGestionnaire->execute();

				$ResultatGestionnaire = $requeteGestionnaire->fetch(PDO::FETCH_ASSOC);
				$NbrUtilisiateur = $ResultatGestionnaire["NbreGest"];
				$ResteGest = ($NbrUtilisiateur % $TailleGest);
															
				if ($ResteGest == 0) {
					$NbrePageGest = ($NbrUtilisiateur / $TailleGest);
				} else {
					$NbrePageGest = floor($NbrUtilisiateur / $TailleGest) + 1;
				}
			?>
			
			<div id="box_form">
				<form id="box_form" method="Post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>">
					<label for="Nom">Nom</label>
					<input type="text" name="Nom" id="Nom" class="recherche_vendeur">
					
					<label for="PostNom">Post nom</label>		
					<input type="text" name="PostNom" id="PostNom" class="recherche_vendeur">

				</form>
			</div>

		<div id="affiche_utilisateur">
		
		</div>
		
		<script type="text/javascript">
			//Fonction de récupération des données
			function rec_donnees(){
				//Récupération des valeurs des champs
				let nom = document.getElementById("Nom").value;
				let post_nom = document.getElementById("PostNom").value;

				//Instanciation de l'objet permettant de faire la requête
				let rq_http = new XMLHttpRequest();

				//Récupération du résultat venant du code php
				rq_http.onreadystatechange = function(){
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("affiche_utilisateur").innerHTML = this.responseText;
					}
				};
				//Ouverture de la requête
				rq_http.open("POST", "affiche_client.php", true);
				rq_http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				rq_http.send("nom=" + nom + "&post_nom=" + post_nom);
			}

			function ecouteur_evenement_input(){
				//Récupération des propriétés des éléments ayant la même classe
				let elements = document.querySelectorAll(".recherche_vendeur");
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
		<table id="tableau">
			<thead>
				<tr>
					<th colspan="5" style="border-bottom: 4px solid #ddd;">
						LISTE DES CLIENTS
					</th>
				</tr>
				<tr>
					<th>NOM</th>
					<th>NBRE DE COMMANDE</th>
					<th>ACTION</th>
				</tr>
			</thead>
				<?php
				while ($resultatGest = $requeteGest->fetch(PDO::FETCH_ASSOC)) {
					$id_client = $resultatGest['PK_Cl'];
					$rq_sel_nbre_cmd = $connexion->prepare("SELECT count(*) nbre_cmd FROM `commande` AS C WHERE C.Client = $id_client");
					$rq_sel_nbre_cmd->execute();
					$rs_sel_nbre_cmd = $rq_sel_nbre_cmd->fetch(PDO::FETCH_ASSOC);
				?>
			<tbody>
				<tr> 
					<td>
						<?php echo $resultatGest['Nom_Cl']." ".$resultatGest['PostNom_Cl']; ?>
					</td>

					<td>
						<?php echo $rs_sel_nbre_cmd["nbre_cmd"];  ?>
					</td>
					
					<td>
						<a class="fas fa-trash" onclick="return confirm('Êtes-vous sûr de pouvoir effectuer cette action ?')" href="suppression_client.php?xxx=<?php echo $resultatGest["PK_Cl"];?>"></a>
					</td>
				</tr>
											
			</tbody>
										
		<?php   } ?>

		</table>

		<div id="BoxBtnDefilement">
			<p>
				<ul>
				<?php
					if ($NbrePageGest > 1) {
						if ($pageGest > 1) { ?>
							<li>
								<a href="gestion_client.php?page=<?php echo ($pageGest - 1) ?>" class="fas fa-arrow-left" title='LienPage'>
								</a>
							</li>

							<li>
								<a href="gestion_client.php?page=<?php echo ($pageGest + 1) ?>" <?php if($NbrePageGest == $pageGest){ echo "style='display:none;'";} ?> class="fas fa-arrow-right" title='LienPage'>
								</a>
							</li>
				<?php	} else { ?>
							<li>
								<a href="gestion_client.php?page=<?php echo ($pageGest + 1) ?>" class="fas fa-arrow-right">
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