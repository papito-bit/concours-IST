<?php
require 'connect.php';

if (isset($_GET['id']))
{
  $salle=$_GET['id'];
  $recup=$bdd->prepare('SELECT * FROM candidats WHERE salle=? AND niveau="DTS" AND ecole=? ORDER BY parcours1');
  $recup->execute(array($salle, $_GET['ecole']));

  $recup1=$bdd->prepare('SELECT * FROM salle WHERE id_salle=?');
  $recup1->execute(array($salle));
  $message1=$recup1->fetch();
}

if (isset($_GET['centre']))
{
  $centre=$_GET['centre'];
  
  $recup=$bdd->prepare('SELECT * FROM candidats WHERE centre=? AND niveau="DTS" AND ecole=? ORDER BY parcours1');
  $recup->execute(array($centre, $_GET['ecole']));

  $recup1=$bdd->prepare('SELECT * FROM centre WHERE nom_centre=?');
  $recup1->execute(array($centre));
  $message1=$recup1->fetch();
}

$date_conc=$bdd->prepare('SELECT * FROM date_concours WHERE Cycle=?');
$date_conc->execute(array("cycle1"));
$date_concours=$date_conc->fetch();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <title>Fiche_de_presence_<?php if (isset($salle)) {echo $salle;} if (isset($centre)) {echo $centre;}  echo "_".$_GET['ecole'];?></title>
  </head>
  <body>
    <style>
    *{
      font-family: Arial Narrow;
      font-size: 12pt;
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
    </style>
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


<b><p>
  Fiche de présence au concours d'entrée en première année DTS 2022-2023
<br>Session du <?php echo $date_concours["Premier_Date"];?> et <?php echo $date_concours["Deuxiem_Date"];?> 2022
<br><?php if (isset($salle)) {echo "Salle : ".$salle;} if (isset($centre)) {echo "Centre : ".$centre;} ?>
</p>
<p style="text-align: center;">Date:_____________ Epreuve:____________________________ Heure:_______________</p>
</b>



  <table class="tableau">
  <tr>
      <th>Pos</th>
      <th>Numéro d'inscription</th>
      <th>Nom et Prénoms</th>
      <th>Mention</th>
      <th>Parcours</th>
      <th>Emargement</th>
  </tr>
<?php
$i=1;
while ($message=$recup->fetch()) {
  ?>
    <tr style="width: auto; height: 30px;">
      <td style="text-align: center;"><span><?php echo $i;?></span></td>
      <td style="text-align:left;"><?php echo sprintf("%'03d", $message['id_candidat'])."/22/".$message['ecole']."/".$message['parcours1'];?></td>
       <td style="width: auto;"><span><?php echo $message['nom']." ".$message['prenom'];?></span></td>
       <td style="width: auto;"><span><?php echo $message['mention'];?></span></td>
       <td style="text-align:auto;width: auto;"><?php echo $message['parcours1'];?></td>
       <td></td>
  </tr>
  <?php
  $i++;
  $sexe=$message['sexe'];
}
?>
</table>
<?php
    $i=$i-1;
    include('ChiffreEnLettre.php');
    $lettre=new ChiffreEnLettre();
    $convert=$lettre->Conversion($i);
    ?>
  </page>
</body>
<br>
  <div class="chiffre_en_lettre">
    <b>
    Arrêté la présente liste au nombre de :
    <span style="text-transform: uppercase;"><?php if($i==1 AND $sexe=="F"){echo " UNE (01) CANDIDATE";}elseif($i==1 AND $sexe=="M" ){echo " UN (01) CANDIDAT";}elseif($i>=2 AND $i<=9){echo $convert." (0$i) CANDIDATS";} elseif($i>=10){echo $convert." ($i) CANDIDATS";}?></span>
    </b>
  </div>
</html>