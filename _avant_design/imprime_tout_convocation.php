<?php
session_start();

if (!isset($_SESSION['id_utilisateur']) && !isset($_SESSION['email_concours'])) {
    header("Location:login.php");
    exit();
}

require "connect.php";

// On récupère tous les candidats DTS (toutes écoles confondues)
$req = $bdd->prepare("
    SELECT can.*, cen.nom_centre AS centre, par.ecole_abrevie, par.ecole_complet
    FROM candidats can
    JOIN centre cen ON can.id_centre = cen.id_centre
    JOIN parcours par ON can.parcours1 = par.parcours_abrevie
    WHERE can.id_annee = :annee AND can.niveau = 'DTS'
    ORDER BY can.id_candidat
");
$req->execute([':annee' => $_SESSION["id_annee_selected"]]);
$candidats = $req->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Impression de toutes les convocations DTS</title>

<style>
  body { font-family: "Arial Narrow", Arial, sans-serif; margin:0; padding:0; }
  .no-print { text-align:center; margin:20px; }
  .btn { display:inline-block; padding:8px 12px; border-radius:4px; text-decoration:none; border:1px solid #666; background:#f5f5f5; color:#000; }
  .btn-primary { background:#0d6efd; color:#fff; border-color:#0d6efd; }
  .btn-secondary { background:#6c757d; color:#fff; border-color:#6c757d; }
  page { display:block; margin: 0 auto; padding: 10mm; width:210mm; background:#fff; box-sizing:border-box; }
  .page-break { page-break-before: always; }
  .convocation { font-size:14px; line-height:1.25; color:#000; }
  .convocation h3 { text-align:center; margin:18px 0; font-size:20px; }
  .convocation .header { text-align:center; margin-bottom:6px; }
  .convocation .header img.logo { width:65px; height:65px; display:block; margin:0 auto 4px; }
  .convocation .meta { margin:8px 0 14px; text-align:center; font-size:11px; color:#333; }
  .convocation ul { margin-left:20px; }

  /* === TABLEAU DES ÉPREUVES === */
  table.table-epreuve {
    width: 100%;
    border-collapse: collapse;
    margin-top: 5px;
    font-size: 13px;
    border: 1px solid black;
  }
  table.table-epreuve th, table.table-epreuve td {
    border: 1px solid black !important;
    padding: 6px 8px;
    text-align: center;
    vertical-align: middle;
  }
  table.table-epreuve th {
    background-color: #f0f0f0 !important;
    font-weight: bold;
    -webkit-print-color-adjust: exact;
  }

  /* === SIGNATURE IMAGE === */
  .signature-block {
    margin-top: 25px;
    text-align: center;
  }
  .signature-block img {
    display:block;
    width: 180px;  /* Taille réduite */
    height:auto;
    margin:5px auto 0;
  }
  .signature-block p {
    margin: 3px 0;
  }

  .photo {
    width: 120px;
    height: 120px;
    border: 2px solid #000;
    float: right;
    text-align: center;
    line-height: 120px;
    margin-left: 12px;
    margin-top: -12px;
  }

  @media print {
    body {
      -webkit-print-color-adjust: exact !important;
      color-adjust: exact !important;
    }
    .no-print { display:none; }
  }
</style>
</head>

<body>

<div class="no-print">
  <a href="convocation_dts.php" class="btn btn-secondary">⬅ Retour</a>
  <button onclick="window.print()" class="btn btn-primary">🖨️ Imprimer toutes les convocations</button>
</div>

<?php
$i = 0;
foreach ($candidats as $donnees):
    if ($i > 0) echo '<div class="page-break"></div>';

    // Mise à jour du statut imprimé
    $bdd->prepare("UPDATE candidats SET convoc=1 WHERE id_candidat=?")->execute([$donnees['id_candidat']]);

    // Informations sur le parcours
    $p = $donnees['parcours1'];
    $pa = $bdd->prepare("SELECT * FROM parcours WHERE parcours_abrevie=?");
    $pa->execute([$p]);
    $parcours = $pa->fetch();

    $p2 = $donnees['parcours2'];
    $pa2 = $bdd->prepare("SELECT * FROM parcours WHERE parcours_abrevie=?");
    $pa2->execute([$p2]);
    $parcours2 = $pa2->fetch();

    // Date concours
    $cycle = ($donnees['niveau']=="DTS") ? "cycle1" : "cycle2";
    $vtmp = $bdd->prepare("SELECT * FROM date_concours WHERE cycle=? ORDER BY id DESC LIMIT 1");
    $vtmp->execute([$cycle]);
    $dateConcours = $vtmp->fetch();
?>
<page size="A4">
  <div class="convocation">
        <div class="header">
          <img src="img/logo_ist.png" class="logo">
          <h5 style="color:blue;"><span style="color:red;">I</span>NSTITUT 
          <span style="color:red;">S</span>UPERIEUR DE <span style="color:red;">T</span>ECHNOLOGIE 
          <span style="color:red;">D</span>’ANTSIRANANA</h5>
          <p class="meta">B.P. 509 Antsiranana - 201 | DIRECTION GÉNÉRALE | www.istdiego.net</p>
        </div>

    <h3>CONVOCATION</h3>

    <div class="photo">Photo 4x4</div>

    <p>
      À <?php if ($donnees['sexe']=="M") echo "Monsieur"; elseif ($donnees['sexe']=="F") echo "Mademoiselle"; else echo "Mr/Mme/Mlle"; ?>
      <strong><?= htmlspecialchars($donnees['nom']." ".$donnees['prenom']); ?></strong><br><br>

      Vous êtes régulièrement 
      <?php if ($donnees['sexe']=="M") echo "inscrit"; elseif ($donnees['sexe']=="F") echo "inscrite"; else echo "inscrit(e)"; ?>
      au concours d'entrée 
      <?php
        if ($donnees['niveau']=='DTS') echo "en première année de formation des Techniciens Supérieurs";
        elseif ($donnees['niveau']=='DTSS') echo "en troisième année de formation des Techniciens Supérieurs Spécialisés";
        else echo "en formation d’ingénieurs niveau M1";
      ?>
      à l'Institut Supérieur de Technologie d'Antsiranana (IST-D) au titre de l’année académique <?= date("Y") . "-" . (date("Y") + 1); ?> 
      à l'<strong><?= $parcours['ecole_complet']." (".$parcours['ecole_abrevie'].")"; ?></strong>.
    </p>

    <ul>
      <li>Parcours 1er choix : <strong><?= $parcours['parcours_complet']." (".$donnees['parcours1'].")"; ?></strong></li>
      <li>Parcours 2e choix : <strong><?= $parcours2['parcours_complet']." (".$donnees['parcours2'].")"; ?></strong></li>
      <li>Centre d'examen : <strong><?= htmlspecialchars($donnees['centre']); ?></strong></li>
      <li>Numéro d'inscription : <strong><?= sprintf("%'03d", $donnees['numero'])."24".$donnees['parcours1']; ?></strong></li>
    </ul>

    <p>
      Les épreuves vont se dérouler le <strong><?= $dateConcours['Premier_Date'] ?? "Date non définie"; ?></strong> 
      et le <strong><?= ($dateConcours['Deuxiem_Date'] ?? "Date non définie")." ".date("Y"); ?></strong>.
    </p>

    <p>
      Les jours du concours, vous êtes prié(e) de vous munir de la présente convocation et d'une <strong>pièce d'identité</strong>.
      L'appel se fera une demi-heure avant le début de chaque épreuve.<br>
      <b>N.B. :</b> Les téléphones et calculatrices programmables sont strictement interdits dans la salle d'examen.
    </p>

    <strong style="text-decoration:underline;">Déroulement des épreuves</strong>

    <table class="table-epreuve">
      <thead>
        <tr>
          <th>Date</th>
          <th>Heures</th>
          <th>Épreuves</th>
          <th>Durée</th>
          <th>Coeff.</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>Jeudi 18 septembre</td><td>08h00 - 09h30</td><td>Français</td><td>1h30</td><td>1</td></tr>
        <tr><td>Jeudi 18 septembre</td><td>09h45 - 11h45</td><td>Mathématiques</td><td>2h00</td><td>2</td></tr>
        <tr><td>Jeudi 18 septembre</td><td>14h30 - 16h30</td><td>Dessin – Technologie</td><td>2h00</td><td>2</td></tr>
        <tr><td>Vendredi 19 septembre</td><td>08h00 - 09h30</td><td>Physiques</td><td>1h30</td><td>2</td></tr>
        <tr><td>Vendredi 19 septembre</td><td>09h45 - 10h45</td><td>Test Psychotechnique</td><td>1h00</td><td>1</td></tr>
      </tbody>
    </table>

    <div class="signature-block">
      <p><b>Fait à Antsiranana, le <?= date("d/m/Y"); ?></b></p>
      <p><b>Le Directeur Général,</b></p>
      <img src="img/dgs.png" alt="signature">
      <p><b>Dr. TSIMITAMBY Briand</b></p>
    </div>

  </div>
</page>
<?php
$i++;
endforeach;
?>

<script>
  // Impression automatique après chargement
  window.onload = function() {
    window.print();
  };
</script>

</body>
</html>
