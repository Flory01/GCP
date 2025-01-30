<?php
	//Début de la session
	session_start();
    //Inclusion de la page permettant de se connecter à la base de données
    require("../Connexion.php");
    
    $id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
    if (empty($id_agent)) {
        header("location:../../rien");
    }

	$IdCmd = (isset($_GET['pr'])) ? (!empty($_GET['pr'])) ? (preg_match("/^[0-9]*$/", $_GET['pr'])) ? $_GET['pr'] : header("location:gestion_produit.php") : header("location:gestion_produit.php") : 0 ;

	//Inclusion de la librairie FPDF
	require('PDF/fpdf.php');
	//Créqtion de la classe dérivée à la classe FPDF
	class PDF extends FPDF{
        //Fonction de créqtion de l'entête
        function Header(){
            //Définition du style des textes se trouvant dans l'entête
            $this->SetFont('times','IB',12);
            //Définition de la position orinzotale gauche des cellules
            $this->SetX(70);
            // Logo
            $this->Image('../Images/Logo.jpg',125,10,50);
            $this->Ln(35);
        }

        // Pied de la page
        function Footer(){
            // Police Arial italique 12
            $this->SetFont('times','I',12);
            
            // Positionnement verticale des cellules
            $this->SetY(-55);
            $this->SetX(127);
            $this->Cell(140,5,"SIGNATURES","BTLR",1,'C');
            $this->SetX(127);
            $this->Cell(70,10,"CLIENT",'',0,'C',true);
            $this->Cell(70,10,"VENDEUR",'',1,'C',true);
            $this->Ln(20);

            //Définition de la zone de récupération de la date
            date_default_timezone_set("Africa/Kinshasa");
            //Définition de la langue de récupération
            setlocale(LC_TIME, ["fr", 'fra', "fr_FR"]);
            //Récupération et affectation de la date
            $date = strftime('%A le %d / %m / %Y');
            $this->Cell(0, 10, utf8_decode("Fait à Kinshasa, ".$date),0, 1, 'R');
            $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
        }
    }

    //Instanciation d'un nouveau objet dans la classe PDF
    $pdf = new PDF("l", "mm");
    //Appel la fonction permettant de récupérer le nombre de page
    $pdf->AliasNbPages();
    //Appel la fonction permettant d'ajouter la page
    $pdf->AddPage();
    //Préparation de la requête de sélection
    $requete = $connexion->prepare("SELECT * FROM `commande` AS `V` INNER JOIN `client` AS `CL` ON CL.PK_Cl = V.Client INNER JOIN `temps` AS `T` ON T.PK_Temps = V.Date_Cmd LEFT JOIN `agent` AS `AG` ON AG.PK_Agent = V.Agent WHERE V.PK_Cmd = '$IdCmd'");
    //Exécution de la requête
    $requete->execute();
    $rs_requete = $requete->fetch(PDO::FETCH_ASSOC);
    $date_cmd = $rs_requete["Jour"]."-".$rs_requete["Mois"]."-".$rs_requete["Annee"];
    $nom_client = $rs_requete["Nom_Cl"]."-".$rs_requete["PostNom_Cl"];
    $num_client = $rs_requete["Tel_Cl"];
    $adresse_client = $rs_requete["adresse"];
    $nom_agent = $rs_requete["Nom_Agent"];
    $Post_nom_agent = $rs_requete["PostNom_Agent"];
    $num_agent = $rs_requete["Tel_Agent"];

    $toatal = $qte_total = 0;
        //Définition du style des entêtes du tableau
        $pdf->SetFont('times','B',12);
        //Titre du tableau
        $Titre = utf8_decode("BON DE COMMANDE N° : ").$IdCmd;
        /*Orientation des cellules à partir du côté gauche*/
        $pdf->SetLeftMargin(28);
        // Couleurs du cadre
        $pdf->SetDrawColor(52,73,94);
        //Définition de la couleur des textes se trouvant dans l'entête du tableau
        $pdf->SetTextColor(255,255,255);
        //Définition de la couleur de font des cellules
        $pdf->SetFillColor(52,73,94);
        $pdf->Cell(240/*Largeur*/, 10/*hauteur*/, $Titre, 'RLTB'/*Encadrement*/, 1, 'C'/*Centrage*/,true/*Condition pour mettre la couleur de font*/);
        $pdf->Ln(5);
        //Création des cellules
        $pdf->SetTextColor(52,73,94);
        $pdf->Cell(50,10,"Date : ".$date_cmd,'',1,'C');
        $pdf->Cell(140,10,"Informations sur le client : ",'BLTR',0,'C');
        $pdf->setX(168);
        $pdf->Cell(99,10,"Informations sur le vendeur : ",'BLTR',1,'C');


        $pdf->Cell(140,10,"Nom : ".utf8_decode($nom_client),'RL',0,'L');
        $pdf->setX(168);
        $pdf->Cell(99,10,"Nom : ".utf8_decode($nom_agent),'LR',1,'L');

        $pdf->Cell(140,10,utf8_decode("Numéro de téléphone : ").$num_client,'RL',0,'L');
        $pdf->setX(168);
        $pdf->Cell(99,10,utf8_decode("Post nom  ").$Post_nom_agent,'LR',1,'L');

        $pdf->Cell(140,10,utf8_decode("Adresse : ").$adresse_client,'RL',0,'L');
        $pdf->setX(168);
        $pdf->Cell(99,10,utf8_decode("Numéro de téléphone : ").$num_agent,'LR',1,'L');

        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(39/*Largeur de la cellule*/,10/*Hauteur de la cellule*/,utf8_decode('N° REFERENCE'), 'RLTB',0,"C",true);
        $pdf->Cell(50,10,'DESIGNATION','TBLR',0,'C',true);
        $pdf->Cell(50,10,'QUANTITE','TBLR',0,'C',true);
        $pdf->Cell(50,10,'PRIX UNITAIRE','TBLR',0,'C',true);
        $pdf->Cell(50,10,'PRIX TOTAL','TBLR',1,'C',true);

        $pdf->SetTextColor(52,73,94);
        $rq_sel_ref = $connexion->prepare("SELECT * FROM `reference` AS `C` INNER JOIN `produit` AS `PR` ON PR.PK_Pro = C.Produit WHERE C.Commande = '$IdCmd'");
	    //Exécution de la requête
	    $rq_sel_ref->execute();
	    //Boucle de récupération de toutes les références
	    while ($rs_sel_ref = $rq_sel_ref->fetch(PDO::FETCH_ASSOC)) {
	    	# code...
	    	$num_ref = $rs_sel_ref["PK_Ref"];
	    	$nom_produit = $rs_sel_ref["Lib_Pro"];
	    	$qte_produit = $rs_sel_ref["Qte_Ref"];
	    	$pu_produit = $rs_sel_ref["P_U"];
	    	$pt_ref = ($qte_produit*$pu_produit);
	    	$toatal += $pt_ref;
            $qte_total += $rs_sel_ref["Qte_Ref"];
	    	$pdf->Cell(39,10,$num_ref,'TBLR',0,'C');
	    	$pdf->Cell(50,10,utf8_decode($nom_produit),'TBLR',0,'C');
	        $pdf->Cell(50,10,$qte_produit,'TBLR',0,'C');
	        $pdf->Cell(50,10,$pu_produit." FC",'TBLR',0,'C');
	        $pdf->Cell(50,10,$pt_ref." FC",'TBLR',1,'C');
	    }
	    $pdf->Cell(89,10,"TOTAL BON DE COMMANDE",'TBLR',0,'C');
	    $pdf->SetTextColor(255,255,255);
        $pdf->Cell(50,10,$qte_total,'TBLR',0,'C',true);
	    $pdf->Cell(100,10,$toatal." FC",'TBLR',1,'C',true);

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFillColor(255,255,255);
        //Définition du saut de ligne
        $pdf->Ln(10);
        
        header("refresh:1380; url=../../rien");
    //Affichage du pdf une fois créé
    $pdf->Output("I"/*Définition du mode d'impression*/, /*Définition du nom du pdf*/$nom_client.'Bon_de_commande_n°'.$IdCmd.'.pdf',/*Acceptation de l'encodage UTF8*/true);
?>