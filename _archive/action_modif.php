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
$id=$_POST['id_modif'];
$req = $bdd->prepare('UPDATE `candidats` SET `sexe`=?,`nom`=?,`prenom`=?,`date_naissance`=?,`lieu_naissance`=?,`adresse`=?,`telephone`=?,`type_candidat`=?,`serie_bacc`=?,`mention_bacc`=?,`annee_bacc`=?,`centre`=?,`num_arrivee`=?,`mode_paiement`=?,`date_arrivee`=?,`dossier_complet`=?,`obs`=?,`parcours1`=?,`parcours2`=?,`modifie_par`=?,`date_modification`=NOW() WHERE `id_candidat`=?');
$req_post=$req->execute(array(
	$_POST['sexe'],
	$_POST['nom'],
	$_POST['prenom'],
	$_POST['date_naissance'],
	$_POST['lieu_naissance'],
	$_POST['adresse'],
	$_POST['telephone'],
	$_POST['type_candidat'],
	$_POST['serie_bacc'],
	$_POST['mention_bacc'],
	$_POST['annee_bacc'],
	$_POST['centre'],
	$_POST['num_arrivee'],
	$_POST['mode_paiement'],
	$_POST['date_arrivee'],
	$_POST['dossier_complet'],
	$_POST['obs'],
	$_POST['parcours1'],
	$_POST['parcours2'],
	$_POST['modifie_par'],
	$id));

	if ($req_post) {
		header("Location:liste_saisi.php");
	} else {echo "Erreur de modification";}
	



	
?>
</body>
</html>