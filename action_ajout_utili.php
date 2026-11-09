<!DOCTYPE html>
<html>
<head>
	<title>action enregistre utilisateur</title>
	<meta charset="utf-8">
</head>
<body>
<?php
require "connect.php";
$req = $bdd->prepare('INSERT INTO utilisateur (nom, prenom, email, pseudo, mdp, id_role) VALUES(?,?,?,?,?,(SELECT id_role FROM roles WHERE libelle=?))');
$req->execute(array($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['pseudo'], $_POST['mdp'], $_POST['id_role']));

if ($req) {
	header("Location:utilisateur.php");
}else {echo "Erreur d'enregistrement !";}

?>
</body>
</html>