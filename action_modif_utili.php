<?php
// Starting session
session_start();?>
<!DOCTYPE html>
<html>
<head>
	<title>action modification</title>
	<meta charset="utf-8">
</head>
<body>
<?php
require "connect.php";
$id=$_POST['id_util'];
$req = $bdd->prepare('UPDATE `utilisateur` SET `nom`=?,`prenom`=?,`email`=?,`pseudo`=?,`mdp`=?,`id_role`=? WHERE `id_utilisateur`=?');
$req_post=$req->execute(array(
	$_POST['nom'],
	$_POST['prenom'],
	$_POST['email'],
	$_POST['pseudo'],
	$_POST['mdp'],
	$_POST['id_role'],
	$id));

	if ($req_post) {
		header("Location:utilisateur.php");
	} else {echo "Erreur de modification";}
	
?>
</body>
</html>