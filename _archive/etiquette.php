<?php
session_start();

if(!isset($_SESSION['id_utilisateur']) AND !isset($_SESSION['email_concours'])){header("Location:login.php");}

require "connect.php";
//$id=$_GET['impr'];
//$req = $bdd->prepare('UPDATE `candidats` SET `convoc`="OUI" WHERE `id_candidat`=?');
//$req_impr=$req->execute(array($id));
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <title>Etiquette_<?php if (isset($_GET['centre'])) {echo $_GET['centre']; } if (isset($_GET['id'])) {echo $_GET['id']; }  ?></title>
  </head>
  <body>
    <style>
    *{
      font-family: Arial Narrow;
      font-size: 12pt;
    }
      table, th, tr, td
      {
        /*border-collapse: collapse;
        border: 1px solid black;
        margin-right:auto;
        margin-left:auto;*/
        font-size:16pt;
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

    .photo{
    width: 150px;
    height: 150px;
    margin-top: -80px;
    margin-left: 526px;
    border: 2px solid;
    text-align: center;
    vertical-align: middle;
  }
  .convocation *{
    font-size: 15px;
    line-height: 1.2;
  }
  .convocation table tr td{
    height: 20px;
    border: 1px solid;
    padding-left: 20px;
    padding-right: 20px;
    vertical-align: middle;
  }
  .convocation table{
    border-collapse: collapse;
  }

        .signature
  {
    /*signature seulement*/
    margin-left: auto;
    text-align: center;
    background-image: url("img/dgs.png");
    background-repeat: no-repeat;
    background-size: 40%;
    background-position: center bottom;

    /*signature avec cachet*/
    /*margin-left: 150mm;
    text-align: center;
    background-image: url("img/dga.png");
    background-repeat: no-repeat;
    background-size: 30%;
    background-position: center bottom;*/
  }


  hr.new1 {
  border-top: 1px solid black;
}

/*saut de page*/
div.breakafter {
  page-break-after: always;
}
    </style>

<?php

$recup=$bdd->query('SELECT * FROM etiquette WHERE niveau="DTS" ORDER BY ecole, date_etiquette, horaire');

$date_conc=$bdd->prepare('SELECT * FROM date_concours WHERE Cycle=?');
$date_conc->execute(array("cycle1"));
$date_concours=$date_conc->fetch();


?>

<?php
while ($message=$recup->fetch()) {
?>

<div class="breakafter">
    <page size="A4" backtop="10mm" backleft="10mm" backright="10mm" backbottom="10mm">


    <div class="tete">
    <p style="margin-top:-15px;font-size: 9pt;">REPOBLIKAN’I MADAGASIKARA</p>
    <p style="margin-top:-15px;font-size: 9pt;">Fitiavana – Tanindrazana – Fandrosoana</p>
    <p style="margin-top:-15px;font-size: 9pt;">------oOo------</p>
    <p style="margin-top:-15px;font-size: 11pt;">MINISTERE DE L’ENSEIGNEMENT SUPERIEUR ET DE LA RECHERCHE SCIENTIFIQUE</p>
    <p style="margin-top:-15px;font-size: 9pt;">------oOo------</p>
  <div class="logo">
    <img src="img/logo_ist.png">
  </div>
    <h5 style="margin-top:-40px;color:blue;"><span style="color:red;">I</span>NSTITUT
      <span style="color:red;">S</span>UPERIEUR DE <span style="color:red;">T</span>ECHNOLOGIE <span style="color:red;">D</span>’ANTSIRANANA</h5>
    <table class="ruban">
      <tr class="ruban">
        <td class="ruban">B.P. 509 Antsiranana - 201</td>
        <td class="ruban">DIRECTION GENERALE</td>
        <td class="ruban">concours@ist-antsiranana.mg</td>
      </tr>
    </table>
  </div>
  <p></p>

<hr class="new1">
<i>
<h1 style="margin-left:400px; font-size:14pt;">CONCOURS D’ENTREE EN PREMIERE ANNEE <br/>
Session du <?php echo $date_concours["session"];?> <br/>
<?php echo $message['ecole']; ?> <br/>
Parcours : <?php echo $message['parcours']; ?></h1>
</i>

<hr class="new1">
<p></p>
<table style="margin-left:50px;">
<tr>
<td style="width:120px; height:40px; text-decoration: underline; text-decoration-color: black;">CENTRE :</td><td style="text-transform: uppercase;"><?php if (isset($_GET['centre'])) {echo $_GET['centre']; } else echo "ANTSIRANANA" ?></td>
<tr><td style="width:120px; height:40px; text-decoration: underline; text-decoration-color: black;">DATE :</td><td><?php echo $message['date_etiquette']; ?></td></tr>
<tr><td style="width:120px; height:40px; text-decoration: underline; text-decoration-color: black;">EPREUVE :</td><td><?php echo $message['epreuve']; ?></td></tr>
<tr><td style="width:120px; height:40px; text-decoration: underline; text-decoration-color: black;">HORAIRE :</td><td><?php echo $message['horaire']; ?></td></tr>
<tr><td style="width:120px; height:40px; text-decoration: underline; text-decoration-color: black;">DUREE :</td><td><?php echo $message['duree']; ?></td></tr>
<?php if (isset($_GET['id'])) { echo '<tr><td style="width:120px; height:40px; text-decoration: underline; text-decoration-color: black;">SALLE :</td><td>'. $_GET['id'].'</td></tr>'; }?>
<tr><td style="width:120px; height:40px; text-decoration: underline; text-decoration-color: black;">NOMBRE :</td><td>    </td></tr>
</tr>
</table>
</page>
</div>
<?php } ?>