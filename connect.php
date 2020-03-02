<?php
    if(isset($_POST['login'])&&isset($_POST['mdp']))

    {
        session_start();

        //On recupere les informations transmises par le formulaire
        $login=$_POST['login'];
        $mdp=$_POST['mdp'];

        //inclusion de la connexion à la base de données
        include_once 'db_connect.php';

        // Récupèrer les données utilisateur pour les stocker dans la sesion

        //on cherche l'utilisateur qui a cet identifiant
        $sql = "SELECT * FROM utilisateur where login='$login'";
        $result = mysqli_query($connexion_a_la_bdd, $sql);
        $row = mysqli_fetch_array($result);

        if ($row)
        {

            //on verifie que le mot de passe est le bon
            if ($row['mdp']==$mdp) {

                //on vide toutes les sessions precedentes et on range les bonnes infos dans cette session
                session_unset();
                $_SESSION['login']=$row['login'];
                $_SESSION['id']=$row['id'];
                $_SESSION['statut']=$row['statut'];

                if ($row['statut']=="Technicien")

                {
                  header("location: accueil_T.php");
                }

                if ($row['statut']=="Assistant")

                {
                  header("location: accueil_A.php");
                }
                
            }
            else {
                $error = "Mauvais mot de passe veuillez reessayer";
                header("Location: index.php?error=".$error);
            }

        }
            if ($row['login']!=$login){
                $error = "Utilisateur inconnu veuillez reessayer";
                header("Location: index.php?error=".$error);
            }
    }
?>