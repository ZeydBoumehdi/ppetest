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

if (isset($_POST['submitRecherche'])) {
    $numero_client = $_POST['recherche'];
    $_SESSION['numero_client'] = $numero_client;
    $req = "SELECT * FROM client WHERE numero_client = ".$_SESSION['numero_client'];
    $resultClient=mysqli_query($bdd,$req);
    //réaffiche la liste lorsqu'on ne met pas de N°Client
    if(!($_POST['recherche'])){
        $resultClient= null;
    }
}

$reqClientMax = "SELECT * FROM client";
$resultClientMax=mysqli_query($bdd,$reqClientMax);
$sizeLD=mysqli_num_rows($resultClientMax);

if (isset($_POST['submitModal'])){
    $req = "UPDATE `client` SET numero_client=\"".$_POST['numClient']."\", prenomC =\"".$_POST['prenom']."\" , nomC =\"".$_POST['nom']."\" ,raison_sociale =\"".$_POST['raison_sociale']."\" , siren =\"".$_POST['siren']."\", code_APE =\"".$_POST['code_ape']."\" , adresse =\"".$_POST['adresse']."\" , telephone =\"".$_POST['telephone']."\" , fax =\"".$_POST['fax']."\" , email =\"".$_POST['email']."\" , duree_deplacement =\"".$_POST['duree_deplacement']."\" ,distance_km =\"".$_POST['distance_km']."\" ,`numero_agence`= \"".$_POST['numero_agence']."\" WHERE numero_client =\"".$_SESSION['numero_client']."\"";
    $result=mysqli_query($bdd,$req);
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

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css">

    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="icon" href="logo.ico" />
</head>

<body>
<div class="container-contact100">
    <div class="wrap-contact100">
        <h2 style="text-align: center;">Rechercher un Client</h2>
        <br>
        <form class="contact100-form validate-form" method="post" action="" autocomplete="off">
            <div class="row">

                <input type="number" name="recherche" class="form-control" placeholder="n°Client"  min="1" max="<?php echo $sizeLD ?>" style="width:150px; margin-left : 90px;" required>
                <button type="submit" class="btn btn-success"  name="submitRecherche" style="margin-left : 10px;">Valider</button>
            </div>
        </form>

        <br/>

        <div>
            <?php

            $reqClientMax = "SELECT * FROM client";
            $resultClientMax=mysqli_query($bdd,$reqClientMax);
            $sizeLD=mysqli_num_rows($resultClientMax);

            if (isset($_POST['submitRecherche']) && ($_POST['recherche'])){
                $affiche = $resultClient->fetch_array(MYSQLI_ASSOC);
                ?>

                <p>N°Client: <?php echo $affiche['numero_client']; ?></p>
                <p>Prénom: <?php echo $affiche['prenomC']; ?></p>
                <p>Nom: <?php echo $affiche['nomC']; ?></p>
                <p>Raison sociale: <?php echo $affiche['raison_sociale']; ?> </p>
                <p>Siren: <?php echo $affiche['siren']; ?></p>
                <p>Code APE: <?php echo $affiche['code_APE']; ?></p>
                <p>Adresse: <?php echo $affiche['adresse']; ?></p>
                <p>Téléphone: <?php echo $affiche['telephone']; ?></p>
                <p>Fax: <?php echo $affiche['fax']; ?></p>
                <p>Email: <?php echo $affiche['email']; ?></p>
                <p>Durée de déplacement: <?php echo $affiche['duree_deplacement']; ?></p>
                <p>Distance km:<?php echo $affiche['distance_km']; ?></p>
                <p>N°Agence: <?php echo $affiche['numero_agence']; ?></p>
                <?php
            }
            ?>
        </div>

        <form class="contact100-form validate-form" method="post" action ="">
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modifier un client</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" >

                            <lo>
                                <li>N°Client: <input type="text" class="form-control" name="numClient" placeholder="N°Client" value="<?php echo $affiche['numero_client']; ?>"></li>
                                <li>Prénom: <input type="text" class="form-control" name="prenom" placeholder="Prenom" value="<?php echo $affiche['prenomC']; ?>"></li>
                                <li>Nom: <input type="text" class="form-control" name="nom" placeholder="Nom" value="<?php echo $affiche['nomC']; ?>"></li>
                                <li>Raison sociale: <input type="text" class="form-control" name="raison_sociale" placeholder="Raison sociale" value="<?php echo $affiche['raison_sociale']; ?>"></li>
                                <li>Siren: <input type="text" class="form-control" name="siren" placeholder="Siren" value="<?php echo $affiche['siren']; ?>"></li>
                                <li>Code APE: <input type="text" class="form-control" name="code_ape" placeholder="Code APE" value="<?php echo $affiche['code_APE']; ?>"></li>
                                <li>Adresse: <input type="text"  class="form-control" name="adresse" placeholder="Adresse" value="<?php echo $affiche['adresse']; ?>"></li>
                                <li>Téléphone: <input type="text" class="form-control" name="telephone" placeholder="Téléphone" value="<?php echo $affiche['telephone']; ?>"></li>
                                <li>Fax: <input type="text" name="fax"  class="form-control" placeholder="Fax" value="<?php echo $affiche['fax']; ?>"></li>
                                <li>Email: <input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo $affiche['email']; ?>"></li>
                                <li>Durée de déplacement: <input type="text" class="form-control" name="duree_deplacement" placeholder="Durée déplacement" value="<?php echo $affiche['duree_deplacement']; ?>"></li>
                                <li>Distance km: <input type="text" class="form-control" name="distance_km" placeholder="Distance kilométrique" value="<?php echo $affiche['distance_km']; ?>"></li>
                                <li>N°Agence: <input type="text" class="form-control" name="numero_agence" placeholder="n°Agence" value="<?php echo $affiche['numero_agence']; ?>"></li>
                            </lo>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary" name="submitModal">Valider</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="offset-md-8 col-4" style="padding-left: 30px;">
            <?php  if (isset($_POST['submitRecherche'])){ ?>
                <button style="margin-bottom: 10px; margin-top: 10px;" type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">Modifier</button>
                <?php
            }
            ?>
            </div>
        </form>

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