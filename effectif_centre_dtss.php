<?php
session_start();
if(!isset($_SESSION['id_utilisateur']) AND !isset($_SESSION['email_concours'])){header("Location:login.php");}



require'connect.php';






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
$nbdtss=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss FROM candidats WHERE niveau!="DTS" AND centre=?');
$nbdtss->execute(array($centre));
$nombre_dtss=$nbdtss->fetch();

$nbdtsssera=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_sera FROM candidats WHERE niveau="DTSS" AND parcours1="SERA" AND centre=?');
$nbdtsssera->execute(array($centre));
$nombre_dtss_sera=$nbdtsssera->fetch();

$nbdtssmure=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_mure FROM candidats WHERE niveau="DTSS" AND parcours1="MURE" AND centre=?');
$nbdtssmure->execute(array($centre));
$nombre_dtss_mure=$nbdtssmure->fetch();

$nbdtssmam=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_mam FROM candidats WHERE niveau="DTSS" AND parcours1="MAM" AND centre=?');
$nbdtssmam->execute(array($centre));
$nombre_dtss_mam=$nbdtssmam->fetch();

$nbdtssadrp=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_adrp FROM candidats WHERE niveau="DTSS" AND parcours1="ADR-P" AND centre=?');
$nbdtssadrp->execute(array($centre));
$nombre_dtss_adrp=$nbdtssadrp->fetch();

$nbdtssadrl=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_adrl FROM candidats WHERE niveau="DTSS" AND parcours1="ADR-L" AND centre=?');
$nbdtssadrl->execute(array($centre));
$nombre_dtss_adrl=$nbdtssadrl->fetch();

$nbdtssirm=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_irm FROM candidats WHERE niveau="DTSS" AND parcours1="IRM" AND centre=?');
$nbdtssirm->execute(array($centre));
$nombre_dtss_irm=$nbdtssirm->fetch();

$nbdtsscci_bat=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_cci_bat FROM candidats WHERE niveau="DTSS" AND parcours1="CCI-BAT" AND centre=?');
$nbdtsscci_bat->execute(array($centre));
$nombre_dtss_cci_bat=$nbdtsscci_bat->fetch();

$nbdtsscci_tp=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_cci_tp FROM candidats WHERE niveau="DTSS" AND parcours1="CCI-TP" AND centre=?');
$nbdtsscci_tp->execute(array($centre));
$nombre_dtss_cci_tp=$nbdtsscci_tp->fetch();

$nbdtsstan=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_tan FROM candidats WHERE niveau="DTSS" AND parcours1="TAN" AND centre=?');
$nbdtsstan->execute(array($centre));
$nombre_dtss_tan=$nbdtsstan->fetch();

$nbdtssgmp=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_gmp FROM candidats WHERE niveau="DTSS" AND parcours1="GMP" AND centre=?');
$nbdtssgmp->execute(array($centre));
$nombre_dtss_gmp=$nbdtssgmp->fetch();

$nbdtsstci=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_tci FROM candidats WHERE niveau="DTSS" AND parcours1="TCI" AND centre=?');
$nbdtsstci->execute(array($centre));
$nombre_dtss_tci=$nbdtsstci->fetch();


$nbdtsscgc=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_cgc FROM candidats WHERE niveau="DTSS" AND parcours1="CGC" AND centre=?');
$nbdtsscgc->execute(array($centre));
$nombre_dtss_cgc=$nbdtsscgc->fetch();

$nbdtsscca=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_cca FROM candidats WHERE niveau="DTSS" AND parcours1="CCA" AND centre=?');
$nbdtsscca->execute(array($centre));
$nombre_dtss_cca=$nbdtsscca->fetch();

$nbdtssdpt=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_dpt FROM candidats WHERE niveau="DTSS" AND parcours1="DPT" AND centre=?');
$nbdtssdpt->execute(array($centre));
$nombre_dtss_dpt=$nbdtssdpt->fetch();

$nbdtssmcd=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_mcd FROM candidats WHERE niveau="DTSS" AND parcours1="MCD" AND centre=?');
$nbdtssmcd->execute(array($centre));
$nombre_dtss_mcd=$nbdtssmcd->fetch();

$nbdtssgeh=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_geh FROM candidats WHERE niveau="DTSS" AND parcours1="GEH" AND centre=?');
$nbdtssgeh->execute(array($centre));
$nombre_dtss_geh=$nbdtssgeh->fetch();


$nbdtssnte=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_nte FROM candidats WHERE niveau="INGENIORAT" AND parcours1="NTE" AND centre=?');
$nbdtssnte->execute(array($centre));
$nombre_dtss_nte=$nbdtssnte->fetch();

$nbdtssgca=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_gca FROM candidats WHERE niveau="INGENIORAT" AND parcours1="GCA" AND centre=?');
$nbdtssgca->execute(array($centre));
$nombre_dtss_gca=$nbdtssgca->fetch();

$nbdtssicmn=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_icmn FROM candidats WHERE niveau="INGENIORAT" AND parcours1="ICMN" AND centre=?');
$nbdtssicmn->execute(array($centre));
$nombre_dtss_icmn=$nbdtssicmn->fetch();

