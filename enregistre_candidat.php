<?php
// Starting session
require_once __DIR__ . '/auth.php';
require "connect.php";
require_once __DIR__ . '/journal_helper.php';
// Nettoyer les données saisies
$nom = preg_replace('/\s+/', ' ', trim($_POST['nom']));
$prenom = preg_replace('/\s+/', ' ', trim($_POST['prenom']));
$date_naissance = trim($_POST['date_naissance']); // date au format YYYY-MM-DD, donc pas d'espaces en théorie

// Vérifier doublons : nom + prenom + date_naissance
$sql = "
SELECT COUNT(*) as nb FROM candidats
WHERE LOWER(TRIM(REGEXP_REPLACE(nom, '\\s+', ' '))) = LOWER(:nom)
  AND LOWER(TRIM(REGEXP_REPLACE(prenom, '\\s+', ' '))) = LOWER(:prenom)
  AND date_naissance = :date_naissance
";

$stmt = $bdd->prepare($sql);
$stmt->execute([
    ':nom' => $nom,
    ':prenom' => $prenom,
    ':date_naissance' => $date_naissance,
]);
$row = $stmt->fetch();

if ($row['nb'] > 0) {
    // Doublon détecté
    echo "<script>alert('Doublon détecté : ce candidat est déjà enregistré.'); window.history.back();</script>";
    exit;
}

// Pas de doublon, on peut continuer l'insertion
?>
<!DOCTYPE html>
<html>
<head>
	<title>action enregistre candidat</title>
	<meta charset="utf-8">
</head>
<body>
<?php


//recuperation du dernier numero de parcours
    $numero_last=$bdd->prepare('SELECT * FROM parcours WHERE parcours_abrevie=?');
    $numero_last->execute(array($_POST['parcours1']));
    $numero=$numero_last->fetch();
    $num=$numero['last_number']+1;

//incrementation du dernier numero de parcours
$incrementation_parcours = $bdd->prepare('UPDATE `parcours` SET `last_number`=? WHERE parcours_abrevie=?');
$incrementation_parcours->execute(array($num, $_POST['parcours1']));






$req = $bdd->prepare('INSERT INTO candidats (
	nom,
	prenom,
	sexe,
	date_naissance,
	lieu_naissance,
	adresse,
	telephone,
	N_CIN,
	Date_CIN,
	Lieu_CIN,
	Adresse_email,
	type_candidat,
	serie_bacc,
	annee_bacc,
	mention_bacc,
	parcours1, 
	parcours2,
	num_arrivee, 
	date_arrivee,
	mode_paiement,
	dossier_complet,
	obs,
	ecole,
	niveau,
	numero,
	date_saisi,
	id_centre,
	saisi_par,
	id_annee)
	VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
	NOW(),
	(SELECT id_centre FROM centre WHERE nom_centre=?),?,?);');
$req->execute(array(
	trim($_POST['nom']),
	trim($_POST['prenom']),
	$_POST['sexe'],
	$_POST['date_naissance'],
	$_POST['lieu_naissance'],
	$_POST['adresse'],
	$_POST['telephone'],
	$_POST['N_CIN'],
	$_POST['Date_CIN'],
	$_POST['Lieu_CIN'],
	$_POST['adresse_email'],
	$_POST['type_candidat'],
	$_POST['serie_bacc'],
	$_POST['annee_bacc'],
	$_POST['mention_bacc'],
	$_POST['parcours1'],
	$_POST['parcours2'],
	$_POST['num_arrivee'],
	$_POST['date_arrivee'],
	$_POST['mode_paiement'],
	$_POST['dossier_complet'],
	$_POST['obs'],
	$_POST['ecole'],
	$_POST['niveau'],
	$num,
	$_POST['centre'],
	$_SESSION['id_utilisateur'],
	$_SESSION['id_annee_selected']));

    $_SESSION['message']= $_POST['nom']." ".$_POST['prenom']." a été enregistré(e) avec succès.";
	$nouvel_id = (int) $bdd->lastInsertId();
	journaliser('candidat_ajout', trim($_POST['nom']).' '.trim($_POST['prenom']).' ('.$_POST['niveau'].' - '.$_POST['ecole'].')', 'candidat', $nouvel_id);
	notifier('Nouveau candidat : '.trim($_POST['nom']).' '.trim($_POST['prenom']).' ('.$_POST['niveau'].' - '.$_POST['ecole'].')', 'liste_saisi.php');
	
	if ($_POST['ecole']=="EGI" AND $_POST['niveau']=="DTS") {header("Location:saisi_dts.php?ecole=EGI");}
	if ($_POST['ecole']=="EGCGN" AND $_POST['niveau']=="DTS") {header("Location:saisi_dts.php?ecole=EGCGN");}
	if ($_POST['ecole']=="EGMCS" AND $_POST['niveau']=="DTS") {header("Location:saisi_dts.php?ecole=EGMCS");}

	if ($_POST['ecole']=="EGI" AND $_POST['niveau']=="DTSS") {header("Location:saisi_dtss.php?ecole=EGI");}
	if ($_POST['ecole']=="EGCGN" AND $_POST['niveau']=="DTSS") {header("Location:saisi_dtss.php?ecole=EGCGN");}
	if ($_POST['ecole']=="EGMCS" AND $_POST['niveau']=="DTSS") {header("Location:saisi_dtss.php?ecole=EGMCS");}

	if ($_POST['ecole']=="EGI" AND $_POST['niveau']=="INGENIORAT") {header("Location:saisi_ing.php?ecole=EGI");}
	if ($_POST['ecole']=="EGCGN" AND $_POST['niveau']=="INGENIORAT") {header("Location:saisi_ing.php?ecole=EGCGN");}
	if ($_POST['ecole']=="EGMCS" AND $_POST['niveau']=="INGENIORAT") {header("Location:saisi_ing.php?ecole=EGMCS");}
?>
</body>
</html>