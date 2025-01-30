<?php
	session_start();
	require("../Connexion.php");
	$id_admin = (array_key_exists("admin", $_SESSION)) ? $_SESSION["admin"]: 0;
	if (empty($id_admin)) {
		header("location:../Index.php");
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		# code...
		$nom = (!empty($_POST['nom'])) ? $_POST['nom'] : "" ;
		
		$post_nom = (!empty($_POST['post_nom'])) ? $_POST['post_nom'] : "" ;

		$requeteGest = $connexion->prepare("SELECT * FROM `agent` AS A WHERE A.Nom_Agent = '$nom' AND A.PostNom_Agent= '$post_nom'");
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
	<?php
	}
?>