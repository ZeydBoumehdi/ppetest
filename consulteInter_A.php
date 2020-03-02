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

  if(isset($_POST['submitRechercheInter'])){  
    if(isset($_POST['rechercheT']) and $_POST['rechercheT']!=""){
      $infoTech = explode (" ", $_POST['rechercheT']);
      $nom_technicien = $infoTech[0];
      $prenom_technicien = $infoTech[1];

      $reqMatTechnicien = "SELECT matricule FROM technicien WHERE prenom =\"".$prenom_technicien."\" and  nom =\"".$nom_technicien."\"";
      $resultMatTechnicien = mysqli_query($bdd,$reqMatTechnicien);
      $matT = $resultMatTechnicien ->fetch_array(MYSQLI_ASSOC);

      if($_POST['dateInter']!=""){
        $reqInterv = "SELECT intervention.*, client.nomC, client.prenomC FROM intervention, client, technicien, assistant, agence WHERE client.numero_client = intervention.numero_client and intervention.matricule_technicien=technicien.matricule and technicien.numero_agence = agence.numero_agence and agence.code_region= assistant.code_region and intervention.validation = 0 and intervention.matricule_technicien=\"".$matT['matricule']."\" and intervention.date_visite = \"".$_POST['dateInter']."\" and assistant.code_region =\"".$CodeRegAssis['code_region']."\" ORDER BY intervention.numero_intervention asc";
        $resultReqInterv = mysqli_query($bdd,$reqInterv);
      }

      if($_POST['dateInter']==""){
        $reqInterv = "SELECT intervention.*, client.nomC, client.prenomC FROM intervention,client, technicien, assistant, agence WHERE client.numero_client = intervention.numero_client and intervention.matricule_technicien=technicien.matricule and technicien.numero_agence = agence.numero_agence and agence.code_region= assistant.code_region and intervention.validation = 0 and  intervention.matricule_technicien=\"".$matT['matricule']."\" and assistant.code_region =\"".$CodeRegAssis['code_region']."\" ORDER BY intervention.numero_intervention asc";
        $resultReqInterv = mysqli_query($bdd,$reqInterv);
      }

    }else if($_POST['rechercheT']=="" and $_POST['dateInter']!=""){
      $reqInterv = "SELECT technicien.nom,technicien.prenom ,intervention.*, client.nomC, client.prenomC FROM intervention, client, technicien, assistant, agence WHERE client.numero_client = intervention.numero_client and intervention.matricule_technicien=technicien.matricule and technicien.numero_agence = agence.numero_agence and agence.code_region= assistant.code_region and intervention.validation = 0 and  intervention.date_visite = \"".$_POST['dateInter']."\" and assistant.code_region =\"".$CodeRegAssis['code_region']."\" ORDER BY intervention.numero_intervention asc";
      $resultReqInterv = mysqli_query($bdd,$reqInterv);
    }
  }
  //inclusion de la connexion à la base de données
  include_once 'db_connect.php';
  //echo (mysqli_error($connexion_a_la_bdd));
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Ca$hCa$h</title>
    <meta charset="utf-8">
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
        <h3 style="text-align: center;">Consulter les interventions</h3>
        <br>
        <form class="contact100-form validate-form" method="post" action="" autocomplete="off">
          <!--<?php #echo $reqInterv ?>-->
          <div class="row">
            <br>
            <select name="rechercheT" class="form-control" id="rechercheT">
              <option value="">--Choisir un technicien--</option>
              <?php while ($afficheT = $resultTechnicien -> fetch_array(MYSQLI_ASSOC)){?>
                  <option><?php echo $afficheT['nom']." ".$afficheT['prenom'];?></option>
               <?php } ?>
            </select>
          </div>

          <div class="row">
            Date : <input type="date" class="form-control" name="dateInter">
          </div>

          <br>
          <div class="row">
            <select multiple class="form-control col-12" size = 5  name = "liste_inter" id = "search">
              <?php 
              if(isset($_POST['rechercheT']) and $_POST['rechercheT']!=""){
                  while($affiche = $resultReqInterv -> fetch_array(MYSQLI_ASSOC)){?>
                  <option><?php echo $affiche['numero_intervention']." | ".$affiche['date_visite']." | ".$affiche['heure_visite']." | ".$affiche['nomC']." ".$affiche['prenomC']?></option>
                  <?php 
                    } 
                  } 
              if($_POST['rechercheT']=="" and $_POST['dateInter']!=""){
                while($affiche = $resultReqInterv -> fetch_array(MYSQLI_ASSOC)){?>
                  <option><?php echo $affiche['numero_intervention']." | ".$affiche['nom']." ".$affiche['prenom']." | ".$affiche['date_visite']." | ".$affiche['heure_visite']." | ".$affiche['nomC']." ".$affiche['prenomC']?></option>
                  <?php 
                  } 
              }          
              ?>
            </select>
          </div>
          <br>
          <div class="row">
            <div class="offset-md-0 col-4">
              <button type="submit" class="btn btn-success" id="ValiderInter" name="submitRechercheInter" class="">Valider</button>
            </div>

            <div class="offset-md-3 col-4">
              <div id = "divInter">
                <button type="submit" class="btn btn-success" name="Modifier" data-toggle="modal" data-target="#Modal1">Modifier</button>
              </div>
            </div>
          </div>

          
        </form>

        <?php 
        if(isset($_POST['liste_inter']) and isset($_POST['Modifier'])){ 
            $infoInter = explode (" | ", $_POST['liste_inter']);
            $num_Inter = $infoInter[0];

            $reqModifier ="SELECT * FROM intervention , client WHERE intervention.numero_client = client.numero_client and  intervention.numero_intervention =\"".$num_Inter."\"";
            $resultModifier = mysqli_query($bdd,$reqModifier);
            $affiche2 = $resultModifier -> fetch_array(MYSQLI_ASSOC);
          ?>

          <script>
            $( document ).ready(function() {
              $('#Modal1').modal('show')  
            });
          </script>

        <div class="modal fade" id="Modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier l'intervention</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
              </div>
              <div class="modal-body" >
                <li>Date : <input type="date" name="datevisite" class="form-control"  value="<?php echo $affiche2['date_visite'] ?>"></li>
                <li>Heure : <input type="text" name="siren" class="form-control" placeholder="Heure" value="<?php  echo $affiche2['heure_visite'] ?>"></li>     
              </div>        
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary" name="submitModal">Valider</button>
              </div>
            </div>
          </div>
        </div>  
       <?php 
          } 
        ?> 
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

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>