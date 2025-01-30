<?php
    //Début de la session
    session_start();
    require("../Connexion.php");

    $id_agent = (array_key_exists("v", $_SESSION)) ? $_SESSION["v"]: 0;
    if (empty($id_agent)) {
        header("location:../../rien");
    }
    $rq_sel_agent = $connexion->prepare("SELECT * FROM `agent` AS TA WHERE TA.PK_Agent = $id_agent");
    $rq_sel_agent->execute();
    $rs_sel_agent = $rq_sel_agent->fetch(PDO::FETCH_ASSOC);
    $nom_agent = $rs_sel_agent["Nom_Agent"];
    $Post_nom_agent = $rs_sel_agent["PostNom_Agent"];
    $num_agent = $rs_sel_agent["Tel_Agent"];

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
    $pdf = new PDF("l", "mm");
    //Appel la fonction permettant de récupérer le nombre de page
    $pdf->AliasNbPages();
    //Appel la fonction permettant d'ajouter la page
    $pdf->AddPage();
        //Définition du style des entêtes du tableau
        $pdf->SetFont('times','B',12);
        //Titre du tableau
        $Titre = utf8_decode("LISTE DES COMMANDES VALIDES");
        /*Orientation des cellules à partir du côté gauche*/
        $pdf->SetLeftMargin(28);
        // Couleurs du cadre
        $pdf->SetDrawColor(52,73,94);
        //Définition de la couleur des textes se trouvant dans l'entête du tableau
        $pdf->SetTextColor(255,255,255);
        //Définition de la couleur de font des cellules
        $pdf->SetFillColor(52,73,94);
        $pdf->Cell(239/*Largeur*/, 10/*hauteur*/, $Titre, 'RLTB'/*Encadrement*/, 1, 'C'/*Centrage*/,true/*Condition pour mettre la couleur de font*/);
        $pdf->Ln(5);

        $pdf->Cell(239/*Largeur*/, 10/*hauteur*/, "INFORMATION SUR LE VENDEUR", 'RLTB'/*Encadrement*/, 1, 'C',true);

        $pdf->SetTextColor(52,73,94);

        $pdf->Cell(99,10,"Nom : ".utf8_decode($nom_agent),'',1,'L');
        $pdf->Cell(99,10,utf8_decode("Post nom  ").$Post_nom_agent,'',1,'L');
        $pdf->Cell(99,10,utf8_decode("Numéro de téléphone : ").$num_agent,'',1,'L');

        $rq_sel_cmd = $connexion->prepare("SELECT * FROM `commande` AS `C` INNER JOIN `paiement` AS `P` ON P.Commande = C.PK_Cmd INNER JOIN `temps` AS `T` ON T.PK_Temps = C.Date_Cmd WHERE P.Observation='PAYE' AND C.Agent = $id_agent");
        //Exécution de la requête
        $rq_sel_cmd->execute();

        $total_cmd = $total_vente = $pt_ref = 0;
        //Boucle de récupération de toutes les références
        while ($rs_sel_cmd = $rq_sel_cmd->fetch(PDO::FETCH_ASSOC)) {
            $IdCmd = $rs_sel_cmd["PK_Cmd"];
            $tab_id_cmd[$rs_sel_cmd["PK_Cmd"]] = [];
            //Création des cellules
            $pdf->SetTextColor(255,255,255);
            $pdf->Cell(239/*Largeur de la cellule*/,10/*Hauteur de la cellule*/,utf8_decode(' COMMANDE N°').$IdCmd, 'RLTB',1,"C",true);

            $rq_sel_ref = $connexion->prepare("SELECT * FROM `reference` AS `C` INNER JOIN `produit` AS `PR` ON PR.PK_Pro = C.Produit WHERE C.Commande = '$IdCmd'");
            //Exécution de la requête
            $rq_sel_ref->execute();

            $pdf->SetTextColor(52,73,94);
            $pdf->Cell(39/*Largeur de la cellule*/,10/*Hauteur de la cellule*/,utf8_decode('N° REFERENCE'), 'RLTB',0,"C");
            $pdf->Cell(50,10,'DESIGNATION','TBLR',0,'C');
            $pdf->Cell(50,10,'QUANTITE','TBLR',0,'C');
            $pdf->Cell(50,10,'PRIX UNITAIRE','TBLR',0,'C');
            $pdf->Cell(50,10,'PRIX TOTAL','TBLR',1,'C');

            //Boucle de récupération de toutes les références
            while ($rs_sel_ref = $rq_sel_ref->fetch(PDO::FETCH_ASSOC)) {
                $tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["reference"][$rs_sel_ref["PK_Ref"]] = $rs_sel_ref["PK_Ref"];
                $tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["produit"][$rs_sel_ref["PK_Ref"]] = $rs_sel_ref["Lib_Pro"];
                $tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["Quantite"][$rs_sel_ref["PK_Ref"]] = $rs_sel_ref["Qte_Ref"];
                $tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["PU"][$rs_sel_ref["PK_Ref"]] = $rs_sel_ref["P_U"];
                $tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["PTR"][$rs_sel_ref["PK_Ref"]] = ($rs_sel_ref["P_U"]*$rs_sel_ref["Qte_Ref"]);

                $tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["PTC"] = $tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["QTC"] =0;

                foreach ($tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["PTR"] as $key => $value) {
                    # code...
                    $tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["PTC"] += $value; 
                }

                foreach ($tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["Quantite"] as $ky => $val) {
                    # code...
                    $tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["QTC"] += $val; 
                }

                $pdf->Cell(39,10,$tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["reference"][$rs_sel_ref["PK_Ref"]],'TBLR',0,'C');
                $pdf->Cell(50,10,utf8_decode($tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["produit"][$rs_sel_ref["PK_Ref"]]),'TBLR',0,'C');
                $pdf->Cell(50,10,$tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["Quantite"][$rs_sel_ref["PK_Ref"]],'TBLR',0,'C');
                $pdf->Cell(50,10,$tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["PU"][$rs_sel_ref["PK_Ref"]]." FC",'TBLR',0,'C');
                $pdf->Cell(50,10,$tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["PTR"][$rs_sel_ref["PK_Ref"]]." FC",'TBLR',1,'C');
            }
            $pdf->Cell(89,10,"TOTAL COMMANDE",'TBLR',0,'C');
            $pdf->SetFillColor(52,73,94);
            $pdf->SetTextColor(255,255,255);
            $pdf->Cell(50,10,$tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["QTC"],'TBLR',0,'C', true);
            $pdf->Cell(100,10,$tab_id_cmd[$rs_sel_cmd["PK_Cmd"]]["PTC"]." FC",'TBLR',1,'C',true);
            $pdf->Ln(10);
        }

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFillColor(255,255,255);
        //Définition du saut de ligne
        $pdf->Ln(10);
        
        header("refresh:1380; url=../../rien");
    //Affichage du pdf une fois créé
    $pdf->Output("I"/*Définition du mode d'impression*/, /*Définition du nom du pdf*/'Liste_des_commandes_valides.pdf',/*Acceptation de l'encodage UTF8*/true);
?>