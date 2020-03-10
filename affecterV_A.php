<?php

session_start();

if (!isset ($_SESSION['login'])) {
    header("location: index.php");
    //Si une personne non connecter essaie d'acceder a la page il est renvoyé vers index.php
}elseif ($_SESSION['statut']=="Technicien") {
    header("location: accueil_T.php");
    //Si un technicien essaie d'acceder aux page assistant il est renvoyé vers la page technicien
}

//inclusion de la connexion à la base de données
include_once 'db_connect.php';
//echo (mysqli_error($connexion_a_la_bdd));

if(isset($_POST['numClient']) and isset($_POST['submitAffecter'])){
    $numClient = $_POST['numClient'];
    $_SESSION['numClient'] = $numClient;

    $ReqCodeRegAssis = "SELECT assistant.code_region FROM assistant, utilisateur WHERE assistant.matricule = utilisateur.matricule and  utilisateur.login = \"".$_SESSION['login']."\"";
    $ResultCodeRegAssis = mysqli_query($bdd,$ReqCodeRegAssis);
    $CodeRegAssis = $ResultCodeRegAssis->fetch_array(MYSQLI_ASSOC);

    $reqNomT = "SELECT technicien.nom, technicien.prenom FROM technicien, client, agence, assistant WHERE client.numero_agence = technicien.numero_agence and technicien.numero_agence = agence.numero_agence and agence.code_region= assistant.code_region and numero_client =\"".$numClient."\" and assistant.code_region =\"".$CodeRegAssis['code_region']."\"";
    $resultNomT = mysqli_query($bdd,$reqNomT);
}

$reqClientMax = "SELECT * FROM client";
$resultClientMax=mysqli_query($bdd,$reqClientMax);
$sizeLD=mysqli_num_rows($resultClientMax);

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
        <h2 style="text-align: center;">Affecter une visite</h2>
        <br>
        <form class="contact100-form validate-form" method="post" action="" autocomplete="off">
            <div class="row">
                <div class="offset-md-3 col-4">
                    <input type="number" class="form-control" id="numClient" name="numClient" style="width:150px;" placeholder="n°Client" min="1" max="<?php echo $sizeLD?>" required>
                </div>
            </div>

            <?php if(isset($_POST['submitAffecter']) and isset($_SESSION['numClient'])  ){ ?>
                <script>
                    document.getElementById("numClient").setAttribute("value", <?php echo $_SESSION['numClient'] ?>);
                    document.getElementById("numClient").setAttribute("readonly", <?php echo $_SESSION['numClient'] ?>);
                </script>
                <br>
                <div class="row">
                    <select name="matriculeT" class="form-control" required>
                        <option value="" >--Choisir un technicien--</option>
                        <?php while ($affiche = $resultNomT -> fetch_array(MYSQLI_ASSOC)) { ?>
                            <option value="<?php echo $affiche['nom'];?>"><?php echo $affiche['nom'];?> <?php echo $affiche['prenom'];?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="row">
                    Date : <input type="date" class="form-control" name="date_visite" required>
                </div>

                <div class="row">
                    Heure :<input type="time" class="form-control" name="heure_visite" placeholder="Heure" required>
                </div>

                <?php
            }
            ?>

            <br>
            <?php
            if((isset($_POST['submitAffecter']) or isset($_POST['boutonPDF'])) and isset($_POST['matriculeT']) and isset($_POST['date_visite']) and isset($_POST['heure_visite']) and is_numeric($_SESSION['numClient'])){
                $date = $_POST['date_visite'];
                $heure = $_POST['heure_visite'];

                $matricule = "SELECT matricule FROM technicien WHERE nom = \"".$_POST['matriculeT']."\"";
                $resultMatriculeT = mysqli_query($bdd,$matricule);
                $mat = $resultMatriculeT->fetch_array(MYSQLI_ASSOC);

                $name = "SELECT nom, prenom FROM technicien WHERE nom = \"".$_POST['matriculeT']."\"";
                $resultName = mysqli_query($bdd,$name);
                $infoTech = $resultName->fetch_array(MYSQLI_ASSOC);

                // pour faire des rapports d'erreur(pour le trigger)
                $driver = new mysqli_driver();

                //Défini toutes les options (rapporte tout)
                $driver->report_mode = MYSQLI_REPORT_ALL;

                try
                {
                    $req = "INSERT INTO intervention (date_visite, heure_visite, matricule_technicien, numero_client,validation) VALUES (\"".$date."\",\"".$heure."\",\"".$mat['matricule']."\",\"".$_SESSION['numClient']."\",0)";
                    $resultReq=mysqli_query($bdd,$req);?>
                    <div class="alert alert-primary col-12" role="alert" style="text-align:center" id = "alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Visite affectée au client n°<?php echo $_SESSION['numClient']; ?> pour le technicien <?php echo $infoTech['nom'].' '.$infoTech['prenom'] ?>
                    </div><?php
                }
                catch(mysqli_sql_exception $e){
                }
            }
            ?>
            <?php if(isset($e)){?>
                    <div class="alert alert-danger col-12" role="alert" style="text-align:center" id = "alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Le client n°<?php echo $_SESSION['numClient']; ?> a déjà une intervention programmée !
                    </div>
           <?php }else{
                if(isset($_POST['boutonPDF']) and isset($_POST['matriculeT']) and isset($_POST['date_visite']) and isset($_POST['heure_visite'])){
                    $_SESSION['date_visite'] = $_POST['date_visite'];
                    $_SESSION['heure_visite'] = $_POST['heure_visite'];
                    $_SESSION['nom'] = $_POST['matriculeT'];?>

                    <script type="text/javascript">window.open('pdf.php');</script> 

                    <?php 
                }
           } ?>

            <div class="row">
            <div class="offset-md-4 col-3">
              <button type="submit" class="btn btn-success" name="submitAffecter" id="submitAffecter">Valider</button>  
            </div>

          <?php if(isset($_POST['submitAffecter'])){ ?>
            <script>
                document.getElementById("submitAffecter").setAttribute("hidden",true);
                document.getElementById("submitAffecter").setAttribute("disabled",true);
            </script>

            <div class="offset-md-0 col-6">
              <button  type="submit" class="btn btn-success" id ="boutonPDF" name ="boutonPDF" >Valider / PDF</button>
            </div>

            <div class="offset-md-1 col-3">
              <button type="submit" class="btn btn-success" onclick="location.href ='./affecterV_A.php'" id="retour" name="submitRetour">Retour</button> 
            </div> 
          <?php } ?>

        </form>

        <br>
        <br>
    </div>

    <div class="row">
        <div class="offset-md-0 col-4">
            <a href='accueil_A.php'><i class="fas fa-arrow-circle-left fa-3x"></i></a>
        </div>
        <div class="offset-md-3">
            <button class="btn btn-danger" onclick="location.href='logout.php'"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>