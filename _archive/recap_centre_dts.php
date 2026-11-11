<?php
session_start();
if(!isset($_SESSION['id_utilisateur']) AND !isset($_SESSION['email_concours'])){header("Location:login.php");}



require'connect.php';

$nbdts=$bdd->query('SELECT COUNT(*) AS nombre_dts FROM candidats WHERE niveau="DTS"');
$nombre_dts=$nbdts->fetch();

$nbdtsmeem=$bdd->query('SELECT COUNT(*) AS nombre_dts_meem FROM candidats WHERE niveau="DTS" AND parcours1="MEEM"');
$nombre_dts_meem=$nbdtsmeem->fetch();

$nbdtsmeft=$bdd->query('SELECT COUNT(*) AS nombre_dts_meft FROM candidats WHERE niveau="DTS" AND parcours1="MEFT"');
$nombre_dts_meft=$nbdtsmeft->fetch();

$nbdtsmsa=$bdd->query('SELECT COUNT(*) AS nombre_dts_msa FROM candidats WHERE niveau="DTS" AND parcours1="MSA"');
$nombre_dts_msa=$nbdtsmsa->fetch();

$nbdtsrt=$bdd->query('SELECT COUNT(*) AS nombre_dts_rt FROM candidats WHERE niveau="DTS" AND parcours1="RT"');
$nombre_dts_rt=$nbdtsrt->fetch();

$nbdtstim=$bdd->query('SELECT COUNT(*) AS nombre_dts_tim FROM candidats WHERE niveau="DTS" AND parcours1="TIM"');
$nombre_dts_tim=$nbdtstim->fetch();

$nbdtsbat=$bdd->query('SELECT COUNT(*) AS nombre_dts_bat FROM candidats WHERE niveau="DTS" AND parcours1="BAT"');
$nombre_dts_bat=$nbdtsbat->fetch();

$nbdtstp=$bdd->query('SELECT COUNT(*) AS nombre_dts_tp FROM candidats WHERE niveau="DTS" AND parcours1="TP"');
$nombre_dts_tp=$nbdtstp->fetch();

$nbdtstaf=$bdd->query('SELECT COUNT(*) AS nombre_dts_taf FROM candidats WHERE niveau="DTS" AND parcours1="TAF"');
$nombre_dts_taf=$nbdtstaf->fetch();

$nbdtstecnan=$bdd->query('SELECT COUNT(*) AS nombre_dts_tecnan FROM candidats WHERE niveau="DTS" AND parcours1="TecNa-N"');
$nombre_dts_tecnan=$nbdtstecnan->fetch();

$nbdtstecnapaq=$bdd->query('SELECT COUNT(*) AS nombre_dts_tecnapaq FROM candidats WHERE niveau="DTS" AND parcours1="TecNa-PAq"');
$nombre_dts_tecnapaq=$nbdtstecnapaq->fetch();

$nbdtscom=$bdd->query('SELECT COUNT(*) AS nombre_dts_com FROM candidats WHERE niveau="DTS" AND parcours1="COM"');
$nombre_dts_com=$nbdtscom->fetch();

$nbdtstghrh=$bdd->query('SELECT COUNT(*) AS nombre_dts_tghrh FROM candidats WHERE niveau="DTS" AND parcours1="TGH-RH"');
$nombre_dts_tghrh=$nbdtstghrh->fetch();

$nbdtstghgan=$bdd->query('SELECT COUNT(*) AS nombre_dts_tghgan FROM candidats WHERE niveau="DTS" AND parcours1="TGH-GAN"');
$nombre_dts_tghgan=$nbdtstghgan->fetch();

$nbdtstba=$bdd->query('SELECT COUNT(*) AS nombre_dts_tba FROM candidats WHERE niveau="DTS" AND parcours1="TBA"');
$nombre_dts_tba=$nbdtstba->fetch();

$nbdtsgfc=$bdd->query('SELECT COUNT(*) AS nombre_dts_gfc FROM candidats WHERE niveau="DTS" AND parcours1="GFC"');
$nombre_dts_gfc=$nbdtsgfc->fetch();




    $centres=array(
    'Antsiranana',
    'Ambilobe',
    'Ambanja',
    'Sambava',
    'Antsohihy',
    'Antananarivo',
    'Fianarantsoa',
    'Mahajanga',
    'Toamasina',
    'Toliara');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Liste de candidats</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
    table, tr, td, th {
      border-collapse: collapse;
      border: 1px solid;
    }

.row {
  margin-left:-5px;
  margin-right:-5px;
}
  
