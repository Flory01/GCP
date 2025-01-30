<?php
	//Début de session
	session_start();
	if (isset($_SESSION['v'])) {
		$IdAgent = "v_".$_SESSION['v'];
		//Vérification de l'existace de la session
		if (!empty($_SESSION)) {
			//Déstruction de la session
			unset($_SESSION['utilisateurs'][$IdAgent]);
			unset($_SESSION['v']);
			//Redirection
			header("location:index.php");
		}
	}

	if (isset($_SESSION['admin'])) {
		$id_admin = "admin_".$_SESSION['admin'];
		//Vérification de l'existace de la session
		if (!empty($_SESSION)) {
			//Déstruction de la session
			unset($_SESSION['utilisateurs'][$id_admin]);
			unset($_SESSION['admin']);
			//Redirection
			header("location:index.php");
		}
	}
?>