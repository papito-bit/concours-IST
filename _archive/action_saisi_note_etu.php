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

$moyenne = ($_POST['matiere1']*$_POST['coef1']+$_POST['matiere2']*$_POST['coef2']+$_POST['matiere3']*$_POST['coef3']+$_POST['matiere4']*$_POST['coef4']+$_POST['matiere5']*$_POST['coef5'])/8;
     $moyenne_arrondie = round($moyenne, 2);


$id=$_POST['id_etu'];
$req = $bdd->prepare('INSERT INTO `notes`(`id_notes`, `matiere1`, `coef1`, `matiere2`, `coef2`, `matiere3`, `coef3`, `matiere4`, `coef4`, `matiere5`, `coef5`, `moyenne`, `parcours_notes`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
$req_post=$req->execute(array(
$id,
$_POST['matiere1'],
$_POST['coef1'],
$_POST['matiere2'],
$_POST['coef2'],
$_POST['matiere3'],
$_POST['coef3'],
$_POST['matiere4'],
$_POST['coef4'],
$_POST['matiere5'],
$_POST['coef5'],
$moyenne_arrondie,
$_POST['parcours']
));
$parcours=$_POST['parcours'];
	if ($req_post) {
		header("Location:delib_parcours.php?id=$parcours");
	} else {echo "Erreur d'enregistrement";}
	
?>
</body>
</html>