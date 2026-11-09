<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Concours IST-D 2024-2025</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
</head>
<?php
session_start();
if(!isset($_SESSION['id_utilisateur']) AND !isset($_SESSION['email_concours'])){header("Location:login.php");}


require'connect.php';


$requette=$bdd->prepare('SELECT * FROM parcours WHERE grade_abrevie=? ORDER BY mention_abrevie');
$requette->execute(array("DTS"));

$requette2=$bdd->prepare('SELECT * FROM parcours WHERE grade_abrevie=? ORDER BY mention_abrevie');
$requette2->execute(array("DTS"));


//Pour tableau DTS
$nbdts_total=$bdd->query('SELECT COUNT(*) AS nombre_dts_total FROM candidats WHERE niveau="DTS"');
$nombre_dts_total=$nbdts_total->fetch();

$i=1;
while ($parc=$requette->fetch()) {

$nbdts=$bdd->prepare('SELECT COUNT(*) AS nombre_effectif FROM candidats WHERE niveau=? AND parcours1=?');
$nbdts->execute(array("DTS", $parc['parcours_abrevie']));
$nombre_dts=$nbdts->fetch();
$effectif[$i]=$nombre_dts["nombre_effectif"];
$i++;
}



$requette3=$bdd->prepare('SELECT * FROM parcours WHERE grade_abrevie=? ORDER BY mention_abrevie');
$requette3->execute(array("DTSS"));

$requette4=$bdd->prepare('SELECT * FROM parcours WHERE grade_abrevie=? ORDER BY mention_abrevie');
$requette4->execute(array("DTSS"));

//Pour tableau DTSS
$nbdtss_total=$bdd->query('SELECT COUNT(*) AS nombre_dtss_total FROM candidats WHERE niveau="DTSS"');
$nombre_dtss_total=$nbdtss_total->fetch();


$j=1;
while ($parc=$requette3->fetch()) {
$nbdtss=$bdd->prepare('SELECT COUNT(*) AS nombre_effectif_dtss FROM candidats WHERE niveau=? AND parcours1=?');
$nbdtss->execute(array("DTSS", $parc['parcours_abrevie']));
$nombre_dtss=$nbdtss->fetch();
$effectif_dtss[$j]=$nombre_dtss["nombre_effectif_dtss"];
$j++;
}



$requette5=$bdd->prepare('SELECT * FROM parcours WHERE grade_abrevie=? ORDER BY mention_abrevie');
$requette5->execute(array("ING"));

$requette6=$bdd->prepare('SELECT * FROM parcours WHERE grade_abrevie=? ORDER BY mention_abrevie');
$requette6->execute(array("ING"));

$nbing_total=$bdd->query('SELECT COUNT(*) AS nombre_ing_total FROM candidats WHERE niveau="INGENIORAT"');
$nombre_ing_total=$nbing_total->fetch();

$k=1;
while ($parc=$requette5->fetch()) {
$nbing=$bdd->prepare('SELECT COUNT(*) AS nombre_ing FROM candidats WHERE niveau=? AND parcours1=?');
$nbing->execute(array("INGENIORAT", $parc['parcours_abrevie']));
$nombre_ing=$nbing->fetch();
$nombre_dtss=$nbdtss->fetch();
$effectif_ing[$k]=$nombre_ing["nombre_ing"];
$k++;
}



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
    </style>
  </head>
  <body>
<h4><i>Institut Supérieur de Technologie d'Antsiranana<br/>
Effectif des candidats au concours 2024-2025</i></h4>
  
    <div class="row">
        <div class="col-md-3">
<h4>DTS</h4>
<table>
  <tr><th>Parcours</th> <th>Effectif</th></tr>

<?php $ii=1; while ($parcours_liste=$requette2->fetch()) { ?>
  <tr><td><?php echo $parcours_liste["parcours_abrevie"]; ?></td> <td><?php echo $effectif[$ii]; ?></td></tr>
<?php $ii++;} ?>
<tr> <td><b>TOTAL</b></td> <td><b><?php echo $nombre_dts_total["nombre_dts_total"]; ?></b></td></tr>
</table>

<p></p>
</div>
<div class="col-md-3">
<h4>DTSS</h4>
<table>
  <tr><th>Parcours</th> <th>Effectif</th></tr>

<?php $jj=1; while ($parcours_dtss_liste=$requette4->fetch()) { ?>
  <tr><td><?php echo $parcours_dtss_liste["parcours_abrevie"]; ?></td> <td><?php echo $effectif_dtss[$jj]; ?></td></tr>
<?php $jj++;} ?>
<tr> <td><b>TOTAL</b></td> <td><b><?php echo $nombre_dtss_total["nombre_dtss_total"]; ?></b></td></tr>
</table>

<p></p><p></p>
<h4>INGENIORAT</h4>
<table>
  <tr><th>Parcours</th> <th>Effectif</th></tr>

<?php $kk=1; while ($parcours_ing_liste=$requette6->fetch()) { ?>
  <tr><td><?php echo $parcours_ing_liste["parcours_abrevie"]; ?></td> <td><?php echo $effectif_ing[$kk]; ?></td></tr>
<?php $kk++;} ?>
<tr> <td><b>TOTAL</b></td> <td><b><?php echo $nombre_ing_total["nombre_ing_total"]; ?></b></td></tr>
</table>
</div>
</div>
<?php $total_gle=$nombre_dts_total['nombre_dts_total']+$nombre_dtss_total['nombre_dtss_total']+$nombre_ing_total['nombre_ing_total']; ?>

<p><b>TOTAL Gle : <?php echo $total_gle; ?></b></p>
<p>Fait à Antsiranana, le <?php echo date("d/m/Y");?></p>
  </body>
</html>