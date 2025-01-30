<?php
    //Début de la session
	session_start();
    require("../Connexion.php");

    $id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
    if (empty($id_agent)) {
        header("location:../../rien");
    }

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
            $this->Image('../Images/Logo.jpg',75,10,50);
            $this->Ln(35);
        }

        // Pied de la page
        function Footer(){
            // Positionnement verticale des cellules
            $this->SetY(-20);
            // Police Arial italique 12
            $this->SetFont('times','I',12);
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
    $pdf = new PDF();
    //Appel la fonction permettant de récupérer le nombre de page
    $pdf->AliasNbPages();
    //Appel la fonction permettant d'ajouter la page
    $pdf->AddPage();
    //Préparation de la requête de sélection
    $requete = $connexion->prepare("SELECT * FROM `reference` AS `C` INNER JOIN `commande` AS `V` ON V.PK_Cmd = C.Commande INNER JOIN `paiement` AS `P` ON P.Commande = V.PK_Cmd INNER JOIN `produit` AS `Pro` ON Pro.PK_Pro = C.Produit WHERE P.Observation='NON PAYE'");
    //Exécution de la requête
    $requete->execute();
        //Définition du style des entêtes du tableau
        $pdf->SetFont('times','B',12);
        //Titre du tableau
        $Titre = "LISTE DES REFERENCES EN ATTENTE DE VALIDATION";
        /*Orientation des cellules à partir du côté gauche*/
        $pdf->SetX(28.5);
        // Couleurs du cadre
        $pdf->SetDrawColor(52,73,94);
        //Définition de la couleur des textes se trouvant dans l'entête du tableau
        $pdf->SetTextColor(255,255,255);
        //Définition de la couleur de font des cellules
        $pdf->SetFillColor(52,73,94);
        $pdf->Cell(151/*Largeur*/, 10/*hauteur*/, $Titre, 'RLTB'/*Encadrement*/, 1, 'C'/*Centrage*/,true/*Condition pour mettre la couleur de font*/);
        $pdf->Ln(5);
        /*Orientation des cellules à partir du côté gauche*/
        $pdf->SetX(28.5);
        //Création des cellules
        $pdf->Cell(21/*Largeur de la cellule*/,10/*Hauteur de la cellule*/,utf8_decode('N°'), 'RLTB',0,"C", true);
        $pdf->Cell(45,10,'PRODUIT','TBLR',0,'C', true);
        $pdf->Cell(30,10,'QUANTITE','TBLR',0,'C', true);
        $pdf->Cell(20,10,'P.U','TBLR',0,'C', true);
        $pdf->Cell(35,10,'P.T','TBLR',0,'C', true);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFillColor(255,255,255);
        //Définition du saut de ligne
        $pdf->Ln(10);
        //Boucle de récupération de tous les enregistrements répondant au critère de sélection
        while ( $resultat = $requete->fetch(PDO::FETCH_ASSOC)) { 
            $NumCmd = $resultat['PK_Ref'];
            $Produit = $resultat['Lib_Pro'];
            $QteCmd = $resultat['Qte_Ref'];
            $prix_unitaire = $resultat['P_U'];
            $PrixCmd = $resultat['Prix_Ref'];

            $pdf->SetFont('times','',12);
            $pdf->SetX(28.5);
            $pdf->Cell(21,10,$NumCmd,'TBLR',0,'C');
            $pdf->Cell(45,10,utf8_decode($Produit),'TBLR',0,'C');
            $pdf->Cell(30,10,$QteCmd,'TBLR',0,'C');
            $pdf->Cell(20,10,$prix_unitaire." FC",'TBLR',0,'C');
            $pdf->Cell(35,10,$PrixCmd." FC",'TBLR',0,'C');
                
            $pdf->Ln(10);
                
        }

        header("refresh:1380; url=../../rien");
    //Affichage du pdf une fois créé
    $pdf->Output("I"/*Définition du mode d'impression*/, /*Définition du nom du pdf*/'Liste_de_references_en_attende_de_validation.pdf',/*Acceptation de l'encodage UTF8*/true);
?>