.column {
  float: left;
  width: 50%;
  padding: 5px;
}

/* Clearfix (clear floats) */
.row::after {
  content: "";
  clear: both;
  display: table;
}

    </style>
  </head>
  <body>
<?php 

 for ($i=0; $i < 10 ; $i++) {
$centre=$centres[$i];
$nbdts=$bdd->prepare('SELECT COUNT(*) AS nombre_dts FROM candidats WHERE niveau="DTS" AND centre=?');
$nbdts->execute(array($centre));
$nombre_dts=$nbdts->fetch();

$nbdtsmeem=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_meem FROM candidats WHERE niveau="DTS" AND parcours1="MEEM" AND centre=?');
$nbdtsmeem->execute(array($centre));
$nombre_dts_meem=$nbdtsmeem->fetch();

$nbdtsmeft=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_meft FROM candidats WHERE niveau="DTS" AND parcours1="MEFT" AND centre=?');
$nbdtsmeft->execute(array($centre));
$nombre_dts_meft=$nbdtsmeft->fetch();

$nbdtsmsa=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_msa FROM candidats WHERE niveau="DTS" AND parcours1="MSA" AND centre=?');
$nbdtsmsa->execute(array($centre));
$nombre_dts_msa=$nbdtsmsa->fetch();

$nbdtsrt=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_rt FROM candidats WHERE niveau="DTS" AND parcours1="RT" AND centre=?');
$nbdtsrt->execute(array($centre));
$nombre_dts_rt=$nbdtsrt->fetch();

$nbdtstim=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_tim FROM candidats WHERE niveau="DTS" AND parcours1="TIM" AND centre=?');
$nbdtstim->execute(array($centre));
$nombre_dts_tim=$nbdtstim->fetch();

$nbdtsbat=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_bat FROM candidats WHERE niveau="DTS" AND parcours1="BAT" AND centre=?');
$nbdtsbat->execute(array($centre));
$nombre_dts_bat=$nbdtsbat->fetch();

$nbdtstp=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_tp FROM candidats WHERE niveau="DTS" AND parcours1="TP" AND centre=?');
$nbdtstp->execute(array($centre));
$nombre_dts_tp=$nbdtstp->fetch();

$nbdtstecnapaq=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_tecnapaq FROM candidats WHERE niveau="DTS" AND parcours1="TecNa-PAq" AND centre=?');
$nbdtstecnapaq->execute(array($centre));
$nombre_dts_tecnapaq=$nbdtstecnapaq->fetch();

$nbdtstecnan=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_tecnan FROM candidats WHERE niveau="DTS" AND parcours1="TecNa-N" AND centre=?');
$nbdtstecnan->execute(array($centre));
$nombre_dts_tecnan=$nbdtstecnan->fetch();

$nbdtstaf=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_taf FROM candidats WHERE niveau="DTS" AND parcours1="TAF" AND centre=?');
$nbdtstaf->execute(array($centre));
$nombre_dts_taf=$nbdtstaf->fetch();

$nbdtscom=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_com FROM candidats WHERE niveau="DTS" AND parcours1="COM" AND centre=?');
$nbdtscom->execute(array($centre));
$nombre_dts_com=$nbdtscom->fetch();

$nbdtstghrh=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_tghrh FROM candidats WHERE niveau="DTS" AND parcours1="TGH-RH" AND centre=?');
$nbdtstghrh->execute(array($centre));
$nombre_dts_tghrh=$nbdtstghrh->fetch();

$nbdtstghgan=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_tghgan FROM candidats WHERE niveau="DTS" AND parcours1="TGH-GAN" AND centre=?');
$nbdtstghgan->execute(array($centre));
$nombre_dts_tghgan=$nbdtstghgan->fetch();

$nbdtstba=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_tba FROM candidats WHERE niveau="DTS" AND parcours1="TBA" AND centre=?');
$nbdtstba->execute(array($centre));
$nombre_dts_tba=$nbdtstba->fetch();

$nbdtsgfc=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_gfc FROM candidats WHERE niveau="DTS" AND parcours1="GFC" AND centre=?');
$nbdtsgfc->execute(array($centre));
$nombre_dts_gfc=$nbdtsgfc->fetch();

?>


<table>
  <tr> <th style="width: 100px;">Centre</th> <th >Effectif</th>
<tr> <td ><?php echo $centre; ?></td>
 <td style="text-align: center;"><?php echo $nombre_dts['nombre_dts']; ?></td></tr>
</table>
  </div>
    
<p></p>
<?php }
?>


  </body>
</html>
