<?php
// Starting session
session_start();?>
<!DOCTYPE html>
<html>
<head>
	<title>action modification salle</title>
	<meta charset="utf-8">
</head>
<body>
<?php
require "connect.php";
$id=$_POST['id_modif'];
$req = $bdd->prepare('UPDATE `candidats` SET `salle`=?,`jury`=? WHERE `id_candidat`=?');
$req_post=$req->execute(array(
	$_POST['salle'],
	$_POST['jury'],
	$id));

	if ($req_post) {
		header("Location:liste_modif_salle.php");
	} else {echo "Erreur de modification";}
	



	
?>
</body>
</html>