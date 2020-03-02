<?php 
session_start();
  if (!isset ($_SESSION['login'])) {
    header("location: index.php");
    //Si une personne non connecter essaie d'acceder a la page il est renvoyé vers index.php
  }elseif ($_SESSION['statut']=="Assistant") {
    header("location: accueil_A.php");
    //Si un assistant essaie d'acceder aux page technicien il est renvoyé vers la page assistant
  }
  $bdd = mysqli_connect("localhost","root","","ppe");
  $reqMatTechnicien = "SELECT technicien.matricule FROM technicien, utilisateur WHERE technicien.matricule = utilisateur.matricule and utilisateur.login =\"".$_SESSION['login']."\"";
  $resultMatTechnicien = mysqli_query($bdd,$reqMatTechnicien);
  $matT = $resultMatTechnicien ->fetch_array(MYSQLI_ASSOC);
  $req = "SELECT intervention.*, client.* FROM intervention, client, technicien WHERE client.numero_client = intervention.numero_client and intervention.matricule_technicien=technicien.matricule and intervention.validation = 0 and technicien.matricule= \"".$matT['matricule']."\" ORDER BY numero_intervention asc";
  $result=mysqli_query($bdd,$req);

    if(isset($_POST['valider1'])){
        $_SESSION['total'] = $_POST['total'];
        $_SESSION['numMachine'] = $_POST['numMachine'];
        $_SESSION['intervention'] = $_POST['intervention'];
    }

    if(isset($_POST['valider2'])){
      $_SESSION['total'] = $_SESSION['total'] + $_POST['ajouter']; 

      if(isset($_POST['numSerie'.($_SESSION['total']-1)]) and isset($_POST['Commentaire'.($_SESSION['total']-1)]) and isset($_POST['nbHeure'.($_SESSION['total']-1)])){ 
        $numSerie = $_POST["numSerie".($_SESSION['total']-1)];
        $Commentaire = $_POST['Commentaire'.($_SESSION['total']-1)];
        $nbHeure = $_POST['nbHeure'.($_SESSION['total']-1)];
        $infoInter = explode (" | ", $_SESSION['intervention']);
        $num_Inter = $infoInter[0];
        
        $reqControl = "INSERT INTO controler (numero_serie, numero_intervention, temps_passer, commentaire) VALUES (\"".$numSerie."\",\"".$num_Inter."\",\"".$nbHeure."\",\"".$Commentaire."\")";
        $resultControl=mysqli_query($bdd,$reqControl);
        $result=mysqli_query($bdd,$req);
      }

      if($_SESSION['total'] == $_SESSION['numMachine']){
        $infoInter = explode (" | ", $_SESSION['intervention']);
        $num_Inter = $infoInter[0];
        $reqValidation = "UPDATE intervention SET Validation= 1 Where numero_intervention = \"".$num_Inter."\"";
        $result=mysqli_query($bdd,$reqValidation);
        
        ?>
          <script>
              document.location.href = './InterV_T.php';
          </script>
        <?php 
        }
    }

  include_once 'db_connect.php';
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
        <h2 style="text-align: center;">Intervention / Validation</h2>
        
        <form id="form1" name="form1" method="post" action="">
          <div id = "divInter">
            <select multiple class="form-control col-6" size = 5 name="intervention" id="intervention">
                <?php while ($affiche = $result -> fetch_array(MYSQLI_ASSOC)) {?>
                    <option><?php echo $affiche['numero_intervention']." | ".$affiche['date_visite']." | ".$affiche['heure_visite']." | ".$affiche['nomC']." ".$affiche['prenomC']?></option>
                 <?php } ?>
            </select>

            <input type="number" class="form-control" name="numMachine" id="numMachine" placeholder="Nombre de machine" min="1">
            <input type="number" name="total" value="0" hidden>
            <button type="submit" id="btnVal2" class="btn btn-primary" name="valider1">Valider</button>
            <button type="submit" class="btn btn-primary" name="Visualiser" data-toggle="modal" data-target="#exampleModal">Visualiser</button>
          </div>

        <?php
          if(isset($_POST['valider1']) || isset($_POST['valider2'])){
            ?>
              <script>
                  document.getElementById("divInter").setAttribute("hidden", true);
                  document.getElementById("intervention").setAttribute("disabled", true);
                  document.getElementById("numMachine").setAttribute("disabled", true);
                  document.getElementById("btnVal2").setAttribute("disabled", true);
              </script>
            <?php 
            if($_SESSION['total'] < $_SESSION['numMachine']){?>
                <div class="form-group">
                  <input type="number" class="form-control" name="<?php echo "numSerie".$_SESSION['total'] ?>" placeholder="Numéro de série" min="1" required>
                </div>
                <div class="form-group">
                  <textarea class="form-control" name="<?php echo "Commentaire".$_SESSION['total'] ?>" rows="3" required ></textarea>
                </div>
                <div class="form-group">
                  <input type="time" class="form-control" name="<?php echo "nbHeure".$_SESSION['total'] ?>" placeholder="Temps d'intervention" required >
                </div>
                <div class="modal-footer">
                  <input type="number" name="ajouter" value="1" hidden>
                  <button type="submit" name="valider2" class="btn btn-primary">Valider</button>
                  <button type="submit" onclick="location.href='InterV_T.php'" class="btn btn-primary">Retour</button>
                </div>
              <?php 
            } 
          }
        ?> 
        </form>

        <div class="row">
          <div class="offset-md-0 col-4">
            <button type="submit" onclick="location.href='accueil_T.php'" class="btn btn-primary">Accueil</button>
          </div>

          <div class="offset-md-3 col-4">
           <button class="btn btn-primary" onclick="location.href='logout.php'">Déconnexion</button>
          </div>
        </div>
      </div>
    </div>

    <?php 
      if(isset($_POST['Visualiser']) and isset($_POST['intervention'])){ 
        $infoInter = explode (" | ", $_POST['intervention']);
        $num_Inter = $infoInter[0];

        $reqVisualiser ="SELECT * FROM intervention, client WHERE intervention.numero_client = client.numero_client and intervention.numero_intervention =\"".$num_Inter."\"";
        $resultVisualiser = mysqli_query($bdd,$reqVisualiser);
        $affiche2 = $resultVisualiser -> fetch_array(MYSQLI_ASSOC);
      ?>

      <script>
        $( document ).ready(function() {
          $('#Modal').modal('show')  
        });
      </script>

        <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Visite</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
              </div>
              <div class="modal-body" >
                <p>Nom: <?php echo $affiche2['nomC'];?></p>
                <p>Prenom: <?php echo $affiche2['prenomC'];?></p>
                <p>Adresse: <?php echo $affiche2['adresse'];?></p>       
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

  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
   </body>
</html>