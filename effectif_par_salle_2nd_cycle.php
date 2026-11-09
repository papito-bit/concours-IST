<?php
ini_set('display_errors', 1);
ini_set('display_sta­cgcuperrors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['id_utilisateur']) AND !isset($_SESSION['email_concours'])){header("Location:login.php");}



require'connect.php';

$nb2nd_cycle=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle FROM candidats WHERE niveau!="DTS"');
$nombre_2nd_cycle=$nb2nd_cycle->fetch();

$nb2nd_cycletci=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_tci FROM candidats WHERE niveau!="DTS" AND parcours1="TCI"');
$nombre_2nd_cycle_tci=$nb2nd_cycletci->fetch();

$nb2nd_cyclemcd=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_mcd FROM candidats WHERE niveau!="DTS" AND parcours1="MCD"');
$nombre_2nd_cycle_mcd=$nb2nd_cyclemcd->fetch();

$nb2nd_cyclecca=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_cca FROM candidats WHERE niveau!="DTS" AND parcours1="MSA"');
$nombre_2nd_cycle_cca=$nb2nd_cyclecca->fetch();

$nb2nd_cyclecgc=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_cgc FROM candidats WHERE niveau!="DTS" AND parcours1="RT"');
$nombre_2nd_cycle_cgc=$nb2nd_cyclecgc->fetch();

$nb2nd_cycleccibat=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_ccibat FROM candidats WHERE niveau!="DTS" AND parcours1="TIM"');
$nombre_2nd_cycle_ccibat=$nb2nd_cycleccibat->fetch();

$nb2nd_cyclegcatp=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_gcatp FROM candidats WHERE niveau!="DTS" AND parcours1="TBM"');
$nombre_2nd_cycle_gcatp=$nb2nd_cyclegcatp->fetch();



$nb2nd_cyclegcabat=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_gcabat FROM candidats WHERE niveau!="DTS" AND parcours1="Mines_EM"');
$nombre_2nd_cycle_gcabat=$nb2nd_cyclegcabat->fetch();

$nb2nd_cycleicmn=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_icmn FROM candidats WHERE niveau!="DTS" AND parcours1="Mines_TM"');
$nombre_2nd_cycle_icmn=$nb2nd_cycleicmn->fetch();



$nb2nd_cyclebat=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_bat FROM candidats WHERE niveau!="DTS" AND parcours1="BAT"');
$nombre_2nd_cycle_bat=$nb2nd_cyclebat->fetch();

$nb2nd_cycletp=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_tp FROM candidats WHERE niveau!="DTS" AND parcours1="TP"');
$nombre_2nd_cycle_tp=$nb2nd_cycletp->fetch();

$nb2nd_cyclemure=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_mure FROM candidats WHERE niveau!="DTS" AND parcours1="TAF"');
$nombre_2nd_cycle_mure=$nb2nd_cyclemure->fetch();

$nb2nd_cyclesera=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_sera FROM candidats WHERE niveau!="DTS" AND parcours1="TecNa"');
$nombre_2nd_cycle_sera=$nb2nd_cyclesera->fetch();

$nb2nd_cyclemam=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_mam FROM candidats WHERE niveau!="DTS" AND parcours1="PAq"');
$nombre_2nd_cycle_mam=$nb2nd_cyclemam->fetch();

$nb2nd_cycleMEITE=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_MEITE FROM candidats WHERE niveau!="DTS" AND parcours1="COM"');
$nombre_2nd_cycle_MEITE=$nb2nd_cycleMEITE->fetch();

$nb2nd_cyclegmp=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_gmp FROM candidats WHERE niveau!="DTS" AND parcours1="RH"');
$nombre_2nd_cycle_gmp=$nb2nd_cyclegmp->fetch();

$nb2nd_cycleirm=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_irm FROM candidats WHERE niveau!="DTS" AND parcours1="GAN"');
$nombre_2nd_cycle_irm=$nb2nd_cycleirm->fetch();

$nb2nd_cycleadrp=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_adrp FROM candidats WHERE niveau!="DTS" AND parcours1="TBA"');
$nombre_2nd_cycle_adrp=$nb2nd_cycleadrp->fetch();

$nb2nd_cycleadrl=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_adrl FROM candidats WHERE niveau!="DTS" AND parcours1="GFC"');
$nombre_2nd_cycle_adrl=$nb2nd_cycleadrl->fetch();

$nb2nd_cycledpt=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_dpt FROM candidats WHERE niveau!="DTS" AND parcours1="AGRI"');
$nombre_2nd_cycle_dpt=$nb2nd_cycledpt->fetch();

$nb2nd_cycledpt=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_dpt FROM candidats WHERE niveau!="DTS" AND parcours1="PAn"');
$nombre_2nd_cycle_dpt=$nb2nd_cycledpt->fetch();

$nb2nd_cycleif=$bdd->query('SELECT COUNT(*) AS nombre_2nd_cycle_if FROM candidats WHERE niveau!="DTS" AND parcours1="IAA"');
$nombre_2nd_cycle_if=$nb2nd_cycleif->fetch();


    $salles=array('AMPHI',
'S3',
'S4',
'S6',
'S7',
'S8',
'SD1',
     );
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

 for ($i=0; $i < 7 ; $i++) {
$salle=$salles[$i];
$nb2nd_cycle=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle FROM candidats WHERE niveau!="DTS" AND salle=?');
$nb2nd_cycle->execute(array($salle));
$nombre_2nd_cycle=$nb2nd_cycle->fetch();

$nb2nd_cycletci=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_tci FROM candidats WHERE niveau!="DTS" AND parcours1="TCI" AND salle=?');
$nb2nd_cycletci->execute(array($salle));
$nombre_2nd_cycle_tci=$nb2nd_cycletci->fetch();

$nb2nd_cyclemcd=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_mcd FROM candidats WHERE niveau!="DTS" AND parcours1="MCD" AND salle=?');
$nb2nd_cyclemcd->execute(array($salle));
$nombre_2nd_cycle_mcd=$nb2nd_cyclemcd->fetch();

$nb2nd_cyclecca=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_cca FROM candidats WHERE niveau!="DTS" AND parcours1="CCA" AND salle=?');
$nb2nd_cyclecca->execute(array($salle));
$nombre_2nd_cycle_cca=$nb2nd_cyclecca->fetch();

$nb2nd_cyclecgc=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_cgc FROM candidats WHERE niveau!="DTS" AND parcours1="CGC" AND salle=?');
$nb2nd_cyclecgc->execute(array($salle));
$nombre_2nd_cycle_cgc=$nb2nd_cyclecgc->fetch();

$nb2nd_cycleccibat=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_ccibat FROM candidats WHERE niveau!="DTS" AND parcours1="CCI-BAT" AND salle=?');
$nb2nd_cycleccibat->execute(array($salle));
$nombre_2nd_cycle_ccibat=$nb2nd_cycleccibat->fetch();

$nb2nd_cycleccitp=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_ccitp FROM candidats WHERE niveau!="DTS" AND parcours1="CCI-TP" AND salle=?');
$nb2nd_cycleccitp->execute(array($salle));
$nombre_2nd_cycle_ccitp=$nb2nd_cycleccitp->fetch();

$nb2nd_cycletan=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_tan FROM candidats WHERE niveau!="DTS" AND parcours1="TAN" AND salle=?');
$nb2nd_cycletan->execute(array($salle));
$nombre_2nd_cycle_tan=$nb2nd_cycletan->fetch();

$nb2nd_cyclemure=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_mure FROM candidats WHERE niveau!="DTS" AND parcours1="MURE" AND salle=?');
$nb2nd_cyclemure->execute(array($salle));
$nombre_2nd_cycle_mure=$nb2nd_cyclemure->fetch();

$nb2nd_cyclesera=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_sera FROM candidats WHERE niveau!="DTS" AND parcours1="SERA" AND salle=?');
$nb2nd_cyclesera->execute(array($salle));
$nombre_2nd_cycle_sera=$nb2nd_cyclesera->fetch();

$nb2nd_cyclemam=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_mam FROM candidats WHERE niveau!="DTS" AND parcours1="MAM" AND salle=?');
$nb2nd_cyclemam->execute(array($salle));
$nombre_2nd_cycle_mam=$nb2nd_cyclemam->fetch();

$nb2nd_cyclemeite=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_meite FROM candidats WHERE niveau!="DTS" AND parcours1="MEITE-L" AND salle=?');
$nb2nd_cyclemeite->execute(array($salle));
$nombre_2nd_cycle_meite=$nb2nd_cyclemeite->fetch();

$nb2nd_cyclegmp=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_gmp FROM candidats WHERE niveau!="DTS" AND parcours1="GMP" AND salle=?');
$nb2nd_cyclegmp->execute(array($salle));
$nombre_2nd_cycle_gmp=$nb2nd_cyclegmp->fetch();

$nb2nd_cycleirm=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_irm FROM candidats WHERE niveau!="DTS" AND parcours1="IRM" AND salle=?');
$nb2nd_cycleirm->execute(array($salle));
$nombre_2nd_cycle_irm=$nb2nd_cycleirm->fetch();

$nb2nd_cycleadrp=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_adrp FROM candidats WHERE niveau!="DTS" AND parcours1="ADR-P" AND salle=?');
$nb2nd_cycleadrp->execute(array($salle));
$nombre_2nd_cycle_adrp=$nb2nd_cycleadrp->fetch();

$nb2nd_cycleadrl=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_adrl FROM candidats WHERE niveau!="DTS" AND parcours1="ADR-L" AND salle=?');
$nb2nd_cycleadrl->execute(array($salle));
$nombre_2nd_cycle_adrl=$nb2nd_cycleadrl->fetch();

$nb2nd_cycledpt=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_dpt FROM candidats WHERE niveau!="DTS" AND parcours1="DPT" AND salle=?');
$nb2nd_cycledpt->execute(array($salle));
$nombre_2nd_cycle_dpt=$nb2nd_cycledpt->fetch();

$nb2nd_cyclegeh=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_geh FROM candidats WHERE niveau!="DTS" AND parcours1="GEH" AND salle=?');
$nb2nd_cyclegeh->execute(array($salle));
$nombre_2nd_cycle_geh=$nb2nd_cyclegeh->fetch();

$nb2nd_cycleif=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_if FROM candidats WHERE niveau!="DTS" AND parcours1="IF" AND salle=?');
$nb2nd_cycleif->execute(array($salle));
$nombre_2nd_cycle_if=$nb2nd_cycleif->fetch();


$nb2nd_cyclegcatp=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_gcatp FROM candidats WHERE niveau!="DTS" AND parcours1="GCA-TP" AND salle=?');
$nb2nd_cyclegcatp->execute(array($salle));
$nombre_2nd_cycle_gcatp=$nb2nd_cyclegcatp->fetch();

$nb2nd_cyclegcabat=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_gcabat FROM candidats WHERE niveau!="DTS" AND parcours1="GCA-BAT" AND salle=?');
$nb2nd_cyclegcabat->execute(array($salle));
$nombre_2nd_cycle_gcabat=$nb2nd_cyclegcabat->fetch();

$nb2nd_cycleicmn=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_icmn FROM candidats WHERE niveau!="DTS" AND parcours1="ICMN" AND salle=?');
$nb2nd_cycleicmn->execute(array($salle));
$nombre_2nd_cycle_icmn=$nb2nd_cycleicmn->fetch();

$nb2nd_cyclemeo=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_meo FROM candidats WHERE niveau!="DTS" AND parcours1="MEO" AND salle=?');
$nb2nd_cyclemeo->execute(array($salle));
$nombre_2nd_cycle_meo=$nb2nd_cyclemeo->fetch();

$nb2nd_cycletamp=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_tamp FROM candidats WHERE niveau!="DTS" AND parcours1="TAM-P" AND salle=?');
$nb2nd_cycletamp->execute(array($salle));
$nombre_2nd_cycle_tamp=$nb2nd_cycletamp->fetch();

$nb2nd_cycletaml=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_taml FROM candidats WHERE niveau!="DTS" AND parcours1="TAM-L" AND salle=?');
$nb2nd_cycletaml->execute(array($salle));
$nombre_2nd_cycle_taml=$nb2nd_cycletaml->fetch();

$nb2nd_cyclente=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_nte FROM candidats WHERE niveau!="DTS" AND parcours1="NTE" AND salle=?');
$nb2nd_cyclente->execute(array($salle));
$nombre_2nd_cycle_nte=$nb2nd_cyclente->fetch();

$nb2nd_cycleicep=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_icep FROM candidats WHERE niveau!="DTS" AND parcours1="ICE-P" AND salle=?');
$nb2nd_cycleicep->execute(array($salle));
$nombre_2nd_cycle_icep=$nb2nd_cycleicep->fetch();

$nb2nd_cycleicel=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_icel FROM candidats WHERE niveau!="DTS" AND parcours1="ICE-L" AND salle=?');
$nb2nd_cycleicel->execute(array($salle));
$nombre_2nd_cycle_icel=$nb2nd_cycleicel->fetch();

$nb2nd_cyclegcfl=$bdd->prepare('SELECT COUNT(*) AS nombre_2nd_cycle_gcfl FROM candidats WHERE niveau!="DTS" AND parcours1="GC-FL" AND salle=?');
$nb2nd_cyclegcfl->execute(array($salle));
$nombre_2nd_cycle_gcfl=$nb2nd_cyclegcfl->fetch();
?>


<table>
  <tr> <th>Salle</th> <th>Mention</th> <th>Parcours</th> <th>Effectif</th></tr>
<tr> <td rowspan="28"><?php echo $salle; ?></td> <td rowspan="8">ME</td> <td>SERA</td> <td><?php echo $nombre_2nd_cycle_sera['nombre_2nd_cycle_sera']; ?></td></tr>
<tr> <td>MURE</td> <td><?php echo $nombre_2nd_cycle_mure['nombre_2nd_cycle_mure']; ?></td> </tr>
<tr> <td>MEITE</td> <td><?php echo $nombre_2nd_cycle_meite['nombre_2nd_cycle_meite']; ?></td> </tr>
<tr> <td>MAM</td> <td><?php echo $nombre_2nd_cycle_mam['nombre_2nd_cycle_mam']; ?></td> </tr>
<tr> <td>TAM-P</td> <td><?php echo $nombre_2nd_cycle_tamp['nombre_2nd_cycle_tamp']; ?></td> </tr>
<tr> <td>TAM-L</td> <td><?php echo $nombre_2nd_cycle_taml['nombre_2nd_cycle_taml']; ?></td> </tr>
<tr> <td>NTE</td> <td><?php echo $nombre_2nd_cycle_meite['nombre_2nd_cycle_meite']; ?></td> </tr>
<tr> <td>GC-FL</td> <td><?php echo $nombre_2nd_cycle_gcfl['nombre_2nd_cycle_gcfl']; ?></td> </tr>

<tr> <td rowspan="5">TC</td> <td>ADR-P</td> <td><?php echo $nombre_2nd_cycle_adrp['nombre_2nd_cycle_adrp']; ?></td></tr>
<tr> <td>ADR-L</td> <td><?php echo $nombre_2nd_cycle_adrl['nombre_2nd_cycle_adrl']; ?></td></tr>
<tr> <td>IRM</td> <td><?php echo $nombre_2nd_cycle_irm['nombre_2nd_cycle_irm']; ?></td></tr>
<tr> <td>ICE-P</td> <td><?php echo $nombre_2nd_cycle_icep['nombre_2nd_cycle_icep']; ?></td></tr>
<tr> <td>ICE-L</td> <td><?php echo $nombre_2nd_cycle_icel['nombre_2nd_cycle_icel']; ?></td></tr>

<tr> <td rowspan="4">CS/TH</td> <td>TCI</td> <td><?php echo $nombre_2nd_cycle_tci['nombre_2nd_cycle_tci']; ?></td></tr>
<tr> <td>MCD</td> <td><?php echo $nombre_2nd_cycle_mcd['nombre_2nd_cycle_mcd']; ?></td></tr>
<tr> <td>DPT</td> <td><?php echo $nombre_2nd_cycle_dpt['nombre_2nd_cycle_dpt']; ?></td></tr>
<tr> <td>GEH</td> <td><?php echo $nombre_2nd_cycle_geh['nombre_2nd_cycle_geh']; ?></td></tr>

<tr> <td rowspan="1">MEB</td> <td>GMP</td> <td><?php echo $nombre_2nd_cycle_gmp['nombre_2nd_cycle_gmp']; ?></td></tr>

<tr> <td rowspan="3">FBA</td> <td>CCA</td> <td><?php echo $nombre_2nd_cycle_tci['nombre_2nd_cycle_tci']; ?></td></tr>
<tr> <td>CGC</td> <td><?php echo $nombre_2nd_cycle_mcd['nombre_2nd_cycle_mcd']; ?></td></tr>
<tr> <td>IF</td> <td><?php echo $nombre_2nd_cycle_dpt['nombre_2nd_cycle_dpt']; ?></td></tr>

<tr> <td rowspan="1">Management</td> <td>MEO</td> <td><?php echo $nombre_2nd_cycle_meo['nombre_2nd_cycle_meo']; ?></td></tr>

<tr> <td rowspan="4">GC</td> <td>CCI-BAT</td> <td><?php echo $nombre_2nd_cycle_ccibat['nombre_2nd_cycle_ccibat']; ?></td></tr>
<tr> <td>CCI-TP</td> <td><?php echo $nombre_2nd_cycle_ccitp['nombre_2nd_cycle_ccitp']; ?></td></tr>
<tr> <td>GCA-BAT</td> <td><?php echo $nombre_2nd_cycle_gcabat['nombre_2nd_cycle_gcabat']; ?></td></tr>
<tr> <td>GCA-TP</td> <td><?php echo $nombre_2nd_cycle_gcatp['nombre_2nd_cycle_gcatp']; ?></td></tr>

<tr> <td rowspan="2">GN</td> <td>TAN</td> <td><?php echo $nombre_2nd_cycle_tan['nombre_2nd_cycle_tan']; ?></td></tr>
<tr> <td>ICMN</td> <td><?php echo $nombre_2nd_cycle_icmn['nombre_2nd_cycle_icmn']; ?></td></tr>



</table>
  </div>
    
<p></p>
<?php }
?>


  </body>
</html>
