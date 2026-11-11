<?php
session_start();
if(!isset($_SESSION['id_utilisateur']) AND !isset($_SESSION['email_concours'])){header("Location:login.php");}



require'connect.php';

$nbdtss=$bdd->query('SELECT COUNT(*) AS nombre_dtss FROM candidats WHERE niveau!="DTS"  AND centre="Antsiranana"');
$nombre_dtss=$nbdtss->fetch();

$nbdtsssera=$bdd->query('SELECT COUNT(*) AS nombre_dtss_sera FROM candidats WHERE niveau!="DTS" AND parcours1="SERA"   AND centre="Antsiranana"');
$nombre_dtss_sera=$nbdtsssera->fetch();

$nbdtssmure=$bdd->query('SELECT COUNT(*) AS nombre_dtss_mure FROM candidats WHERE niveau!="DTS" AND parcours1="MURE"   AND centre="Antsiranana"');
$nombre_dtss_mure=$nbdtssmure->fetch();

$nbdtssmsa=$bdd->query('SELECT COUNT(*) AS nombre_dtss_msa FROM candidats WHERE niveau!="DTS" AND parcours1="MSA"   AND centre="Antsiranana"');
$nombre_dtss_msa=$nbdtssmsa->fetch();

$nbdtssadr=$bdd->query('SELECT COUNT(*) AS nombre_dtss_adr FROM candidats WHERE niveau!="DTS" AND parcours1="ADR"   AND centre="Antsiranana"');
$nombre_dtss_adr=$nbdtssadr->fetch();

$nbdtssirm=$bdd->query('SELECT COUNT(*) AS nombre_dtss_irm FROM candidats WHERE niveau!="DTS" AND parcours1="IRM"   AND centre="Antsiranana"');
$nombre_dtss_irm=$nbdtssirm->fetch();

$nbdtsscci_bat=$bdd->query('SELECT COUNT(*) AS nombre_dtss_cci_bat FROM candidats WHERE niveau!="DTS" AND parcours1="CCI-BAT"   AND centre="Antsiranana"');
$nombre_dtss_cci_bat=$nbdtsscci_bat->fetch();

$nbdtsstp=$bdd->query('SELECT COUNT(*) AS nombre_dtss_tp FROM candidats WHERE niveau!="DTS" AND parcours1="CCI-TP"   AND centre="Antsiranana"');
$nombre_dtss_tp=$nbdtsstp->fetch();

$nbdtsstan=$bdd->query('SELECT COUNT(*) AS nombre_dtss_tan FROM candidats WHERE niveau!="DTS" AND parcours1="TAN"   AND centre="Antsiranana"');
$nombre_dtss_tan=$nbdtsstan->fetch();

$nbdtsstci=$bdd->query('SELECT COUNT(*) AS nombre_dtss_tci FROM candidats WHERE niveau!="DTS" AND parcours1="TCI"   AND centre="Antsiranana"');
$nombre_dtss_tci=$nbdtsstci->fetch();

$nbdtsstghh=$bdd->query('SELECT COUNT(*) AS nombre_dtss_tghh FROM candidats WHERE niveau!="DTS" AND parcours1="TGH-H"   AND centre="Antsiranana"');
$nombre_dtss_tghh=$nbdtsstghh->fetch();

$nbdtsstght=$bdd->query('SELECT COUNT(*) AS nombre_dtss_tght FROM candidats WHERE niveau!="DTS" AND parcours1="TGH-T"   AND centre="Antsiranana"');
$nombre_dtss_tght=$nbdtsstght->fetch();

$nbdtsscgc=$bdd->query('SELECT COUNT(*) AS nombre_dtss_cgc FROM candidats WHERE niveau!="DTS" AND parcours1="CGC"   AND centre="Antsiranana"');
$nombre_dtss_cgc=$nbdtsscgc->fetch();

