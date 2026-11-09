<?php
session_start();
if($_SESSION['groupe']!="admin" && $_SESSION['groupe']!="superadmin"){header("Location:login.php");}

require('connect.php');



//verification si toutes les notes sont enregistré, sinon redirection vers page erreur

$nbr_notes=$bdd->prepare('SELECT COUNT(*) AS nbr_notes_total FROM notes WHERE parcours_notes=?');
$nbr_notes->execute(array($_GET['id']));
$nbr_notes_total=$nbr_notes->fetch();

$nbr_candidats=$bdd->prepare('SELECT COUNT(*) AS nbr_candidats_total FROM candidats WHERE parcours1=?');
$nbr_candidats->execute(array($_GET['id']));
$nbr_candidats_total=$nbr_candidats->fetch();

$reste=$nbr_candidats_total['nbr_candidats_total']-$nbr_notes_total['nbr_notes_total'];

if($nbr_notes_total['nbr_notes_total']!=$nbr_candidats_total['nbr_candidats_total']  OR $nbr_candidats_total['nbr_candidats_total']==0){header("Location:msg_avert_saisi.php?msg=$reste");}



$recup=$bdd->prepare('SELECT * FROM candidats WHERE parcours1=? ORDER BY nom');
$recup->execute(array($_GET['id']));

$requette2=$bdd->prepare('SELECT * FROM parcours WHERE parcours_abrevie=?');
$requette2->execute(array($_GET['id']));
$requette_parcours=$requette2->fetch();

$requette_matiere=$bdd->prepare('SELECT * FROM matiere WHERE mention=? ORDER BY id_matiere');
$requette_matiere->execute(array($requette_parcours['mention_abrevie']));


$ii=1;
$pp=$_GET['id'];
$requette4=$bdd->prepare('SELECT * FROM candidats, notes WHERE `candidats`.`parcours1`=? AND `candidats`.`id_candidat`=`notes`.`id_notes` AND `notes`.`parcours_notes`=? ORDER BY `notes`.`moyenne` DESC');
$requette4->execute(array($pp, $pp));
while($notes1=$requette4->fetch())
{
$k[$ii]=$notes1['moyenne'];
$ii++;
}
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
      font-size: 11pt;
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
        <td class="ruban">www.istdiego.net</td>
      </tr>
    </table>
  </div>
  <p></p>
<p style="text-align:center;">
<b >
<span style="text-transform: uppercase;"><?php echo $requette_parcours["ecole_complet"]." (".$requette_parcours["ecole_abrevie"].")"; ?></span>
<br><br>
DELIBERATION DU CONCOURS D'ENTREE EN PREMIERE ANNEE 2024-2025
<br><br>
<?php echo "Parcours ".$requette_parcours["parcours_complet"]." (".$requette_parcours["parcours_abrevie"].")"; ?>
</b>
    <page size="A4" backtop="10mm" backleft="10mm" backright="10mm" backbottom="10mm">
 

<table class="tableau" id="datatable">
  <tr>
      <th>Rang</th>
      <th>Numéro d'inscription</th>
      <th>Nom et prénoms</th>
      <th>Centre d'examen</th>
      <th>Parcours1</th>
      <th>Parcours2</th>
      <th>Sexe</th>
      <th>Téléphone</th>
      <th>Série Bacc</th>
                      <?php
                      while ($matiere=$requette_matiere->fetch()) {
                      ?>
                      <th>
                          <?php echo $matiere['nom_matiere'];?>
                      </th>
                      <?php } ?>
                      <th style="">
                          Moyenne
                      </th>
  </tr>
<?php
$i=1;
$p=$_GET['id'];
$requette3=$bdd->prepare('SELECT * FROM candidats, notes WHERE `candidats`.`parcours1`=? AND `candidats`.`id_candidat`=`notes`.`id_notes` AND `notes`.`parcours_notes`=? ORDER BY `notes`.`moyenne` DESC');
$requette3->execute(array($p, $p));
while($notes=$requette3->fetch())
{
  $enregistre_moyenne[0]=0;
  $enregistre_moyenne[$i]=$notes['moyenne'];
?>
  <tr style="<?php if ($i<=30){echo "background:#C6E0B4;";}
                  if (isset($k[31])) {if ($k[30]==$k[31] AND $i==31){echo "background:#C6E0B4;";}}
                  if (isset($k[31])) {if ($k[30]==$k[32] AND $i==32){echo "background:#C6E0B4;";}}
                  if (isset($k[31])) {if ($k[30]==$k[33] AND $i==33){echo "background:#C6E0B4;";}}
                ?>">
      <td style="text-align:left; "><?php  
      if ($i==1) {echo $i."er";}
      if ($i>1 AND $notes['moyenne']!=$enregistre_moyenne[$i-1]) {echo $i."ème";}
      if($i>1 AND $notes['moyenne']==$enregistre_moyenne[$i-1] AND $notes['moyenne']!=$enregistre_moyenne[$i-2]) {
        $j=$i-1;
        if ($j==1){echo $j."erEX";} else {echo $j."èmeEX";}}
      if($i>1 AND $notes['moyenne']==$enregistre_moyenne[$i-1] AND $notes['moyenne']==$enregistre_moyenne[$i-2] AND $notes['moyenne']!=$enregistre_moyenne[$i-3]) {
        $j=$i-2;
        if ($j==1){echo $j."erEX";} else {echo $j."èmeEX";}}
      elseif($i>3 AND $notes['moyenne']==$enregistre_moyenne[$i-3]) {
        $j=$i-3;
        if ($j==1){echo $j."erEX";} else {echo $j."èmeEX";}}
        

    ?></td>
      <td style="text-align:center"><?php echo sprintf("%'03d", $notes['numero'])."24".$notes['parcours1'];?></td>
       <td style="width: auto;"><?php echo $notes['nom']." ".$notes['prenom'];?></td>
       <td style="text-align:center;"><?php echo $notes['centre'];?></td>
       <td style="text-align:center;"><?php echo $notes['parcours1'];?></td>
       <td style="text-align:center;"><?php echo $notes['parcours2'];?></td>
       <td style="text-align:center"><?php echo $notes['sexe'];?></td>
       <td style="width: auto;"><?php echo $notes['telephone'];?></td>
       <td style="text-align:center"><?php echo $notes['serie_bacc'];?></td>
<td class="project-state" style="<?php if ($notes['matiere1']==0){echo "background:red;";} ?>">
                        <?php echo $notes['matiere1']; ?>
                      </td>
                      <td class="project-state" style="text-align:right; <?php if ($notes['matiere2']==0){echo "background:red;";} ?>width: 20px;">
                        <?php echo $notes['matiere2']; ?>
                      </td>
                      <td class="project-state" style="text-align:right; <?php if ($notes['matiere3']==0){echo "background:red;";} ?>width: 20px">
                        <?php echo $notes['matiere3']; ?>
                      </td>
                      <td class="project-state" style="text-align:right; <?php if ($notes['matiere4']==0){echo "background:red;";} ?>width: 20px">
                        <?php echo $notes['matiere4']; ?>
                      </td>
                      <td class="project-state" style="text-align:right; <?php if ($notes['matiere5']==0){echo "background:red;";} ?>width: 20px">
                        <?php echo $notes['matiere5']; ?>
                      </td>
                      <td class="project-state" style="text-align:right; <?php if ($notes['moyenne']==0){echo "background:red;";}?>width: 20px">
                        <?php echo $notes['moyenne']; ?>
                      </td>
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