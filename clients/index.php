<?php
  //Début de session
  session_start();
  if(isset($_SESSION['client'])){
	header('location: ../index.php');
  }
  require("../Connexion.php");
?>
<!DOCTYPE html>
<html>
<head>
   <title> Formulaire d'authentification agent </title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width">
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

  <div class="Loader">
    <div class="BoxLoading">
      
    </div>
  </div>
  <?php 

    $ErNom = $ErMotDePasse = $Nom = $Mot2Passe = $ErDeConnexion = "" ;
    $_SESSION['utilisateurs']= array();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      if (empty($_POST["Nom"])) {
        $ErNom = "<p>Veuillez saisir le nom SVP</p>";
      } else {

        if (preg_match("/^[0-9]*$/", $_POST["Nom"])) {
          $Nom = trim($_POST["Nom"]);
        } else {
          $ErNom = "<p>Le numéro de téléphone ne doit pas contenir des lettres</p>";
        }

      }

      if (empty($_POST["MotDePasse"])) {
        $ErMotDePasse = "<p>Veuillez saisir le mot de passe SVP</p>";
      } else {

        if (preg_match("/^[a-zA-Z0-9]*$/", $_POST["MotDePasse"])) {
          $Mot2Passe = $_POST["MotDePasse"];
        } else {
          $ErMotDePasse = "<p>Evitez les caractères spéciaux SVP</p>";
        }

      }
      
      if (!empty($Nom) AND !empty($Mot2Passe)) {

        $ChaineSelAgent = "SELECT * FROM `client` WHERE `Tel_Cl` = '$Nom' AND `PasseAssocie` = '$Mot2Passe'";
        $RequeteSelAgent = $connexion->prepare($ChaineSelAgent);
        $RequeteSelAgent->execute();
        $ResultaSelAgent = $RequeteSelAgent->fetch(PDO::FETCH_ASSOC);

        if (is_array($ResultaSelAgent)) {
          
          if (password_verify($Mot2Passe, $ResultaSelAgent["MotDePasse"])) {
            
              $_SESSION['client'] = $ResultaSelAgent["PK_Cl"];

              $_SESSION['utilisateurs']["c_".$ResultaSelAgent["PK_Cl"]]=[];

              $_SESSION['utilisateurs']["c_".$ResultaSelAgent["PK_Cl"]]["nom"] = $ResultaSelAgent["Nom_Cl"];

              $_SESSION['utilisateurs']["c_".$ResultaSelAgent["PK_Cl"]]["id"] = $ResultaSelAgent["PK_Cl"];

              header('location: ../index.php');
            
          } else {
            $ErDeConnexion = "<p>Nom ou mot de passe incorrect</p>";
          }
          
        } else {
          $ErDeConnexion = "<p>Nom ou mot de passe incorrect</p>";
        }
        
      }
  
    }
    
  ?>
    <form id="FormConnexion" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"; method="post">
      <h2> Se connecter </h2>
      <?php echo $ErDeConnexion ;?>

      <label for="Nom"> Nom : </label>
      <input type="text" name="Nom" id="Nom" value="<?php echo $Nom ;?>" <?php if(empty($Nom) AND empty($Mot2Passe)){echo "autofocus";} ;?> >
      <?php echo $ErNom ;?>
    
      <label for="MotDePasse"> Mot de Passe : </label>
      <input type="password" name="MotDePasse" id="MotDePasse" value="" <?php if(!empty($Nom) AND empty($Mot2Passe)){echo "autofocus";} ;?>>
      <?php echo $ErMotDePasse ;?>

      <div id="BoxBtnConnexion">
        <input type="submit" value="Connexion" id="BtnConnexion" name="BtnConnexion">
      </div>
      <div class="">
		Vous n'avez pas de compte ? <a href="inscription.php" class="link">S'inscrire</a>
	  </div>
    </form>
</body>
</html>
