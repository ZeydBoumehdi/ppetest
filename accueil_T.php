<?php  
session_start();
  if (!isset ($_SESSION['login'])) {
    header("location:index.php");
    //Si une personne non connecter essaie d'acceder a la page il est renvoyé vers index.php
  }elseif ($_SESSION['statut']=="assistant") {
    header("location: accueil_T.php");
    //Si un assistant essaie d'acceder aux page technicien il est renvoyé vers la page assistant
  }

  $bdd = mysqli_connect("localhost","root","","ppe");
  $nomA = "SELECT nom , prenom FROM utilisateur, technicien Where technicien.matricule = utilisateur.matricule and login =\"".$_SESSION['login']."\"";
  $reqNom = mysqli_query($bdd,$nomA);
  $affiche = $reqNom->fetch_array(MYSQLI_ASSOC);

//inclusion de la connexion à la base de données
include_once 'db_connect.php';
//echo (mysqli_error($connexion_a_la_bdd));
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
      	<h2 style="text-align: center;">Accueil technicien</h2>
      	<p style="text-align: center;"><?php echo $affiche['nom']; ?> <?php echo $affiche['prenom'];?></p>	
        <button class="btn btn-primary" onclick="location.href='InterV_T.php'">Intervention / Validation</button>
      	<button class="btn btn-primary" onclick="location.href='logout.php'">Déconnexion</button>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>