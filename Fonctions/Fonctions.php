<?php
	# Fonction permettant de mettre la première lettre en majuscule
	function Filtrage($data) {
		$data = ucfirst($data);
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);

		return $data;
	}
	# Fonction permettant de supprimer des espaces aux etrémités d'une chaîne de caratère
	function FiltrageEl($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		
		return $data;
	}
	# Fonction permettant de convertir une chaîne de caractère en majuscul
	function Majuscule($donnee){
		$donnee = strtoupper($donnee);
		return $donnee;
	}
?>