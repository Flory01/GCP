<?php

  require('Connexion.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Formulaire pour l'administrateur</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="">
	<style type="text/css">
     * {
		  font-family : 'poppins', sans-serif;
		  margin : 0; 
		  padding : 0;
		  box-sizing : border-box;
		}
		body {
		  display : flex ;
		  justify-content : center ;
		  align-items : center;
		  min-height : 100vh;
		  background : #34495E;
		  background-size: cover;
		}
		body #FormConnexion{
		  display: grid;
		  background: white;
		  box-shadow: /*OX*/5px /*OY*/5px /*Epaisseur du brouillard*/5px /*Couleur du brouillard*/rgb(0,0,0);
		  border-radius: 20px;
		  justify-content: center;
		  align-items: center;
		  color: #34495E;
		  height: 300px;
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

	<div id="CorpsDePage">
		<?php
		
				$Nom = '';
				$ErrNom = '';
				$MotDePasse = '';
				$ErrMotDePasse = '';
				if ($_SERVER['REQUEST_METHOD'] == "POST") {

					if (isset($_POST['BtnDeValidation'])) {

						if (empty($_POST['Nom'])) {
						$ErrNom = '<p>Veuillez insérer nom Svp</p>';
					} else {
						
						if (!preg_match("/^[a-zA-Z]*$/", $_POST['Nom'])) {
							$ErrNom = '<p>Evitez les caractères spéciaux Svp</p>';
						} else {
							$Nom = trim($_POST['Nom']);
						}
					}
						if (empty($_POST['MotDepasse'])) {
							$ErrMotDePasse = '<p>Veuillez insérer le mot de passe svp!</p>';
						} else {
							if (preg_match("/^[a-zA-Z0-9]*$/", $_POST['MotDepasse'])) {
								$MotDePasse = $_POST['MotDepasse'];
							} else {
								$ErrMotDePasse = '<p>Evitez les caractères spéciaux Svp</p>';
							}
						}
					}
					
				}
				
				if (!empty($Nom) AND !empty($MotDePasse)) {
						$MotDePasseFin = password_hash($MotDePasse, PASSWORD_DEFAULT);
						$RequeteInsertion = $connexion->prepare("INSERT INTO `administrateur` SELECT NULL, :Nom, :MotDePasse, :PasseAssocie FROM DUAL WHERE NOT EXISTS (SELECT * FROM `administrateur` WHERE `Nom`='$Nom')");
						$RequeteInsertion->BindParam(':Nom', $Nom);
						$RequeteInsertion->BindParam(':MotDePasse', $MotDePasseFin);
						$RequeteInsertion->BindParam(':PasseAssocie', $MotDePasse);
						$RequeteInsertion->execute();
						header("location:administrateur/");
				}
				
			?>
			
			<form id="FormConnexion" action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
				<h1>
					Insert Adm
				</h1>

				<label>Entrez le nom:</label>
				<input type="text" name="Nom" value="<?php echo($Nom); ?>" autofocus="" ><?php echo($ErrNom); ?>

				<input style="display: none;" type="text" name="Etat" value="A">

				<label>Entrez le mot de passe:</label>
				<input type="Password" name="MotDepasse" value=""><?php echo($ErrMotDePasse); ?>
				<div id="BoxBtnConnexion" >
					<input type="submit" name="BtnDeValidation" id="BtnDeValidation" value="Valider">
				</div>
			</form>
	</div>
</body>
</html>