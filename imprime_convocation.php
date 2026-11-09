<?php
session_start();

if(!isset($_SESSION['id_utilisateur']) OR !isset($_SESSION['email_concours'])){
    header("Location:login.php");
    exit;
}

require "connect.php";

$id = $_GET['impr'];

// Marquer comme "imprimé"
$req = $bdd->prepare('UPDATE candidats SET convoc = 1 WHERE id_candidat = ?');
$req->execute([$id]);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Convocation</title>
</head>

<body>
<style>
*{
  font-family: Arial Narrow;
  font-size: 12pt;
}
table, th, tr, td {
  border-collapse: collapse;
  border: 1px solid black;
  margin-right:auto;
  margin-left:auto;
}
.tete{
  text-align: center;
  margin-top:2%;
}
.ruban{
  background: lightgrey;
  font-size: 10pt;
  margin-right:auto;
  margin-left:auto;
  margin-top: -20px
}
.ruban tr td{
  width: 220px;
}
.logo img{
  margin-top: -30px;
  margin-left: -500px;
  width: 65px;
  height: 65px;
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
  padding-left: 20px;
  padding-right: 20px;
  vertical-align: middle;
}
.signature{
  margin-left: auto;
  text-align: center;
  background-image: url("img/dgs.png");
  background-repeat: no-repeat;
  background-size: 40%;
  background-position: center bottom;
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

    <h5 style="margin-top:-40px;color:blue;">
        <span style="color:red;">I</span>NSTITUT
        <span style="color:red;">S</span>UPERIEUR DE <span style="color:red;">T</span>ECHNOLOGIE 
        <span style="color:red;">D</span>’ANTSIRANANA
    </h5>

    <table class="ruban">
        <tr>
            <td>B.P. 509 Antsiranana - 201</td>
            <td>DIRECTION GENERALE</td>
            <td>www.istdiego.net</td>
        </tr>
    </table>
</div>

<?php
// ----------- Chargement des données du candidat
$recup = $bdd->prepare("
    SELECT can.*, cen.nom_centre as centre 
    FROM candidats can 
    JOIN centre cen ON can.id_centre = cen.id_centre 
    WHERE can.id_candidat = ? 
      AND can.id_annee = ?
");
$recup->execute([$id, $_SESSION["id_annee_selected"]]);
$donnees = $recup->fetch();

// ----------- Détection automatique du cycle
$cycle = ($donnees['niveau'] == "DTS") ? "cycle1" : "cycle2";

// ----------- Récupération année académique sélectionnée
$reqAn = $bdd->prepare("SELECT libelle FROM annees WHERE id_annee=?");
$reqAn->execute([$_SESSION["id_annee_selected"]]);
$anneeSelected = $reqAn->fetch();

// ----------- Parcours 1
$pa = $bdd->prepare("SELECT * FROM parcours WHERE parcours_abrevie=?");
$pa->execute([$donnees['parcours1']]);
$parcours = $pa->fetch();

// ----------- Parcours 2
$pa2 = $bdd->prepare("SELECT * FROM parcours WHERE parcours_abrevie=?");
$pa2->execute([$donnees['parcours2']]);
$parcours2 = $pa2->fetch();

// ----------- Récupération des dates du concours (cycle + année)
$reqDate = $bdd->prepare("
    SELECT Premier_Date, Deuxiem_Date 
    FROM date_concours 
    WHERE cycle=? AND id_annee=? 
    ORDER BY id DESC LIMIT 1
");
$reqDate->execute([$cycle, $_SESSION["id_annee_selected"]]);
$dateConcours = $reqDate->fetch();

// ----------- Sécurisation si aucune date encore enregistrée
if (!$dateConcours) {
    $dateConcours = [
        "Premier_Date" => "Date non définie",
        "Deuxiem_Date" => "Date non définie"
    ];
}

// ----------- Salle
$sa = $bdd->prepare("SELECT * FROM salle WHERE id_salle=?");
$sa->execute([$donnees['id_salle']]);
$salle = $sa->fetch();
?>

<div class="convocation">

<h4 style="margin-top:40px;text-align:center;font-size:20px;">CONVOCATION</h4>

<div class="photo">Photo 4x4</div>

<div><br>
A 
<?php 
echo ($donnees['sexe']=="M" ? "Monsieur" : ($donnees['sexe']=="F" ? "Mademoiselle" : "Mr/Mme/Mlle"));
?>
<strong><?= $donnees['nom']." ".$donnees['prenom']; ?></strong>

<br><br>
Vous êtes régulièrement 
<?= ($donnees['sexe']=="F" ? "inscrite" : "inscrit"); ?>
au concours d'entrée 

<?php
if ($donnees['niveau']=='DTS') echo " en première année de formation des Techniciens Supérieurs ";
if ($donnees['niveau']=='DTSS') echo " en troisième année de formation des Techniciens Supérieurs Spécialisés ";
if ($donnees['niveau']=='INGENIORAT') echo " en formation d’ingénieurs niveau M1 ";
?>

à l'Institut Supérieur de Technologie d'Antsiranana (IST-D) au titre de l’année académique 
<strong><?= $anneeSelected["libelle"]; ?></strong>
à l'<strong><?= $parcours['ecole_complet']." (".$parcours['ecole_abrevie'].")"; ?></strong>.

<ul>
    <li>Parcours 1er choix : <strong><?= $parcours['parcours_complet']." (".$donnees['parcours1'].")"; ?></strong></li>
    <li>Parcours 2e choix : <strong><?= $parcours2['parcours_complet']." (".$donnees['parcours2'].")"; ?></strong></li>
    <li>Centre d'examen : <strong><?= $donnees['centre']; ?></strong></li>
    <li>Numéro d'inscription : 
        <strong><?= sprintf("%'03d", $donnees['numero'])."24".$donnees['parcours1']; ?></strong>
    </li>
</ul>

<p>
Les épreuves vont se dérouler le 
<strong><?= $dateConcours['Premier_Date']; ?></strong> 
et le 
<strong><?= $dateConcours['Deuxiem_Date']; ?></strong>.
</p>

<p>
Les jours du concours, vous êtes 
<?= ($donnees['sexe']=="F" ? "priée" : "prié"); ?>
de vous munir de la présente convocation et d'une <strong>pièce d'identité.</strong><br>
L'appel des candidats se fera une demi-heure avant le début de chaque épreuve.<br>
Il est strictement interdit de sortir des salles d’examen durant la première heure.<br>
<b>N.B. :</b> Les téléphones et calculatrices programmables sont strictement interdits.
</p>

<strong style="text-decoration:underline;margin-top:15px;">Déroulement des épreuves</strong><br>

<?php
// ----------- Inclusions EDT selon spécialité
if ($donnees['niveau']=='DTS') {

    if ($donnees['ecole']=='EGMCS') include 'edt_egmcs.php';

    if (in_array($parcours['mention_abrevie'], ['ME','TC','GM'])) include 'edt_me_tc_mines.php';

    if ($parcours['mention_abrevie']=='AGRO') include 'edt_agro.php';

    if (in_array($parcours['mention_abrevie'], ['GC','GN'])) include 'edt_gc_gn.php';

    if ($parcours['mention_abrevie']=='MEB') include 'edt_meb.php';
}
else {
    include 'edt_second_cycle.php';
}
?>

<br>
<div style='font-size:9px;'>
Pour tous renseignement complémentaires, contacter 
<strong>032 03 100 55</strong> / 
<strong>032 03 456 46</strong> / 
<strong>032 04 859 21</strong> / 
<strong>032 03 100 50</strong> / 
<strong>032 57 532 76</strong>.
</div>

</div>
</page>

<script>
window.onload = function() {
    window.print();
    window.onafterprint = function() {
        window.location.href = "convocation_a_imprimer.php";
    };
};
</script>

<div class="signature">
<p><b>Fait à Antsiranana, le <?= date("d/m/Y"); ?></b></p>
<p><b>Le Directeur Général,</b></p>
<br><br>
<p><b>Dr. TSIMITAMBY Briand</b></p>
</div>

</body>
</html>
