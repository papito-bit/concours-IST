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

if($nbr_notes_total['nbr_notes_total']!=$nbr_candidats_total['nbr_candidats_total'] OR $nbr_candidats_total['nbr_candidats_total']==0){header("Location:msg_avert_saisi.php?msg=$reste");}




$recup=$bdd->prepare('SELECT * FROM candidats WHERE parcours1=? ORDER BY nom');
$recup->execute(array($_GET['id']));

$requette2=$bdd->prepare('SELECT * FROM parcours WHERE parcours_abrevie=?');
$requette2->execute(array($_GET['id']));
$requette_parcours=$requette2->fetch();

$requette_matiere=$bdd->prepare('SELECT * FROM matiere WHERE mention=? ORDER BY id_matiere');
$requette_matiere->execute(array($requette_parcours['mention_abrevie']));


//verifier si les notes sont deja verrouiller
if ($requette_parcours['verrou_notes']=="NON") {$m=-1; header("Location:msg_avert_saisi.php?msg=$m");}



$ii=1;
$pp=$_GET['id'];
$requette4=$bdd->prepare('SELECT * FROM candidats, notes WHERE `candidats`.`parcours1`=? AND `candidats`.`id_candidat`=`notes`.`id_notes` AND `notes`.`parcours_notes`=? ORDER BY `notes`.`moyenne` DESC LIMIT 0,40');
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
      table, tr
      {
        border-collapse: collapse;
        border: 1px solid black;
        border-left: 0px solid black;
        border-right: 0px solid black;
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
<p style="margin-left:50px";>
<b >
Resultat du concours d'entrée en prémière année 2024-2025
<br>
<span style="text-transform: uppercase;"><?php echo $requette_parcours["ecole_complet"]." (".$requette_parcours["ecole_abrevie"].")"; ?></span>
<br>
<?php echo "Parcours ".$requette_parcours["parcours_complet"]." (".$requette_parcours["parcours_abrevie"].")"; ?>
</b>
<p>
    <page size="A4" backtop="10mm" backleft="10mm" backright="10mm" backbottom="10mm">
 



<table class="tableau" id="datatable">
  <tr>
      <th style="text-align:left;width: 60px;">Rang</th>
      <th style="text-align:left;width: 125px;">Numéro d'inscription</th>
      <th style="width: 290px; text-align:left">Nom et prénoms</th>
      <th style="text-align:left; width: 120px;">Centre d'examen</th>
  </tr>
<?php
$i=1;
$p=$_GET['id'];

if (isset($k[31])) {
if ($k[30]!=$k[31])
{
  $requette3=$bdd->prepare('SELECT * FROM candidats, notes WHERE `candidats`.`parcours1`=? AND `candidats`.`id_candidat`=`notes`.`id_notes` AND `notes`.`parcours_notes`=? ORDER BY `notes`.`moyenne` DESC LIMIT 0,30');
$requette3->execute(array($p, $p));
}} else
{  $requette3=$bdd->prepare('SELECT * FROM candidats, notes WHERE `candidats`.`parcours1`=? AND `candidats`.`id_candidat`=`notes`.`id_notes` AND `notes`.`parcours_notes`=? ORDER BY `notes`.`moyenne` DESC LIMIT 0,30');
$requette3->execute(array($p, $p));
}


if (isset($k[31])) {
if ($k[30]==$k[31])
{
  $requette3=$bdd->prepare('SELECT * FROM candidats, notes WHERE `candidats`.`parcours1`=? AND `candidats`.`id_candidat`=`notes`.`id_notes` AND `notes`.`parcours_notes`=? ORDER BY `notes`.`moyenne` DESC LIMIT 0,31');
$requette3->execute(array($p, $p));
}}


if (isset($k[32])) {if ($k[30]==$k[32])
{
  $requette3=$bdd->prepare('SELECT * FROM candidats, notes WHERE `candidats`.`parcours1`=? AND `candidats`.`id_candidat`=`notes`.`id_notes` AND `notes`.`parcours_notes`=? ORDER BY `notes`.`moyenne` DESC LIMIT 0,32');
$requette3->execute(array($p, $p));
}}


if (isset($k[33])) {if ($k[30]==$k[33])
{
  $requette3=$bdd->prepare('SELECT * FROM candidats, notes WHERE `candidats`.`parcours1`=? AND `candidats`.`id_candidat`=`notes`.`id_notes` AND `notes`.`parcours_notes`=? ORDER BY `notes`.`moyenne` DESC LIMIT 0,33');
$requette3->execute(array($p, $p));
}}

while($notes=$requette3->fetch())
{
  $enregistre_moyenne[0]=0;
  $enregistre_moyenne[$i]=$notes['moyenne'];
?>
  <tr>
      <td><?php  
      if ($i==1) {echo $i."er";}
      if ($i>1 AND $notes['moyenne']!=$enregistre_moyenne[$i-1]) {echo $i."ème";}
      if($i>1 AND $notes['moyenne']==$enregistre_moyenne[$i-1] AND $notes['moyenne']!=$enregistre_moyenne[$i-2]) {
        $j=$i-1;
        if ($j==1){echo $j."er ex";} else {echo $j."ème ex";}}
      if($i>1 AND $notes['moyenne']==$enregistre_moyenne[$i-1] AND $notes['moyenne']==$enregistre_moyenne[$i-2] AND $notes['moyenne']!=$enregistre_moyenne[$i-3]) {
        $j=$i-2;
        if ($j==1){echo $j."er ex";} else {echo $j."ème ex";}}
      elseif($i>3 AND $notes['moyenne']==$enregistre_moyenne[$i-3]) {
        $j=$i-3;
        if ($j==1){echo $j."er ex";} else {echo $j."ème ex";}}
        

    ?></td>
      <td ><?php echo sprintf("%'03d", $notes['numero'])."24".$notes['parcours1'];?></td>
       <td ><?php echo $notes['nom']." ".$notes['prenom'];?></td>
       <td ><?php echo $notes['centre'];?></td>
  </tr>
<?php
$k[$i]=$notes['moyenne'];
$i++;
$sexe=$notes['sexe'];
}


    $i=$i-1;
    include('ChiffreEnLettre.php');
    $lettre=new ChiffreEnLettre();
    $convert=$lettre->Conversion($i);
    $candidat=0;

?>
</table>
<br/>
  <div class="chiffre_en_lettre">
    
    Arrêtée la liste des admis(e)s au Parcours <?php echo $p; ?> au total de
    <span style="text-transform: uppercase;"><b><?php if($i==1 AND $sexe=="F"){echo " une (01) ";$candidat="candidate.";}elseif($i==1 AND $sexe=="M" ){echo " un (01) ";$candidat="candidat.";}elseif($i>=2 AND $i<=9){echo $convert." (0$i) ";$candidat="candidat(e)s.";} elseif($i>=10){echo $convert." ($i) ";$candidat="candidat(e)s.";}?></b></span><?php echo $candidat;?>
    
  </div>
  </page>
</body>
<div class="signature">
<p style="text-align:center;">Fait à Antsiranana, le <?php echo date("d/m/Y");?></p>
<p style="text-align:center;">Le président du jury,</p>
<br>
<p style="text-align:center;">Dr. TSIMITAMBY Briand</p>
<br>
</div>
</html>