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

//verification si toutes les notes sont enregistré, sinon redirection vers page erreur

$nbr_notes=$bdd->prepare('SELECT COUNT(*) AS nbr_notes_total FROM notes WHERE parcours_notes=?');
$nbr_notes->execute(array($_POST['parcours']));
$nbr_notes_total=$nbr_notes->fetch();

$nbr_candidats=$bdd->prepare('SELECT COUNT(*) AS nbr_candidats_total FROM candidats WHERE parcours1=?');
$nbr_candidats->execute(array($_POST['parcours']));
$nbr_candidats_total=$nbr_candidats->fetch();

$reste=$nbr_candidats_total['nbr_candidats_total']-$nbr_notes_total['nbr_notes_total'];

if($nbr_notes_total['nbr_notes_total']!=$nbr_candidats_total['nbr_candidats_total'] OR $nbr_candidats_total['nbr_candidats_total']==0){header("Location:msg_avert_saisi.php?msg=$reste");} 

else

{

        $id=$_POST['id_etu'];
        $req = $bdd->prepare('UPDATE `parcours` SET `verrou_notes` = ? WHERE `parcours_abrevie` = ?;');
        $req_post=$req->execute(array($_POST['verrou'], $_POST['parcours']));
        $parcours=$_POST['parcours'];
        	if ($req_post) {
        		header("Location:delib_parcours.php?id=$parcours");
        	} else {echo "Erreur d'enregistrement";}

}
?>
</body>
</html>