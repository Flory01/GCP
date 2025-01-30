<?php
	//Début de la session
	session_start();
    //Inclusion de la page permettant de se connecter à la base de données
    require("../Connexion.php");
    
    $id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
    if (empty($id_agent)) {
        header("location:../Index.php");
    }

	$IdCmd = (isset($_GET['pr'])) ? (!empty($_GET['pr'])) ? (preg_match("/^[0-9]*$/", $_GET['pr'])) ? $_GET['pr'] : header("location:gestion_produit.php") : header("location:gestion_produit.php") : 0 ;

    $rq_maj_cmd = $connexion->prepare("UPDATE `commande` SET `Agent` = $id_agent, `livraison` ='ENCOURS' WHERE PK_Cmd = $IdCmd");
    $rq_maj_cmd->execute();
    header("location:Gestion_commande.php");
?>