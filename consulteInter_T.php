<?php
  session_start();
  if (!isset ($_SESSION['login'])) {
    header("location: index.php");
    //Si une personne non connecter essaie d'acceder a la page il est renvoyé vers index.php
  }elseif ($_SESSION['statut']=="assistant") {
    header("location: accueil_A.php");
    //Si un technicien essaie d'acceder aux page assistant il est renvoyé vers la page technicien
  }

  $bdd = mysqli_connect("localhost","root","","ppe");

  $reqInterv="SELECT intervention.*,client.nomC, client.prenomC FROM client, agence, intervention, technicien, utilisateur WHERE technicien.matricule = utilisateur.matricule and client.numero_client = intervention.numero_client and intervention.matricule_technicien=technicien.matricule and technicien.numero_agence = agence.numero_agence and intervention.validation = 0 and intervention.date_visite= CAST(NOW() AS DATE) and utilisateur.login=\"".$_SESSION['login']."\" ORDER BY client.duree_deplacement, client.distance_km asc";
  $resultReqInterv = mysqli_query($bdd,$reqInterv);
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>

  <body>
    <u><h1 style="text-align: center;">Consulter les interventions</h1></u>

    <form method="post" action="" autocomplete="off">
      <select multiple class="form-control col-6" size = 5  name = "liste_inter" id = "search">
        <?php while($affiche = $resultReqInterv -> fetch_array(MYSQLI_ASSOC)){?>
        <option><?php echo $affiche['numero_intervention']." | ".$affiche['date_visite']." | ".$affiche['heure_visite']." | ".$affiche['nomC']." ".$affiche['prenomC']?></option>
        <?php } ?>
      </select>
    </form>

  <li><a href="logout.php">Déconnexion</a></li>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>