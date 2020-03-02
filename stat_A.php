<?php 
	session_start();
  if (!isset ($_SESSION['login'])) {
    header("location: index.php");
    //Si une personne non connecter essaie d'acceder a la page il est renvoyé vers index.php
  }elseif ($_SESSION['statut']=="Technicien") {
    header("location: accueil_T.php");
    //Si un technicien essaie d'acceder aux page assistant il est renvoyé vers la page technicien
  }
  $bdd = mysqli_connect("localhost","root","","ppe");
  $ReqCodeRegAssis = "SELECT assistant.code_region FROM assistant, utilisateur WHERE assistant.matricule = utilisateur.matricule and utilisateur.login = \"".$_SESSION['login']."\"";
  $ResultCodeRegAssis = mysqli_query($bdd,$ReqCodeRegAssis);
  $CodeRegAssis = $ResultCodeRegAssis->fetch_array(MYSQLI_ASSOC);
  $reqTechnicien= "SELECT technicien.nom, technicien.prenom FROM technicien, agence, assistant WHERE assistant.code_region = agence.code_region and technicien.numero_agence = agence.numero_agence and assistant.code_region = \"".$CodeRegAssis['code_region']."\"";
  $resultTechnicien = mysqli_query($bdd,$reqTechnicien);
  if(isset($_POST['valider'])){
  	if (isset($_POST['tech'])) {
		  $infoTech = explode (" ", $_POST['tech']);
		  $nom_technicien = $infoTech[0];
		  $prenom_technicien = $infoTech[1];
		  $reqMatTechnicien = "SELECT matricule FROM technicien WHERE prenom =\"".$prenom_technicien."\" and  nom =\"".$nom_technicien."\"";
		  $resultMatTechnicien = mysqli_query($bdd,$reqMatTechnicien);
		  $matT = $resultMatTechnicien ->fetch_array(MYSQLI_ASSOC);
		  $req = "SELECT SEC_TO_TIME( SUM( TIME_TO_SEC(temps_passer) ) ) as temps FROM controler, intervention WHERE controler.numero_intervention = intervention.numero_intervention and MONTH(intervention.date_visite)= \"".$_POST['mois']."\" AND YEAR(intervention.date_visite) = YEAR(NOW()) and intervention.matricule_technicien = \"".$matT['matricule']."\"";
		  $res = mysqli_query($bdd, $req);
		  $resultTime = $res->fetch_array(MYSQLI_ASSOC);
		  $reqK = "SELECT SUM(client.distance_km) as distance FROM intervention, client WHERE intervention.numero_client = client.numero_client and intervention.validation = 1 AND intervention.matricule_technicien = \"".$matT['matricule']."\" and MONTH(date_visite)= \"".$_POST['mois']."\" and YEAR(date_visite) = YEAR(NOW())";
		  $resK = mysqli_query($bdd, $reqK);
		  $resultK = $resK->fetch_array(MYSQLI_ASSOC);
		  $reqNB = "SELECT COUNT(numero_intervention) as nbInter FROM `intervention` WHERE matricule_technicien = \"".$matT['matricule']."\" AND MONTH(intervention.date_visite)= \"".$_POST['mois']."\" AND YEAR(intervention.date_visite) = YEAR(NOW()) AND validation = 1";
		  $resNB = mysqli_query($bdd, $reqNB);
		  $resultNB = $resNB->fetch_array(MYSQLI_ASSOC);
  	}
  }
?>
<!DOCTYPE html>
<html>

	<head>
		<title>CashCash</title>
		<meta charset="UTF-8">
		<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css">
		
		<link rel="stylesheet" type="text/css" href="css/util.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="icon" href="logo.ico" />
	</head>

	<body>
		<div class="container-contact100">
			<div class="wrap-contact100">
				<h2 style="text-align: center;">Statistique</h2>
				<form class="contact100-form validate-form" method="post">
					<div class="row">
						<select name="tech" id="tech" class="form-control" required>
							<option value="">--Choisir un technicien--</option>
					        <?php while ($afficheT = $resultTechnicien -> fetch_array(MYSQLI_ASSOC)){?>
					            <option><?php echo $afficheT['nom']." ".$afficheT['prenom'];?></option>
					         <?php } ?>
						</select>
					</div>
					<br>
					<div class="row">
						<select name="mois" class="form-control" id="mois" required>
							<option value="">--Choisir un mois--</option>
							<option value="1">Janvier</option>
							<option value="2">Février</option>
							<option value="3">Mars</option>
							<option value="4">Avril</option>
							<option value="5">Mai</option>
							<option value="6">Juin</option>
							<option value="7">Juillet</option>
							<option value="8">Août</option>
							<option value="9">Septembre</option>
							<option value="10">Octobre</option>
							<option value="11">Novembre</option>
							<option value="12">Décembre</option>
						</select>
					</div>
					<br>
	
					<?php if(isset($_POST['valider'])){ 
						echo $nom_technicien." ".$prenom_technicien;
						?> pour le mois<?php
						switch ($_POST['mois']) {
						    case 1:
						        echo " de janvier";
						        break;
						    case 2:
						        echo " de février";
						        break;
						    case 3:
						        echo " de mars";
						        break;
						    case 4:
						        echo " d'avril";
						        break;
						    case 5:
						        echo " de mai";
						        break;
						    case 6:
						        echo " de juin";
						        break;
						    case 7:
						        echo " de juillet";
						        break;
						    case 8:
						        echo " d'août";
						        break;
						    case 9:
						        echo " de septembre";
						        break;
						    case 10:
						        echo " d'octobre";
						        break;
						    case 11:
						        echo " de novembre";
						        break;
						    case 12:
						        echo " de décembre";
						        break;
						}
						?>
					<br>
					<br>
					Nombre d'intervention : <?php echo $resultNB['nbInter'] ?>
					<br>
					<br>
					Nombre de kilomètres parcourus : <?php if (is_null($resultK['distance'])){ echo "0";}
					else echo $resultK['distance'] ?> Km
					<br>
					<br>
					Temps passer à contrôler : <?php if (is_null($resultTime['temps'])){ echo "00:00:00";}
					else echo $resultTime['temps']; } ?>

					<div class="row">
				        <div class="offset-md-0 col-4">
				        <br>
				          <button type="submit" name="valider" class="btn btn-success">Valider</button>
				        </div>
					 </div>
				</form>
				<br>
				<div class="row">
		            <div class="offset-md-0 col-4">
		                <a href='accueil_A.php'><i class="fas fa-arrow-circle-left fa-3x"></i></a>
		            </div>

		            <div class="offset-md-3 ">
		                <button class="btn btn-danger" onclick="location.href='logout.php'"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
		            </div>
		        </div>
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	</body>
</html>