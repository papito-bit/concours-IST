<!DOCTYPE html>
<html>
<head>
	<title>action enregistre candidat</title>
	<meta charset="utf-8">
</head>
<body>
<?php
require "connect.php";
$req = $bdd->prepare('INSERT INTO candidats (nom, prenom, date_naissance, lieu_naissance, adresse, telephone, type_candidat, serie_bacc, annee_bacc, mention_bacc, centre, mention, parcours1, parcours2, parcours3, num_arrivee, date_arrivee, dossier_complet, obs) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
$req->execute(array($_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['lieu_naissance'], $_POST['adresse'], $_POST['telephone'], $_POST['type_candidat'], $_POST['serie_bacc'], $_POST['annee_bacc'], $_POST['mention_bacc'], $_POST['mention'], $_POST['parcours1'], $_POST['parcours2'], $_POST['parcours3'], $_POST['num_arrivee'], $_POST['date_arrivee'], $_POST['dossier_complet'], $_POST['obs']));
header("Location:index.php");
?>
</body>
</html>