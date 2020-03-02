<?php  
session_start();
  if (!isset ($_SESSION['login'])) {
    header("location: index.php");
    //Si une personne non connecter essaie d'acceder a la page il est renvoyé vers index.php
  }elseif ($_SESSION['statut']=="technicien") {
    header("location: accueil_A.php");
    //Si un assistant essaie d'acceder aux page technicien il est renvoyé vers la page assistant
  }

  $bdd = mysqli_connect("localhost","root","","ppe");
  $nomA = "SELECT nom , prenom FROM utilisateur, assistant Where assistant.matricule = utilisateur.matricule and login =\"".$_SESSION['login']."\"";
  $reqNom = mysqli_query($bdd,$nomA);
  $affiche = $reqNom->fetch_array(MYSQLI_ASSOC);

  $reqNbContrat = "SELECT COUNT(*) AS nbContrat FROM contrat_maintenance WHERE DATEDIFF(date_echeance,CURRENT_DATE) < 31";
  $resultNbContrat = mysqli_query($bdd, $reqNbContrat);
  $afficheNbContrat = $resultNbContrat -> fetch_array(MYSQLI_ASSOC);

  //inclusion de la connexion à la base de données
  include_once 'db_connect.php';
  //echo (mysqli_error($connexion_a_la_bdd));

?>

<!DOCTYPE html>
<html>

 <head>
   <meta charset="utf-8">
	<title>Ca$hCa$h</title>
  <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css">
  
  <link rel="stylesheet" type="text/css" href="css/util.css">
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="icon" href="logo.ico" />
  </head>

  <body>
    <div class="container-contact100">
      <div class="wrap-contact100">
        <div align="center" style="font-size: 25px">
          <h2>Accueil Assistants</h2>
        </div>
        	
          <p align="center"><?php echo $affiche['nom']; ?> <?php echo $affiche['prenom']; ?></p>

          <div class="row">
        	   <button class="btn btn-success" style="margin: 10px;" onclick="location.href='recherche_A.php'">Page de recherche</button>
             <button class="btn btn-success" style="margin: 10px;" onclick="location.href='affecterV_A.php'">Affecter une visite</button>
          </div>

          <div class="row">
             <button class="btn btn-success" style="margin: 10px;" onclick="location.href='consulteInter_A.php'">Consulter les interventions</button>
             <button class="btn btn-success" style="margin: 10px;" onclick="location.href='stat_A.php'">Statistique</button>
          </div>


            <div class="row">
              <div class="col-12">
                <form method="post" action ="">
                  <button style="margin: 10px;" type="submit" class="btn btn-success" name="Contrat" data-toggle="modal" data-target="#ModalA">Le nombre de contrat arrivant à la fin : <?php echo $afficheNbContrat['nbContrat']?></button>
                </form>
              </div>
            </div>


          <?php 

            if(isset($_POST['Contrat'])){ 

              $reqContrat = "SELECT client.*, contrat_maintenance.* FROM contrat_maintenance, client WHERE contrat_maintenance.numero_client = client.numero_client and DATEDIFF(date_echeance,CURRENT_DATE) < 31";
              $resultContrat = mysqli_query($bdd, $reqContrat); 

            ?>

            <script>
              $( document ).ready(function() {
                $('#ModalA').modal('show')  
              });
            </script>

            <div class="modal fade" id="ModalA" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Contrat arrivant à la fin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>
                  <div class="modal-body" >
                    <table class="table table-bordered table-sm">
                      <thead>
                        <tr>
                          <th>Nom</th>
                          <th>Prenom</th>
                          <th>n°Contrat</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while($afficheContrat = $resultContrat -> fetch_array(MYSQLI_ASSOC)){?>
                          <td><?php echo $afficheContrat['nomC'] ?></td>
                          <td><?php echo $afficheContrat['prenomC'] ?></td>
                          <td><?php echo $afficheContrat['numero_contrat'] ?></td>
                      <?php 
                        }
                      ?>

                      </tbody>
                    </table>

                  </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
              </div>
            </div>  
            
        <?php
          }
        ?>

          <div class="offset-md-7 ">
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