<?php
session_start();
if(!isset($_SESSION['id_utilisateur']) AND !isset($_SESSION['email_concours'])){header("Location:login.php?erreur=erreur");}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <title>Liste des Candidats</title>
    <script src="excellentexport.js"></script>
  </head>
  <body>
    <style>
    *{
      font-family: Arial Narrow;
      font-size: 13pt;
    }
      table, th, tr, td
      {
        border-collapse: collapse;
        border: 1px solid black;
        margin-right:auto;
        margin-left:auto;
      }
    .tete{
        text-align: center;
    margin-top:2%;
    font-weight: normal;
    }
  .ruban{
    background: lightgrey;
    font-size: 10pt;
    border-collapse: none;
    border-color: lightgrey;
    margin-right:auto;
    margin-left:auto;
    margin-top: -20px
  }
  .ruban tr td{
    width: 220px;
    border-collapse: none;
  }
  .logo img{
    margin-top: -30px;
    margin-left: -500px;
    width: 65px;
    height: 65px;
  }
  .chiffre_en_lettre
  {
    text-align: center;
  }
  .signature
  {
    margin-left: 100mm;
  }
  .titre
  {
    text-align: center;
    margin-top: 30px;
    margin-bottom: -20px;
  }
  h5
  {
    margin-top: -20px;
  }
  img{
    width: 75px;
    height: 73px;
  }
    </style>
    <page size="A4" backtop="10mm" backleft="10mm" backright="10mm" backbottom="10mm">
 


<?php

require('connect.php');

$recup=$bdd->prepare('SELECT * FROM candidats WHERE niveau="INGENIORAT" AND parcours1=? ORDER BY id_candidat');
$recup->execute(array($_GET['id']));
?>
<table class="tableau" id="datatable">
  <tr>
      <th>Num inscription</th>
      <th>Nom et prénoms</th>
      <th>date et lieu de naissance</th>
      <th>sexe</th>
      <th>Adresse</th>
      <th>Téléphone</th>
      <th>Type candidat</th>
      <th>Diplôme</th>
      <th>Année</th>
      <th>centre concours</th>
      <th>Parcours1</th>
      <th>Parcours2</th>
  </tr>
<?php
$i=1;

$recup1=$bdd->prepare('SELECT * FROM candidats WHERE niveau="INGENIORAT" AND parcours1=? ORDER BY nom');
$recup1->execute(array($_GET['id']));
while($message=$recup1->fetch())

{
?>
  <tr>
       <td style="text-align:left"><?php echo sprintf("%'03d", $message['id_candidat'])."/22/".$message['ecole']."/".$message['parcours1'];?></td>
       <td style="width: 370px;"><?php echo $message['nom']." ".$message['prenom'];?></td>
       <td style="width: 370px;"><?php echo $message['date_naissance']." à ".$message['lieu_naissance'];?></td>
       <td style="text-align:center"><?php echo $message['sexe'];?></td>
       <td style="width: 370px;"><?php echo $message['adresse'];?></td>
       <td style="width: 370px;"><?php echo $message['telephone'];?></td>
       <td style="width: 370px;"><?php echo $message['type_candidat'];?></td>
       <td style="text-align:center"><?php echo $message['serie_bacc'];?></td>
       <td style="text-align:center;width: 180px;"><?php echo $message['annee_bacc'];?></td>
       <td style="text-align:center;width: 180px;"><?php echo $message['centre'];?></td>
       <td style="text-align:center;width: 180px;"><?php echo $message['parcours1'];?></td>
       <td style="text-align:center;width: 180px;"><?php echo $message['parcours2'];?></td

  </tr>
<?php
$i++;
}
?>
</table>
<br/>
  </page>
</body>
</html>