<?php
	session_start();
	require("../Connexion.php");
	$id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
	if (empty($id_agent)) {
		header("location:../Index.php");
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		# code...
		$nom = (!empty($_POST['nom'])) ? $_POST['nom'] : "" ;
		
		$post_nom = (!empty($_POST['post_nom'])) ? $_POST['post_nom'] : "" ;

		$requeteGest = $connexion->prepare("SELECT * FROM `client` AS C WHERE C.Nom_Cl = '$nom' AND C.PostNom_Cl= '$post_nom'");
		$requeteGest->execute();
		?>

		<table id="tableau">
			<thead>
				<tr>
					<th colspan="5" style="border-bottom: 4px solid #ddd;">
						LISTE DES VENDEURS
					</th>
				</tr>
				<tr>
					<th>NOM</th>
					<th>NOMBRE DE COMMANDES</th>
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
						<a class="fas fa-trash" onclick="return confirm('Êtes-vous sûr de pouvoir effectuer cette action ?')" href="suppression_vendeur.php?xxx=<?php echo $resultatGest["PK_Cl"];?>"></a>
					</td>
				</tr>
											
			</tbody>
										
		<?php   } ?>

		</table>
	<?php
	}
?>