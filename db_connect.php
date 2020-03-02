<?php
	//connexion à la base de données
	$host = "localhost";
	$user = "root";
	$password = "";
	$nom_db = "ppe";
	$connexion_a_la_bdd = mysqli_connect($host, $user, $password, $nom_db) or die("Error " . mysqli_error($con));
?>