$nbdtsstamp=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_tamp FROM candidats WHERE niveau="INGENIORAT" AND parcours1="TAM-P" AND centre=?');
$nbdtsstamp->execute(array($centre));
$nombre_dtss_tamp=$nbdtsstamp->fetch();

$nbdtsstaml=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_taml FROM candidats WHERE niveau="INGENIORAT" AND parcours1="TAM-L" AND centre=?');
$nbdtsstaml->execute(array($centre));
$nombre_dtss_taml=$nbdtsstaml->fetch();

$nbdtssgcfl=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_gcfl FROM candidats WHERE niveau="INGENIORAT" AND parcours1="GC-FL" AND centre=?');
$nbdtssgcfl->execute(array($centre));
$nombre_dtss_gcfl=$nbdtssgcfl->fetch();

$nbdtssice=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_ice FROM candidats WHERE niveau="INGENIORAT" AND parcours1="ICE" AND centre=?');
$nbdtssice->execute(array($centre));
$nombre_dtss_ice=$nbdtssice->fetch();

$nbdtssmeo=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss_meo FROM candidats WHERE niveau="INGENIORAT" AND parcours1="MEO" AND centre=?');
$nbdtssmeo->execute(array($centre));
$nombre_dtss_meo=$nbdtssmeo->fetch();
?>


<table>
  <tr> <th>Centre</th> <th>Mention</th> <th>Parcours</th> <th>Effectif</th></tr>
<tr> <td rowspan="24"><?php echo $centre; ?></td> <td rowspan="7">ME</td> <td>SERA</td> <td><?php echo $nombre_dtss_sera['nombre_dtss_sera']; ?></td></tr>
<tr> <td>MURE</td> <td><?php echo $nombre_dtss_mure['nombre_dtss_mure']; ?></td> </tr>
<tr> <td>MAM</td> <td><?php echo $nombre_dtss_mam['nombre_dtss_mam']; ?></td> </tr>
<tr> <td>NTE</td> <td><?php echo $nombre_dtss_nte['nombre_dtss_nte']; ?></td> </tr>
<tr> <td>TAM-P</td> <td><?php echo $nombre_dtss_tamp['nombre_dtss_tamp']; ?></td> </tr>
<tr> <td>TAM-L</td> <td><?php echo $nombre_dtss_taml['nombre_dtss_taml']; ?></td> </tr>
<tr> <td>GC-FL</td> <td><?php echo $nombre_dtss_gcfl['nombre_dtss_gcfl']; ?></td> </tr>
<tr> <td rowspan="4">TC</td> <td>ADR-P</td> <td><?php echo $nombre_dtss_adrp['nombre_dtss_adrp']; ?></td></tr>
<tr><td>ADR-L</td> <td><?php echo $nombre_dtss_adrl['nombre_dtss_adrl']; ?></td></tr>
<tr> <td>IRM</td> <td><?php echo $nombre_dtss_irm['nombre_dtss_irm']; ?></td></tr>
<tr> <td>ICE</td> <td><?php echo $nombre_dtss_ice['nombre_dtss_ice']; ?></td></tr>
<tr> <td rowspan="3">GC</td> <td>CCI-BAT</td> <td><?php echo $nombre_dtss_cci_bat['nombre_dtss_cci_bat']; ?></td></tr>
<tr> <td>CCI-TP</td> <td><?php echo $nombre_dtss_cci_tp['nombre_dtss_cci_tp']; ?></td></tr>
<tr> <td>GCA</td> <td><?php echo $nombre_dtss_gca['nombre_dtss_gca']; ?></td></tr>
<tr> <td  rowspan="3">GN</td> <td>TAN</td> <td><?php echo $nombre_dtss_tan['nombre_dtss_tan']; ?></td></tr>
<tr> <td>GMP</td> <td><?php echo $nombre_dtss_gmp['nombre_dtss_gmp']; ?></td></tr>
<tr><td>ICMN</td> <td><?php echo $nombre_dtss_icmn['nombre_dtss_icmn']; ?></td></tr>
<tr> <td rowspan="4">CS</td> <td>TCI</td> <td><?php echo $nombre_dtss_tci['nombre_dtss_tci']; ?></td></tr>
<tr> <td>DPT</td> <td><?php echo $nombre_dtss_dpt['nombre_dtss_dpt']; ?></td> </tr>
<tr> <td>MCD</td> <td><?php echo $nombre_dtss_mcd['nombre_dtss_mcd']; ?></td> </tr>
<tr> <td>GEH</td> <td><?php echo $nombre_dtss_geh['nombre_dtss_geh']; ?></td> </tr>
<tr> <td rowspan="2">FBA</td> <td>CGC</td> <td><?php echo $nombre_dtss_cgc['nombre_dtss_cgc']; ?></td></tr>
<tr> <td>CCA</td> <td><?php echo $nombre_dtss_cca['nombre_dtss_cca']; ?></td></tr>
<tr> <td>Management</td> <td>MEO</td> <td><?php echo $nombre_dtss_meo['nombre_dtss_meo']; ?></td></tr>
<tr> <td colspan="3"><b>TOTAL</b></td> <td><b><?php echo $nombre_dtss['nombre_dtss']; ?></b></td></tr>
</table>
  </div>
    
<p></p>
<?php }
?>


  </body>
</html>
