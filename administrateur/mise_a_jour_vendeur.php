<?php
	session_start();
	require('../Connexion.php');

	$id_admin = (array_key_exists("admin", $_SESSION)) ? $_SESSION["admin"]: 0;
	if (empty($id_admin)) {
		header("location:../Index.php");
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$IdAgent = $_POST["IdAgent"];
	}

	$NumAgent = (isset($_GET['xxx'])) ? (!empty($_GET['xxx'])) ? (preg_match("/^[0-9]*$/", $_GET['xxx'])) ? $_GET['xxx'] : 0 : 0 : $IdAgent ;
				
	if (preg_match("/^[0-9]*$/", $NumAgent)) {
		$Chaine ="SELECT * FROM `agent` AS A INNER JOIN `genre` AS G ON G.PK_Genre = A.Genre_Agent WHERE a.PK_Agent = '$NumAgent'";
		$Requete = $connexion->prepare($Chaine);
		$Requete->execute();
		$Resultat = $Requete->fetch(PDO::FETCH_ASSOC);
					
		if (is_array($Resultat)) {
			#Récupérations des infos de l'utilisateur
			$NumAgent = $Resultat["PK_Agent"];
			$NomAgent = $Resultat["Nom_Agent"];
			$PostNomAgent = $Resultat["PostNom_Agent"];
			$GenreAgent = $Resultat["Genre_Agent"];
			$NumTelAgent = $Resultat["Tel_Agent"];
			$EtatAgent = $Resultat["Etat"];
		}else{
			header("location:gestion_utilisateur.php");
		}
	}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Mise à jour Elève</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
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
			body #FormConnexion{
			  display: grid;
			  margin: 100px 0px 0px 0px
			  background: white;
			  box-shadow: /*OX*/0px /*OY*/0px /*Epaisseur du brouillard*/5px /*Couleur du brouillard*/rgb(0,0,0);
			  border-radius: 20px;
			  justify-content: center;
			  align-items: center;
			  color: #34495E;
			  height: 800px;
			}
			@media screen and (max-width: 400px){
			  body #FormConnexion{
			    width: 250px;
			  }
			}
			@media screen and (min-width: 401px){
			  body #FormConnexion{
			    width: 400px;
			  }
			}
			body #FormConnexion label{
			  font-weight: bold;
			}
			body #FormConnexion input{
			  border: 2px solid gray;
			  border-radius: 10px;
			  outline: none;
			}
			body #FormConnexion p{
			  border: 1px solid red;
			  background: pink;
			  height: 45px;
			  display: flex;
			  justify-content: center;
			  padding: 4px;
			  align-items: center;
			  color: red;
			  text-align: center;
			  width: 300px;
			}
			@media screen and (max-width : 400px){
			  body #FormConnexion p{
			    max-width: 200px;
			  }
			}
			@media screen and (min-width : 401px){
			  body #FormConnexion p{
			    max-width: 280px;
			  }
			}
			body #FormConnexion #BoxBtnConnexion{
			  display: flex;
			  justify-content: center;
			}
			body #FormConnexion input[type=text],
			body #FormConnexion input[type=password]{
			  height: 30px;
			  padding-left: 20px;
			}
			@media screen and (min-width: 401px){
			  body #FormConnexion input[type=text],
			  body #FormConnexion input[type=password]{
			    min-width: 280px;
			  }
			}
			body #FormConnexion input[type=text]:focus,
			body #FormConnexion input[type=password]:focus{
			  border-bottom: 4px solid gray;
			}
			body #FormConnexion input[type=submit]{
			  height: 50px;
			  width: 150px;
			}
			body #FormConnexion input[type=submit]:hover{
			  transition: 2s;
			  background: #34495E;
			  color: white;
			}
		</style>
	</head>
		
	<body>
		<?php
			require "../menu.php";
			require '../Fonctions/Fonctions.php';
			$NomAg = $ErrNomAg = $PostNomAg = $ErrPostNomAg = $FonctionAg = $ErrFonctionAg = $GenreAg = $ErrGenreAg = $NumTelAg = $ErrNumTelAg = $Mot2PasseAg = $ErrMot2PasseAg = $EtatAg = $ErrEtatAg = '';
									
			if ($_SERVER["REQUEST_METHOD"] == 'POST') {

				if (empty($_POST['NomAg'])) {
					$ErrNomAg = '<p id = "ErrMsg">Insérer le nom de l\'agent Svp</p>';
				} else {
					if (preg_match("/^[a-zA-Z]*$/", $_POST['NomAg'])) {
						$NomAg = FiltrageEL(Majuscule($_POST['NomAg']));
					} else {
						$ErrNomAg = '<p id = "ErrMsg">Evitez les caractères spéciaux Svp</p>';
					}						
				}

				if (empty($_POST['PostNomAg'])) {
					$ErrPostNomAg = '<p id = "ErrMsg">Insérer le post nom de l\'agent Svp</p>';
				} else {
					if (preg_match("/^[a-zA-Z]*$/", $_POST['PostNomAg'])) {
						$PostNomAg = FiltrageEL(Majuscule($_POST['PostNomAg']));
					} else {
						$ErrPostNomAg = '<p id = "ErrMsg">Evitez les caractères spéciaux Svp</p>';
					}					
				}

				if (empty($_POST['GenreAg'])) {
					$ErrGenreAg = '<p id = "ErrMsg">Choisissez le genre de l\'agent Svp</p>';
				} else {
					if (preg_match("/^[0-9]*$/", $_POST['GenreAg'])) {
						$GenreAg = Filtrage($_POST['GenreAg']);
					} else {
						$ErrGenreAg = '<p id = "ErrMsg">Evitez les caractères spéciaux Svp</p>';
					}
				}

				if (empty($_POST['Mot2PasseAg'])) {
					$Mot2PasseAg = '';
				} else {
					if (preg_match("/^[a-zA-Z0-9]*$/", $_POST['Mot2PasseAg'])) {
						$Mot2PasseAg = $_POST['Mot2PasseAg'];
					} else {
						$ErrMot2PasseAg = '<p id = "ErrMsg">Evitez les caractères spéciaux Svp</p>';
					}
				}

				if (empty($_POST['EtatAg'])) {
					$ErrEtatAg = '<p id = "ErrMsg">Choisissez l\'état de l\'agent Svp</p>';
				} else {
					if (preg_match("/^[a-zA-Z]*$/", $_POST['EtatAg'])) {
						$EtatAg = Filtrage($_POST['EtatAg']);
					} else {
						$ErrEtatAg = '<p id = "ErrMsg">Evitez les caractères spéciaux Svp</p>';
					}
				}

				if (empty($_POST['NumTelAg'])) {
					$ErrNumTelAg = '<p id = "ErrMsg">Choisissez l\'état de l\'agent Svp</p>';
				} else {
					if (preg_match("/^[0-9]*$/", $_POST['NumTelAg'])) {
						if (strlen($_POST['NumTelAg']) < 10) {
							$ErrNumTelAg = '<p>Le numéro inséré est incorrect</p>';
						} elseif (strlen($_POST['NumTelAg']) > 10) {
							$ErrNumTelAg = '<p>Le numéro inséré comprend plus de 10 chiffres</p>';
						} else {
								$NumTelAg = Filtrage($_POST['NumTelAg']);
						}
												
					} else {
						$ErrNumTelAg = '<p id = "ErrMsg">Evitez les caractères spéciaux Svp</p>';
					}
											
				}
										
			}
									
			if (!empty($NomAg) AND !empty($PostNomAg) AND !empty($NumTelAg) AND !empty($GenreAg) AND !empty($EtatAg)) {
				
				#Mise à jour des informations de l'utilisateur
				if (empty($Mot2PasseAg)) {

					$RequeteMisajourAgent = $connexion->prepare("UPDATE `agent` SET Nom_Agent = '$NomAg', PostNom_Agent = '$PostNomAg', Genre_Agent = '$GenreAg', Tel_Agent = '$NumTelAg', Etat = '$EtatAg' WHERE PK_Agent = '$NumAgent'");

				} else {
					$Pass = password_hash($Mot2PasseAg, PASSWORD_DEFAULT);

					$RequeteMisajourAgent = $connexion->prepare("UPDATE `agent` SET Nom_Agent = '$NomAg', PostNom_Agent = '$PostNomAg', Genre_Agent = '$GenreAg', Tel_Agent = '$NumTelAg', MotDePasse = '$Pass', PasseAssoc = '$Mot2PasseAg', Etat = '$EtatAg' WHERE PK_Agent = '$NumAgent'");
				}
				$RequeteMisajourAgent->execute();
				header("location:gestion_utilisateur.php");
			}
			?>
		<div style="display: flex;justify-content: center;margin-top: 50px;">	
			<form method="POST" id="FormConnexion" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<input type="text" style= "display: none;" name="IdAgent" id="IdAgent" value="<?php echo $NumAgent ?>">

				<label for="NomAgent">Nom vendeur</label>
				<input  type="text" name="NomAg" id="NomAgent" value="<?php if(empty($NomAg)){echo $NomAgent;}else{echo $NomAg;} ?>" <?php if (empty($NomAg) AND empty($PostNomAg) AND empty($NumTelAg) AND empty($GenreAg) AND empty($Mot2PasseAg) AND empty($EtatAg)) {echo "autofocus";} ?>>
				<?php echo $ErrNomAg ?>

				<label for="PostNomAgent">Post nom vendeur</label>
				<input  type="text" name="PostNomAg" id="PostNomAgent" value="<?php if(empty($PostNomAg)){echo $PostNomAgent;}else{echo $PostNomAg;} ?>" <?php if (!empty($NomAg) AND empty($PostNomAg)) {echo "autofocus";} ?>>
				<?php echo $ErrPostNomAg ?>

				<label for="GenreAg">Genre venedeur</label>
				<select id="GenreAg" name="GenreAg">
					<option value="" <?php if(empty($GenreAg)){ if ($GenreAgent ==""){echo "Selected";}}else{if($GenreAg == ""){echo "Selected";}} ?>>Aucun genre</option>
												
					<option value="1" <?php if(empty($GenreAg)){ if ($GenreAgent == "1"){echo "Selected";}}else{if($GenreAg == "1"){echo "Selected";}} ?>>Masculin</option>
												
					<option value="2" <?php if(empty($GenreAg)){ if ($GenreAgent =="2"){echo "Selected";}}else{if($GenreAg == "2"){echo "Selected";}} ?>>Féminin</option>
				</select>
				<?php echo $ErrGenreAg ?>

				<label for="NumTelAg">Numéro de téléphone</label>
				<input type="text" name="NumTelAg" id="NumTelAg" value="<?php if(empty($NumTelAg)){echo $NumTelAgent;}else{echo $NumTelAg;} ?>" <?php if (!empty($NomAg)AND !empty($PostNomAg) AND empty($NumTelAg)) {echo "autofocus";} ?>>
				<?php echo $ErrNumTelAg ?>

				<label for="Mot2PasseAg">Mot de passe</label>
				<input type="password" name="Mot2PasseAg" id="Mot2PasseAg" value="<?php if(!empty($Mot2PasseAg)){echo $Mot2PasseAg;}?>" <?php if (!empty($NomAg)AND !empty($PostNomAg) AND !empty($NumTelAg) AND empty($Mot2PasseAg)) {echo "autofocus";} ?>>
				<?php echo $ErrMot2PasseAg ?>

				<label for="EtatAg">Etat</label>
				<select style="" name="EtatAg" id="EtatAg">>
					<option value="" <?php if(empty($EtatAg)){if ($EtatAgent == "") {echo "selected";}}else{if ($EtatAg == "") {echo "selected";}} ?>>Aucun</option>
													
					<option value="A" <?php if(empty($EtatAg)){if ($EtatAgent == "A") {echo "selected";}}else{if ($EtatAg == "A") {echo "selected";}} ?>>Activé</option>
													
					<option value="D" <?php if(empty($EtatAg)){if ($EtatAgent == "D") {echo "selected";}}else{if ($EtatAg == "D") {echo "selected";}} ?>>Désactivé</option>
				</select>
				<?php echo $ErrEtatAg ?>

				<div id="BoxBtnConnexion">
					<input type="submit" name="BtnValidation" value="Envoyez" id="BtnDeValidation">
				</div>
			</form>
		</div>
		<?php require "../foot.php"; ?>
	</body>
</html>
