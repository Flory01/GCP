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
    //Préparation de la requête
    $requete = $connexion->prepare("SELECT * FROM produit WHERE Qte_Stock = 0");
    //Exécution de la requête
    $requete->execute();
        //Définition du style des textes de l'entête du tableau
        $pdf->SetFont('times','B',12);
        //Titre du tableau
        $Titre = "LISTE DES PRODUITS EN RUPTURE DE STOCK";
        // Couleurs du cadre
        $pdf->SetDrawColor(52,73,94);
        /*Orientation des cellules à partir du côté gauche*/
        $pdf->SetX(28.5);
        //Couleur des textes
        $pdf->SetTextColor(255,255,255);
        #Coleur de font
        $pdf->SetFillColor(52,73,94);
        $pdf->Cell(151/*Largeur*/, 10/*hauteur*/, $Titre, 'RLTB'/*Encadrement*/, 1, 'C'/*Centrage*/,true/*Condition pour mettre la couleur de font*/);
        $pdf->Ln(5);

        $pdf->SetX(28.5);
        $pdf->Cell(16/*Largeur de la cellule*/,10/*Hauteur de la cellule*/,utf8_decode('N°'),'RLTB',0,"C", true);
        $pdf->Cell(45,10,'NOM PRODUIT', 'TBLR',0,'C', true);
        $pdf->Cell(45,10,'QUANTITE','TBLR',0,'C', true);
        $pdf->Cell(45,10,'PRIX UNITAIRE','TBLR',0,'C', true);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(10);
        //Boucle de récupération des enregistrements
        while ( $resultat = $requete->fetch(PDO::FETCH_ASSOC)) { 

            $NumeroProd = $resultat['PK_Pro'];
            $NomProd = $resultat['Lib_Pro'];
            $PUProd = $resultat['P_U'];
            $QteProd = $resultat['Qte_Stock'];
            $pdf->SetFont('times','',12);
            $pdf->SetX(28.5);
            $pdf->Cell(16,10,$NumeroProd,'TBLR',0,'C');
            $pdf->Cell(45,10,utf8_decode($NomProd),'TBLR',0,'C');
            $pdf->Cell(45,10,$QteProd,'TBLR',0,'C');
            $pdf->Cell(45,10,$PUProd." FC",'TBLR',0,'C');
                
            $pdf->Ln(10);
                
        }

        header("refresh:1380; url=../../rien");
    //Affichage du pdf
    $pdf->Output("I", 'Liste_produit_en_rupture_de_stock.pdf',true);
?>