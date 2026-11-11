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

$nbdtstecna=$bdd->query('SELECT COUNT(*) AS nombre_dts_tecna FROM candidats WHERE niveau="DTS" AND parcours1="TecNa"');
$nombre_dts_tecna=$nbdtstecna->fetch();

$nbdtscom=$bdd->query('SELECT COUNT(*) AS nombre_dts_com FROM candidats WHERE niveau="DTS" AND parcours1="COM"');
$nombre_dts_com=$nbdtscom->fetch();

$nbdtstghh=$bdd->query('SELECT COUNT(*) AS nombre_dts_tghh FROM candidats WHERE niveau="DTS" AND parcours1="TGH-H"');
$nombre_dts_tghh=$nbdtstghh->fetch();

$nbdtstght=$bdd->query('SELECT COUNT(*) AS nombre_dts_tght FROM candidats WHERE niveau="DTS" AND parcours1="TGH-T"');
$nombre_dts_tght=$nbdtstght->fetch();

$nbdtstba=$bdd->query('SELECT COUNT(*) AS nombre_dts_tba FROM candidats WHERE niveau="DTS" AND parcours1="TBA"');
$nombre_dts_tba=$nbdtstba->fetch();

$nbdtsgfc=$bdd->query('SELECT COUNT(*) AS nombre_dts_gfc FROM candidats WHERE niveau="DTS" AND parcours1="GFC"');
$nombre_dts_gfc=$nbdtsgfc->fetch();




    $centres=array('Antsiranana',
    'Ambanja',
    'Sambava',
    'Mahajanga',
    'Antananarivo',
    'Toamasina',
    'Ambositra',
    'Fianarantsoa',
    'Manakara',
    'Toliary');
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

$nbdtstecna=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_tecna FROM candidats WHERE niveau="DTS" AND parcours1="TecNa" AND centre=?');
$nbdtstecna->execute(array($centre));
$nombre_dts_tecna=$nbdtstecna->fetch();

$nbdtscom=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_com FROM candidats WHERE niveau="DTS" AND parcours1="COM" AND centre=?');
$nbdtscom->execute(array($centre));
$nombre_dts_com=$nbdtscom->fetch();

$nbdtstghh=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_tghh FROM candidats WHERE niveau="DTS" AND parcours1="TGH-H" AND centre=?');
$nbdtstghh->execute(array($centre));
$nombre_dts_tghh=$nbdtstghh->fetch();

$nbdtstght=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_tght FROM candidats WHERE niveau="DTS" AND parcours1="TGH-T" AND centre=?');
$nbdtstght->execute(array($centre));
$nombre_dts_tght=$nbdtstght->fetch();

$nbdtstba=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_tba FROM candidats WHERE niveau="DTS" AND parcours1="TBA" AND centre=?');
$nbdtstba->execute(array($centre));
$nombre_dts_tba=$nbdtstba->fetch();

$nbdtsgfc=$bdd->prepare('SELECT COUNT(*) AS nombre_dts_gfc FROM candidats WHERE niveau="DTS" AND parcours1="GFC" AND centre=?');
$nbdtsgfc->execute(array($centre));
$nombre_dts_gfc=$nbdtsgfc->fetch();

?>


<table>
  <tr> <th>Centre</th> <th>Mention</th> <th>Parcours</th> <th>Effectif</th></tr>
<tr> <td rowspan="13"><?php echo $centre; ?></td> <td rowspan="3">ME</td> <td>MEEM</td> <td><?php echo $nombre_dts_meem['nombre_dts_meem']; ?></td></tr>
<tr> <td>MEEFT</td> <td><?php echo $nombre_dts_meft['nombre_dts_meft']; ?></td> </tr>
<tr> <td>MSA</td> <td><?php echo $nombre_dts_msa['nombre_dts_msa']; ?></td> </tr>
<tr> <td rowspan="2">TC</td> <td>RT</td> <td><?php echo $nombre_dts_rt['nombre_dts_rt']; ?></td></tr>
<tr> <td>TIM</td> <td><?php echo $nombre_dts_tim['nombre_dts_tim']; ?></td></tr>
<tr> <td rowspan="2">GC</td> <td>BAT</td> <td><?php echo $nombre_dts_bat['nombre_dts_bat']; ?></td></tr>
<tr> <td>TP</td> <td><?php echo $nombre_dts_tp['nombre_dts_tp']; ?></td></tr>
<tr> <td>GN</td> <td>TecNa</td> <td><?php echo $nombre_dts_tecna['nombre_dts_tecna']; ?></td></tr>
<tr> <td rowspan="3">CS</td> <td>COM</td> <td><?php echo $nombre_dts_com['nombre_dts_com']; ?></td></tr>
<tr> <td>TGH-H</td> <td><?php echo $nombre_dts_tghh['nombre_dts_tghh']; ?></td> </tr>
<tr> <td>TGH-T</td> <td><?php echo $nombre_dts_tght['nombre_dts_tght']; ?></td> </tr>
<tr> <td rowspan="2">FBA</td> <td>TBA</td> <td><?php echo $nombre_dts_tba['nombre_dts_tba']; ?></td></tr>
<tr> <td>GFC</td> <td><?php echo $nombre_dts_gfc['nombre_dts_gfc']; ?></td></tr>
<tr> <td colspan="3"><b>TOTAL</b></td> <td><b><?php echo $nombre_dts['nombre_dts']; ?></b></td></tr>
</table>
  </div>
    
<p></p>
<?php }
?>


  </body>
</html>