$nbdtssgfc=$bdd->query('SELECT COUNT(*) AS nombre_dtss_gfc FROM candidats WHERE niveau!="DTS" AND parcours1="CCA"   AND centre="Antsiranana"');
$nombre_dtss_gfc=$nbdtssgfc->fetch();




    $salles=array('AMPHI',
    'S1',
    'S2',
    'S3',
    'S4');
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

 for ($i=0; $i < 5 ; $i++) {
$salle=$salles[$i];
$nbdtss=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss FROM candidats WHERE niveau!="DTS" AND salle=?');
$nbdtss->execute(array($salle));
$nombre_dtss=$nbdtss->fetch();

$nbdtsssera=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_sera FROM candidats WHERE niveau!="DTS" AND parcours1="SERA" AND salle=?');
$nbdtsssera->execute(array($salle));
$nombre_dtss_sera=$nbdtsssera->fetch();

$nbdtssmure=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_mure FROM candidats WHERE niveau!="DTS" AND parcours1="MURE" AND salle=?');
$nbdtssmure->execute(array($salle));
$nombre_dtss_mure=$nbdtssmure->fetch();

$nbdtssmsa=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_msa FROM candidats WHERE niveau!="DTS" AND parcours1="MSA" AND salle=?');
$nbdtssmsa->execute(array($salle));
$nombre_dtss_msa=$nbdtssmsa->fetch();

$nbdtssadr=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_adr FROM candidats WHERE niveau!="DTS" AND parcours1="ADR" AND salle=?');
$nbdtssadr->execute(array($salle));
$nombre_dtss_adr=$nbdtssadr->fetch();

$nbdtssirm=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_irm FROM candidats WHERE niveau!="DTS" AND parcours1="IRM" AND salle=?');
$nbdtssirm->execute(array($salle));
$nombre_dtss_irm=$nbdtssirm->fetch();

$nbdtsscci_bat=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_cci_bat FROM candidats WHERE niveau!="DTS" AND parcours1="CCI-BAT" AND salle=?');
$nbdtsscci_bat->execute(array($salle));
$nombre_dtss_cci_bat=$nbdtsscci_bat->fetch();

$nbdtsstp=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_tp FROM candidats WHERE niveau!="DTS" AND parcours1="CCI-TP" AND salle=?');
$nbdtsstp->execute(array($salle));
$nombre_dtss_tp=$nbdtsstp->fetch();

$nbdtsstan=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_tan FROM candidats WHERE niveau!="DTS" AND parcours1="TAN" AND salle=?');
$nbdtsstan->execute(array($salle));
$nombre_dtss_tan=$nbdtsstan->fetch();

$nbdtsstci=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_tci FROM candidats WHERE niveau!="DTS" AND parcours1="TCI" AND salle=?');
$nbdtsstci->execute(array($salle));
$nombre_dtss_tci=$nbdtsstci->fetch();

$nbdtsstghh=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_tghh FROM candidats WHERE niveau!="DTS" AND parcours1="TGH-H" AND salle=?');
$nbdtsstghh->execute(array($salle));
$nombre_dtss_tghh=$nbdtsstghh->fetch();

$nbdtsstght=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_tght FROM candidats WHERE niveau!="DTS" AND parcours1="TGH-T" AND salle=?');
$nbdtsstght->execute(array($salle));
$nombre_dtss_tght=$nbdtsstght->fetch();

$nbdtsscgc=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_cgc FROM candidats WHERE niveau!="DTS" AND parcours1="CGC" AND salle=?');
$nbdtsscgc->execute(array($salle));
$nombre_dtss_cgc=$nbdtsscgc->fetch();

$nbdtssgfc=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_gfc FROM candidats WHERE niveau!="DTS" AND parcours1="CCA" AND salle=?');
$nbdtssgfc->execute(array($salle));
$nombre_dtss_gfc=$nbdtssgfc->fetch();

$nbdtssdpt=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_dpt FROM candidats WHERE niveau!="DTS" AND parcours1="DPT" AND salle=?');
$nbdtssdpt->execute(array($salle));
$nombre_dtss_dpt=$nbdtssdpt->fetch();


$nbdtssnte=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_nte FROM candidats WHERE niveau="INGENIORAT" AND parcours1="NTE" AND salle=?');
$nbdtssnte->execute(array($salle));
$nombre_dtss_nte=$nbdtssnte->fetch();

$nbdtsstami=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_tami FROM candidats WHERE niveau="INGENIORAT" AND parcours1="TAM-I" AND salle=?');
$nbdtsstami->execute(array($salle));
$nombre_dtss_tami=$nbdtsstami->fetch();

$nbdtsstamn=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_tamn FROM candidats WHERE niveau="INGENIORAT" AND parcours1="TAM-N" AND salle=?');
$nbdtsstamn->execute(array($salle));
$nombre_dtss_tamn=$nbdtsstamn->fetch();

$nbdtssice=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_ice FROM candidats WHERE niveau="INGENIORAT" AND parcours1="ICE" AND salle=?');
$nbdtssice->execute(array($salle));
$nombre_dtss_ice=$nbdtssice->fetch();

$nbdtssmeo=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_meo FROM candidats WHERE niveau="INGENIORAT" AND parcours1="MEO" AND salle=?');
$nbdtssmeo->execute(array($salle));
$nombre_dtss_meo=$nbdtssmeo->fetch();
?>


<table>
  <tr> <th>Centre</th> <th>Mention</th> <th>Parcours</th> <th>Effectif</th></tr>
<tr> <td rowspan="16"><?php echo $salle; ?></td> <td rowspan="4">ME</td> <td>SERA</td> <td><?php echo $nombre_dtss_sera['nombre_dtss_sera']; ?></td></tr>
<tr> <td>MURE</td> <td><?php echo $nombre_dtss_mure['nombre_dtss_mure']; ?></td> </tr>
<tr> <td>NTE</td> <td><?php echo $nombre_dtss_nte['nombre_dtss_nte']; ?></td> </tr>
<tr> <td>TAM-I</td> <td><?php echo $nombre_dtss_tami['nombre_dtss_tami']; ?></td> </tr>
<tr> <td rowspan="3">TC</td> <td>ADR</td> <td><?php echo $nombre_dtss_adr['nombre_dtss_adr']; ?></td></tr>
<tr> <td>IRM</td> <td><?php echo $nombre_dtss_irm['nombre_dtss_irm']; ?></td></tr>
<tr> <td>ICE</td> <td><?php echo $nombre_dtss_ice['nombre_dtss_ice']; ?></td></tr>
<tr> <td rowspan="2">GC</td> <td>CCI-BAT</td> <td><?php echo $nombre_dtss_cci_bat['nombre_dtss_cci_bat']; ?></td></tr>
<tr> <td>CCI-TP</td> <td><?php echo $nombre_dtss_tp['nombre_dtss_tp']; ?></td></tr>
<tr> <td  rowspan="2">GN</td> <td>TAN</td> <td><?php echo $nombre_dtss_tan['nombre_dtss_tan']; ?></td></tr>
<tr> <td>TAM-N</td> <td><?php echo $nombre_dtss_tamn['nombre_dtss_tamn']; ?></td> </tr>
<tr> <td rowspan="2">CS</td> <td>TCI</td> <td><?php echo $nombre_dtss_tci['nombre_dtss_tci']; ?></td></tr>
<tr> <td>DPT</td> <td><?php echo $nombre_dtss_dpt['nombre_dtss_dpt']; ?></td> </tr>
<tr> <td rowspan="2">FBA</td> <td>CGC</td> <td><?php echo $nombre_dtss_cgc['nombre_dtss_cgc']; ?></td></tr>
<tr> <td>CCA</td> <td><?php echo $nombre_dtss_gfc['nombre_dtss_gfc']; ?></td></tr>
<tr> <td>Management</td> <td>MEO</td> <td><?php echo $nombre_dtss_meo['nombre_dtss_meo']; ?></td></tr>
<tr> <td colspan="3"><b>TOTAL</b></td> <td><b><?php echo $nombre_dtss['nombre_dtss']; ?></b></td></tr>
</table>
  </div>
    
<p></p>
<?php }
?>


  </body>
</html>
