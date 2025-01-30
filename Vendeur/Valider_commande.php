<?php
	session_start();
	require("../Connexion.php");

	$id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
	if (empty($id_agent)) {
		header("location:../Index.php");
	}
	
	if ($_SERVER["REQUEST_METHOD"]=="POST") {
		$PK = $_POST["IdVte"];
	}

	$IdVte = (isset($_GET['pr'])) ? (!empty($_GET['pr'])) ? (preg_match("/^[0-9]*$/", $_GET['pr'])) ? $_GET['pr'] : header("location:gestion_produit.php") : header("location:gestion_produit.php") : $PK ;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Formulaire Client</title>
	<link rel="stylesheet" type="text/css" href="validation_commande.css">
	<link rel="stylesheet" type="text/css" href="../css/all.css">
	<link rel="stylesheet" type="text/css" href="../menu.css">
	<link rel="stylesheet" type="text/css" href="../foot.css">
</head>
<body>
	<?php
		require "../menu.php";
		$IdAgent = $Obs = $MotDePasse = $ErrMotDePasse = "";

		if ($_SERVER["REQUEST_METHOD"]=="POST") {
			if (!empty($_POST['Agent'])) {
				$IdAgent = $_POST['Agent'];
				$Obs = "PAYE";
			}

			if (empty($_POST['MotDePasse'])) {
				$ErrMotDePasse = '<p id = "ErrMsg">Veuillez insérer le mot de passe svp</p>';
			} else {
				if (preg_match("/^[a-zA-Z0-9]*$/", $_POST['MotDePasse'])) {

					$rq_sel_ag = $connexion->prepare("SELECT * FROM `agent` WHERE PK_Agent = $IdAgent");
					$rq_sel_ag->execute();
					$rs_sel_ag = $rq_sel_ag->fetch(PDO::FETCH_ASSOC);

					if (password_verify($_POST['MotDePasse'], $rs_sel_ag["MotDePasse"])) {
						# code...
						$MotDePasse = $_POST['MotDePasse'];
					}else{
						$ErrMotDePasse = '<p id = "ErrMsg">Mot de passe incorrecte</p>';
					}
					
				} else {
					$ErrMotDePasse = '<p id = "ErrMsg">Evitez les caratères spéciaux svp</p>';
				}
											
			}

			if (!empty($IdAgent) AND !empty($MotDePasse)) {

				//Validation du paiement associé à la commande
				$RequetMaj = $connexion->prepare("UPDATE `paiement` SET Observation = '$Obs', Agent = $IdAgent WHERE Commande = $IdVte");
				$RequetMaj->execute();

				$RequetMajVente = $connexion->prepare("UPDATE `commande` SET Agent = $id_agent, `livraison` = 'OUI' WHERE PK_Cmd = $IdVte");
				$RequetMajVente->execute();
				header("refresh: 5; url=gestion_produit.php");
	
			}	
		}
		
	?>
<div id="BoxForm">
	<form id="Formulaire_commande" <?php if (!empty($IdAgent) AND !empty($MotDePasse)) {echo "style='display: none;'";} else {echo "style='display: grid;'";} ?> action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<h3>
			Formulaire Agent
		</h3>
		<div id="BoxAgent">
			<input type="number" name="IdVte" style="display: none;" value="<?php echo $IdVte ; ?>">
			<div>
				<label for="Agent">Agent</label>
				<select name="Agent" id="Agent">
					<?php
						$RequeteAgent = $connexion->prepare("SELECT * FROM `agent` WHERE PK_Agent = $id_agent");
						$RequeteAgent->execute();
							
						while ($ResultatAgent = $RequeteAgent->fetch(PDO::FETCH_ASSOC)) { 
							$Agent = $ResultatAgent["PK_Agent"];
							?>
							<option value="<?php echo $Agent ; ?>" <?php if($IdAgent == $ResultatAgent["PK_Agent"]){echo "selected";} ?>>
									<?php echo $ResultatAgent["Nom_Agent"]; ?>
							</option>		
				<?php	}
						?>
				</select>
			</div>
			<div>
				<label for="MotDePasse">Mot de passe</label>
				<input type="password" name="MotDePasse" id="MotDePasse">
			</div>
			
			<?php echo $ErrMotDePasse ?>
		</div>

		<div id="BoxBtnValide">
			<input type="submit" name="BtnValider" value="Valider">	
		</div>
		
	</form>
	<div id="MsgSucces" <?php if (!empty($IdAgent) AND !empty($MotDePasse)) {echo "style='display: flex;'";} ?>>
		<p>
			Commande validée avec succès.
		</p>
	</div>
</div>
<?php require "../foot.php"; ?>
</body>
</html>