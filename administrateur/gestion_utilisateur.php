<?php
	session_start();
	require('../Connexion.php');

	$id_admin = (array_key_exists("admin", $_SESSION)) ? $_SESSION["admin"]: 0;
	if (empty($id_admin)) {
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
		<link rel="stylesheet" type="text/css" href="../foot.css">
		<style type="text/css">
		*{
			margin: 0px;
			padding:0px;
			box-sizing: border-box;
			font-size: large;
			font-family: "bell mt";
			color: black;
		}
		body{
			background-size: cover;
			height: 100%;
			padding: 50px 0px 0px 0px;
			display: flex;
			flex-direction: column;
			justify-content: center;
			overflow-x: hidden;
		}
		@media screen and (max-width: 259px){
			*{
				font-size: small;
			}
			body #box_form form {
				display: grid;
			}
			body #box_form form input{
				width: 150px;
			}
		} 
		@media all and (min-width: 260px) and (max-width: 491px){
			*{
				font-size: 16px;
			}
			body #box_form form {
				display: grid;
			}
			body #box_form form input{
				width: 150px;
			}
		}
		body #box_form{
			display: flex;
			margin-top: 20px;
			justify-content: center;
			align-items: center;
		}
		body #box_form form input{
			padding: 0px 0px 0px 10px;
			height: 25px;
			border: 2px solid #34495E;
			border-radius: 10px;
			color: #34495E;
			font-weight: bold;
		}
		@media all and (min-width: 492px){
			body #box_form form input{
				width: 100px;
			}
		}
		body table{
			border-collapse: collapse;
			margin-top: 20px;
			width: 100%;
			text-align: center;
		}
		body table th{
			background: #34495E;
			color: white;
		}
		body table thead tr:first-child th{
			border-bottom: 3px solid white;
		}
		body table td,
		body table th{
			border: 2px solid #34495E;
			font-weight: bold;
			height: 50px;
		}
		body table td{
			color: #34495E;
		}
		body table tbody a{
			text-decoration: none;
		}
		body table tbody .fa-download,
		body table tbody .fa-edit,
		body table tbody .fa-check,
		body table tbody .fa-xmark{
		  color: rgb(14, 209, 69);
		}
		body table tbody .fa-trash{
			color: red;
		}
		body #BoxBtnDefilement{
			height: 30px;
			margin: 20px 0px 10px 0px;
		}
		body #BoxBtnDefilement ul{
			height: 100%;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		body #BoxBtnDefilement li{
			height: 100%;
			display: flex;
			align-items: center;
			justify-content: center;
			list-style-type: none;
		}
		body #BoxBtnDefilement a{
			border: 2px solid #34495E;
			text-decoration: none;
			display: flex;
			align-items: center;
			justify-content: center;
			height: 100%;
			width: 70px;
			margin: 0px 10px 0px 0px;
			border-radius: 10px;
			color: #34495E;
		}
		body #BoxBtnDefilement a:hover{
			background: #34495E;
			color: white;
			font-weight: bold;
			transition: 1.5s;
		}
		</style>
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
												
				$requeteGest = $connexion->prepare("SELECT * FROM `agent` AS A LIMIT $TailleGest OFFSET $offsetGest");

					$requeteGestionnaire = $connexion->prepare("SELECT count(*) NbreGest FROM `agent`");
												
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
				rq_http.open("POST", "affiche_utilisateur.php", true);
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
						LISTE DES VENDEURS
					</th>
				</tr>
				<tr>
					<th>NOM</th>
					<th>ETAT</th>
					<th>ACTION</th>
				</tr>
			</thead>
				<?php
				while ($resultatGest = $requeteGest->fetch(PDO::FETCH_ASSOC)) {
				?>
			<tbody>
				<tr> 
					<td>
						<?php echo $resultatGest['Nom_Agent']; ?>
					</td>

					<td>
						<?php echo $resultatGest['Etat']; ?>
					</td>
					
					<td>
						<a href="activation_vendeur.php?xxx=<?php echo $resultatGest["PK_Agent"] ?>" <?php if ($resultatGest["Etat"] == "A") { echo 'class="fas fa-check"';} else {echo 'class="fas fa-xmark"';}?>>
						</a>
						
						<a class="fas fa-trash" onclick="return confirm('Êtes-vous sûr de pouvoir effectuer cette action ?')" href="suppression_vendeur.php?xxx=<?php echo $resultatGest["PK_Agent"];?>"></a>
						<a class="fas fa-edit" href="mise_a_jour_vendeur.php?xxx=<?php echo $resultatGest["PK_Agent"];?>"></a>
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
								<a href="gestion_utilisateur.php?page=<?php echo ($pageGest - 1) ?>" class="fas fa-arrow-left" title='LienPage'>
								</a>
							</li>

							<li>
								<a href="gestion_utilisateur.php?page=<?php echo ($pageGest + 1) ?>" <?php if($NbrePageGest == $pageGest){ echo "style='display:none;'";} ?> class="fas fa-arrow-right" title='LienPage'>
								</a>
							</li>
				<?php	} else { ?>
							<li>
								<a href="gestion_utilisateur.php?page=<?php echo ($pageGest + 1) ?>" class="fas fa-arrow-right">
								</a>
							</li>
				<?php	}
					} ?>
				</ul>
			</p>
		</div>

		<div id="BoxBtnTel">
			<ul>
			<?php
				if ($NbrePageGest > 1) {
					if ($pageGest > 1) { ?>
						<li>
							<a class="fas fa-arrow-left" href="GestionUtilisateur.php?page2=<?php echo ($pageGest - 1) ?>" >
							</a>
						</li>

						<li>
							<a class="fas fa-arrow-right" href="GestionUtilisateur.php?page2=<?php echo ($pageGest + 1) ?>" <?php if($NbrePageGest == $pageGest){ echo "style='display:none;'";} ?>>
							</a>
						</li>
			<?php	} else { ?>
						<li>
							<a class="fas fa-arrow-right" href="GestionUtilisateur.php?page2=<?php echo ($pageGest + 1) ?>" >
							</a>
						</li>
			<?php	}
												
				} ?>
			</ul>
		</div>	

		<?php require "../foot.php"; ?>
	</body>
</html>