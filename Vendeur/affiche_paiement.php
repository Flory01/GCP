<?php
	session_start();
	require("../Connexion.php");
	$id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
	if (empty($id_agent)) {
		header("location:../Index.php");
	}

	date_default_timezone_set("Africa/Kinshasa");
	setlocale(LC_TIME, ["fr", 'fra', "fr_FR"]);
	$Jour = strftime("%d");
	$Mois = strftime("%m");
	$Annee = strftime("%Y");

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		# code...
		$jour = (!empty($_POST['jour'])) ? $_POST['jour'] : $Jour ;
		
		$mois = (!empty($_POST['mois'])) ? $_POST['mois'] : $Mois ;
		
		$annee = (!empty($_POST['annee'])) ? $_POST['annee'] : $Annee ;

		$RequteNbrePaie = $connexion->prepare("SELECT count(*) NbrePaie FROM `paiement` AS `P` INNER JOIN `agent` AS `A` ON A.PK_Agent = P.Agent INNER JOIN `commande` AS `C` ON C.PK_Cmd = P.Commande INNER JOIN temps AS `T` ON T.PK_Temps = C.Date_Cmd WHERE P.Observation='PAYE' AND T.Jour = '$jour' AND T.Mois = '$mois' AND T.Annee = '$annee' AND P.Agent = $id_agent");
		$RequteNbrePaie->execute();
		$ResPaie = $RequteNbrePaie->fetch(PDO::FETCH_ASSOC);
		$NbrePaie = $ResPaie["NbrePaie"];
		?>
		<div id="BoxBtnTel" <?php if(empty($NbrePaie)){echo "style='display:none'";} ?>>
			<p>
				<a href="Impression_Inventaire.php?jour=<?php echo $jour ?>&mois=<?php echo $mois ?>&annee=<?php echo $annee ?>" class="fas fa-download" title="Téléchargement du rapport journalier de vente" target="_blank" rel="noopener noreferrer"></a>
			</p>
		</div>
		<table>
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

			$RequetePaie = $connexion->prepare("SELECT * FROM `paiement` AS `P` INNER JOIN `agent` AS `A` ON A.PK_Agent = P.Agent INNER JOIN `commande` AS `C` ON C.PK_Cmd = P.Commande INNER JOIN temps AS `T` ON T.PK_Temps = C.Date_Cmd WHERE P.Observation='PAYE' AND T.Jour = '$jour' AND T.Mois = '$mois' AND T.Annee = '$annee' AND P.Agent = $id_agent");
			$RequetePaie->execute();

			$SelectionSomme = $connexion->prepare("SELECT SUM(Mont_Paie) MontPaie FROM `paiement` AS `P` INNER JOIN `agent` AS `A` ON A.PK_Agent = P.Agent INNER JOIN `commande` AS `C` ON C.PK_Cmd = P.Commande INNER JOIN temps AS `T` ON T.PK_Temps = C.Date_Cmd WHERE P.Observation='PAYE' AND T.Jour = '$jour' AND T.Mois = '$mois' AND T.Annee = '$annee' AND P.Agent = $id_agent");
			$SelectionSomme->execute();

			$ResultatMontPaie = $SelectionSomme->fetch(PDO::FETCH_ASSOC);
				
			while ($ResultatPaie = $RequetePaie->fetch(PDO::FETCH_ASSOC)) { 
				?>
				<tr>
					<td>
						<?php echo $ResultatPaie["PK_Paie"]; ?>
					</td>

					<td>
						<?php echo $ResultatPaie["Mont_Paie"]." FC"; ?>
					</td>
				</tr>
				<?php
			}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2">TOTAL : <?php if($ResultatMontPaie["MontPaie"] < 1){echo 0;}else{ echo $ResultatMontPaie["MontPaie"];} ?> FC</th>
				</tr>
			</tfoot>
		</table>
	<?php
	}
?>