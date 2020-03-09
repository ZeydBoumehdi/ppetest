<?php
	session_start();

	require('fpdf/fpdf.php');

    //inclusion de la connexion à la base de données
    include_once 'db_connect.php';
    //echo (mysqli_error($connexion_a_la_bdd));

    $matricule = "SELECT * FROM technicien WHERE nom = \"".$_SESSION['nom']."\"";
    $resultMatriculeT = mysqli_query($bdd,$matricule);
    $afficheTechnicien = $resultMatriculeT->fetch_array(MYSQLI_ASSOC);

    $req = "INSERT INTO intervention (date_visite, heure_visite, matricule_technicien, numero_client,validation) VALUES (\"".$_SESSION['date_visite']."\",\"".$_SESSION['heure_visite']."\",\"".$afficheTechnicien['matricule']."\",\"".$_SESSION['numClient']."\",0)";

    $resultReq=mysqli_query($bdd,$req);

    $ReqIntervention = "SELECT * FROM intervention WHERE matricule_technicien = \"".$afficheTechnicien['matricule']."\" and date_visite = \"".$_SESSION['date_visite']."\" and heure_visite = \"".$_SESSION['heure_visite']."\" and numero_client = \"".$_SESSION['numClient']."\" and validation = 0";
    $resultIntervention = mysqli_query($bdd,$ReqIntervention);
    $affichePDF = $resultIntervention->fetch_array(MYSQLI_ASSOC);

    $ReqClient = "SELECT * FROM client WHERE numero_client = \"".$_SESSION['numClient']."\"";
    $resultClient = mysqli_query($bdd,$ReqClient);
    $afficheClient = $resultClient->fetch_array(MYSQLI_ASSOC);

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(190,10,'Intervention',0,0,'C');
    $pdf->Ln();
    $pdf->SetFont('Arial','B',12);

    $pdf->Cell(40,10, utf8_decode('N°Intervention : ')." ".$affichePDF['numero_intervention']);
    $pdf->Ln();
    $pdf->Cell(40,10, 'Date visite : '." ".$affichePDF['date_visite']);
    $pdf->Ln();
    $pdf->Cell(40,10, 'Heure visite : '." ".$affichePDF['heure_visite']);
    $pdf->Ln();
    $pdf->Cell(40,10, utf8_decode('N°Client: ')." ".$affichePDF['numero_client']);
    $pdf->Ln();
    $pdf->Cell(40,10, 'Client : '." ".utf8_decode($afficheClient['nomC'])." ".utf8_decode($afficheClient['prenomC']));
    $pdf->Ln();
    $pdf->Cell(40,10, 'Technicien : '." ".utf8_decode($afficheTechnicien['nom'])." ".utf8_decode($afficheTechnicien['prenom']));

    $pdf->Output();
?>
