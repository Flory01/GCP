<?php
	session_start();
	require("../Connexion.php");

	$id_admin = (array_key_exists("admin", $_SESSION)) ? $_SESSION["admin"]: 0;
	if (empty($id_admin)) {
		header("location:../Index.php");
	}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
	<title>Inscription Client </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="../css/all.css">
	<link rel="stylesheet" type="text/css" href="../menu.css">
	<link rel="stylesheet" type="text/css" href="../foot.css">
	<style type="text/css">
		*{
			margin: 0px 0px 0px 0px;
			padding: 0px 0px 0px 0px;
			box-sizing: border-box;
			font-size: large;
			font-family: "bell mt";
		}
		body{
			border: thick solid transparent;
			background-size: cover;
			height: 100%;
			overflow-x: hidden;
		}
		@media screen and (max-width: 259px){
			*{
				font-size: small;
			}
		} 
		@media all and (min-width: 260px) and (max-width: 491px){
			*{
				font-size: 16px;
			}
		}
		body #FormConnexion{
		  display: grid;
		  margin: 100px 0px 0px 0px;
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
			require'../Fonctions/Fonctions.php';
			require "../menu.php";
		?>
					
		<?php
			$Nom = $ErrNom = $PostNom = $ErrPostNom = $Genre = $ErrGenre = $MotDePasse = $ErrMotDePasse = $NumTelAgent = $ErrNumTelAgent = $MDPC = $ErrMDPC = $NomPhoto = $SourcePhoto = $ErrPhoto = '';
			$MessaErreur = "Mot de passe ou nom d'utilisateur incorrect !";
								
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				if (isset($_POST['BtnDeValidation'])) {
					if (empty($_POST['Nom'])) {
						$ErrNom = '<p id = "ErrMsg">Veuillez insérer le nom Svp</p>';
					} else {
				
						if (preg_match("/^[a-zA-Z]*$/", $_POST['Nom'])) {
								$Nom = FiltrageEL(Majuscule($_POST['Nom']));
						} else {
								$ErrNom = '<p id = "ErrMsg">Insérer bien le nom Svp</p>';
						}
					}
										
					if (empty($_POST['PostNom'])) {
						$ErrPostNom = '<p id = "ErrMsg">Veuillez insérer le post nom Svp</p>';
					} else {
											
						if (!preg_match("/^[a-zA-Z]*$/", $_POST['PostNom'])) {
							$ErrPostNom = '<p id = "ErrMsg">Insérer bien le post nom Svp</p>';
						} else {
							$PostNom = FiltrageEl(Majuscule($_POST['PostNom']));
						}
					}
										
					if (empty($_POST['Genre'])) {
						$ErrGenre = '<p id = "ErrMsg">Veuillez un genre Svp</p>';
					} else {
						if (!preg_match("/^[0-9]*$/", $_POST['Genre'])) {
							$ErrGenre = '<p id = "ErrMsg">Insérer bien le genre Svp </p>';
						} else {
							$Genre = $_POST['Genre'];
						}
					}
					
					if (empty($_POST['MotDepasse'])) {
						$ErrMotDePasse = '<p id = "ErrMsg">Veuillez insérer le mot de passe svp</p>';
					} else {
						if (preg_match("/^[a-zA-Z0-9]*$/", $_POST['MotDepasse'])) {
							$MotDePasse = $_POST['MotDepasse'];
						} else {
							$ErrMotDePasse = '<p id = "ErrMsg">Pas des caratères spéciaux svp</p>';
						}
					}
					
					if (empty($_POST['MotDepasseConfir'])) {
						$ErrMDPC = '<p id = "ErrMsg">Veuillez insérer le mot de passe svp</p>';
					} else {
						if (preg_match("/^[a-zA-Z0-9]*$/", $_POST['MotDepasseConfir'])) {
							$MDPC = $_POST['MotDepasseConfir'];
						} else {
							$ErrMDPC = '<p id = "ErrMsg">Pas des caratères spéciaux svp</p>';
						}						
					}
					
					if ($MotDePasse === $MDPC) {
						$MDPC = $MotDePasse ;
					} else {
						$MDPC = "";
						$ErrMDPC = '<p id = "ErrMsg">Mot de passe non confirmé</p>';
					}
										
					if (empty($_POST['NumTelAgent'])) {
						$ErrNumTelAgent = '<p id = "ErrMsg">Veuillez insérer le mot de passe svp</p>';
					} else {
						if (preg_match("/^[0-9]*$/", $_POST['NumTelAgent'])) {
							if (strlen($_POST['NumTelAgent']) < 10 ) {
								$ErrNumTelAgent = '<p id = "ErrMsg">Le numéro entré est incomplet</p>';
							} elseif (strlen($_POST['NumTelAgent']) > 10 ) {
								$ErrNumTelAgent = '<p id = "ErrMsg">Veuillez entrer moins de 10 chiffres svp</p>';
							} else {
								$NumTelAgent = Filtrage($_POST['NumTelAgent']);
							}
						} else {
							$ErrNumTelAgent = '<p id = "ErrMsg">Les lettres ne sont pas autorisées svp</p>';
						}
					}

					if (!empty($_FILES["Photo"])) {
						echo "Type ".$_FILES["Photo"]["type"];
						$Type = ["image/jpeg", "image/png", "image/Gif", "image/jpg"];

						if (in_array($_FILES["Photo"]["type"], $Type)) {
							$NomPhoto = $_FILES["Photo"]["name"];
							$ImgDir = "../Photos/".$_FILES["Photo"]["name"];
							$SourcePhoto = "Photos/".$_FILES["Photo"]["name"];

							move_uploaded_file($_FILES["Photo"]["tmp_name"], $ImgDir);
						}							
					}else{
						$ErrPhoto = '<p id = "ErrMsg">Veuillez sélectionner une photo svp</p>';
					}
				}
			}
								
			if (!empty($Nom) && !empty($PostNom) && !empty($Genre) && !empty($MotDePasse) && !empty($NumTelAgent) && !empty($MDPC)) {
				$PasseDef = password_hash($MotDePasse, PASSWORD_DEFAULT);
				$Etat = $_POST['Etat'];
				$RequeteInsertion = $connexion->prepare("INSERT INTO `client` (Nom_Cl, PostNom_Cl, Tel_Cl, Genre_Cl, MotDePasse, PasseAssocie) VALUES (:Nom_Cl, :PostNom_Cl, :Tel_Cl, :Genre_Cl, :MotDePasse, :PasseAssocie)");
				$RequeteInsertion->bindParam(':Nom_Cl', $Nom);
				$RequeteInsertion->bindParam(':PostNom_Cl', $PostNom);
				$RequeteInsertion->bindParam(':Tel_Cl', $NumTelAgent);
				$RequeteInsertion->bindParam(':Genre_Cl', $Genre);
				$RequeteInsertion->bindParam(':MotDePasse', $PasseDef);
				$RequeteInsertion->bindParam(':PasseAssocie', $MotDePasse);
				$RequeteInsertion->execute();
				$_SESSION['client'] = $connexion->lastInsertId();

				header('location: ../index.php');
			}
		?>
		
		<div style="display: flex;justify-content: center;margin-top: 100px;">	
			<form id="FormConnexion" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"; method="post" enctype="multipart/form-data">

				<label for="Nom">Entrez le nom</label>
				<input type="text" name="Nom" id="Nom" value="<?php echo($Nom); ?>" <?php if (empty($Nom) AND empty($PostNom) AND empty($Fonction) AND empty($Genre) AND empty($MotDePasse) AND empty($NumTelAgent)){echo "autofocus";} ?>><?php echo($ErrNom); ?>

				<label for="PostNom">Entrez le post nom</label>
				<input type="text" name="PostNom" id="PostNom" value="<?php echo($PostNom); ?>" <?php if (!empty($Nom) AND empty($PostNom)){echo "autofocus";} ?>><?php echo($ErrPostNom); ?>

				<label for="Genre">Choisisssez votre genre</label>
				<select id="Genre" name="Genre">
					<option value="" <?php if($Genre == ""){ echo "Selected";} ?>>Aucun genre</option>
					<option value="1" <?php if($Genre == 1){ echo "Selected";} ?>>Masculin</option>
					<option value="2" <?php if($Genre == 2){ echo "Selected";} ?>>Féminin</option>
				</select><?php echo($ErrGenre); ?>

				<label for="NumtelAgent">Insérez le numéro de téléphone</label>
				<input type="text" name="NumTelAgent" id="NumtelAgent" value="<?php echo($NumTelAgent); ?>" <?php if (!empty($Nom) AND !empty($PostNom) AND empty($NumTelAgent)){echo "autofocus";} ?>>
								<?php echo($ErrNumTelAgent); ?>

				<input type="text" name="Etat" value="A" style="display: none;">

				<label for="MotDepasse">Entrez le mot de passe:</label><input type="Password" name="MotDepasse" value="<?php echo($MotDePasse); ?>" id="MotDepasse" <?php if (!empty($Nom) AND !empty($PostNom) AND empty($MotDePasse) AND !empty($NumTelAgent)){echo "autofocus";} ?>><?php echo($ErrMotDePasse); ?>

				<label for="MotDepasseConfir">Confirmez le mot de passe:</label><input type="Password" name="MotDepasseConfir" id="MotDepasseConfir" <?php if (!empty($Nom) AND !empty($PostNom) AND !empty($MotDePasse) AND !empty($NumTelAgent) AND empty($MDPC)){echo "autofocus";} ?>><?php echo($ErrMDPC); ?>

				<div id="BoxBtnConnexion">
					<input type="submit" name="BtnDeValidation" id="BtnDeValidation" value="Valider">
				</div>
			</form>
		</div>
		
		<?php require "../foot.php"; ?>
	</body>
</html>

?>
