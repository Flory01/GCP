<?php
	try{
		//Création de l'objet PDO pour la connexion à la base de données
		$connexion = new PDO("mysql:host=localhost;dbname=gestion_vente;charset=utf8", "root", "");
		$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//Capture de l'erreur de connexion
	}catch(PDOException $e){
		//Affichage du message en cas de l'erreur de connexion
		echo "Désolé ! la connexion a échouée ".$e->getMessage();
	}
?>