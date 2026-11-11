<?php
// (le même PHP que précédemment)
session_start();
if(!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['email_concours'])) {
    header("Location: login.php");
    exit;
}
require 'connect.php';

function getEffectifsParNiveau($bdd, $niveau) {
    $stmt = $bdd->prepare('
        SELECT c.ecole, c.parcours1, COUNT(*) AS total 
        FROM candidats c 
        WHERE c.niveau = ? 
        GROUP BY c.ecole, c.parcours1
        ORDER BY c.ecole, c.parcours1
    ');
    $stmt->execute([$niveau]);
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[$row['ecole']][$row['parcours1']] = $row['total'];
    }
    return $data;
}

$effectifsDTS = getEffectifsParNiveau($bdd, 'DTS');
$effectifsDTSS = getEffectifsParNiveau($bdd, 'DTSS');
$effectifsING = getEffectifsParNiveau($bdd, 'INGENIORAT');

function getTotalParNiveau($bdd, $niveau) {
    $stmt = $bdd->prepare('SELECT COUNT(*) AS total FROM candidats WHERE niveau = ?');
    $stmt->execute([$niveau]);
    $res = $stmt->fetch();
    return intval($res['total']);
}

$totalDTS = getTotalParNiveau($bdd, 'DTS');
$totalDTSS = getTotalParNiveau($bdd, 'DTSS');
$totalING = getTotalParNiveau($bdd, 'INGENIORAT');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Effectif Concours IST-D 2025-2026</title>
<style>
  body {
    font-family: "Trebuchet MS", sans-serif;
    font-size: 16px;
    margin: 0;
    padding: 15px;
    background: #fff;
    color: #000;
  }
  h4 {
    font-size: 1.4rem;
    text-align: center;
    margin-bottom: 1rem;
  }
  .container {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-bottom: 4rem; /* pour laisser place au footer fixe */
  }
  .box {
    flex: 1 1 300px;
    min-width: 280px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #000;
    font-size: 1rem;
  }
  th, td {
    border: 1px solid #000;
    padding: 8px 12px;
    text-align: center;
    vertical-align: middle;
  }
  th {
    background-color: #eee;
  }
  .ecole-cell {
    background-color: #d9edf7;
    font-weight: bold;
    text-align: left;
  }
  /* Pied de page fixé à droite en bas */
  .footer-fixed {
    position: fixed;
    bottom: 15px;
    right: 15px;
    font-size: 1rem;
    text-align: right;
    background: #fff;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 1px 1px 5px rgba(0,0,0,0.1);
    max-width: 300px;
  }

  /* Impression */
  @media print {
    @page {
      size: A4 landscape;
      margin: 15mm;
    }
    body {
      font-size: 12pt;
      margin: 0;
      padding: 0;
      line-height: 1.2;
      -webkit-print-color-adjust: exact;
      color-adjust: exact;
    }
    h4 {
      font-size: 14pt;
      margin-bottom: 12pt;
      page-break-after: avoid;
    }
    .container {
      display: flex !important;
      flex-wrap: nowrap !important;
      gap: 10pt;
      justify-content: space-between;
      margin-bottom: 2rem !important;
    }
    .box {
      flex: 1 1 32% !important;
      margin: 0 !important;
      page-break-inside: avoid !important;
    }
    table {
      font-size: 11pt !important;
      border: 1px solid #000 !important;
      border-collapse: collapse !important;
      width: 100% !important;
      page-break-inside: avoid !important;
    }
    th, td {
      border: 1px solid #000 !important;
      padding: 6px 8px !important;
      font-size: 11pt !important;
    }
    .footer-fixed {
      position: static !important;
      max-width: none !important;
      box-shadow: none !important;
      border: none !important;
      padding: 0 !important;
      text-align: right !important;
      margin-top: 1rem !important;
    }
  }
</style>
</head>
<body>

<h4>Institut Supérieur de Technologie d'Antsiranana</h4>
<h4>Effectif des candidats au concours 2025-2026</h4>

<div class="container">

  <!-- DTS -->
  <div class="box">
    <h4>DTS</h4>
    <table>
      <thead>
        <tr>
          <th>École</th>
          <th>Parcours</th>
          <th>Effectif</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($effectifsDTS as $ecole => $parcoursList): ?>
          <?php
          $rowspan = count($parcoursList);
          $first = true;
          ?>
          <?php foreach($parcoursList as $parcours => $effectif): ?>
          <tr>
            <?php if ($first): ?>
              <td class="ecole-cell" rowspan="<?= $rowspan ?>"><?= htmlspecialchars($ecole) ?></td>
            <?php $first = false; endif; ?>
            <td><?= htmlspecialchars($parcours) ?></td>
            <td><?= intval($effectif) ?></td>
          </tr>
          <?php endforeach; ?>
        <?php endforeach; ?>
        <tr>
          <td colspan="2"><strong>TOTAL</strong></td>
          <td><strong><?= $totalDTS ?></strong></td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- DTSS -->
  <div class="box">
    <h4>DTSS</h4>
    <table>
      <thead>
        <tr>
          <th>École</th>
          <th>Parcours</th>
          <th>Effectif</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($effectifsDTSS as $ecole => $parcoursList): ?>
          <?php
          $rowspan = count($parcoursList);
          $first = true;
          ?>
          <?php foreach($parcoursList as $parcours => $effectif): ?>
          <tr>
            <?php if ($first): ?>
              <td class="ecole-cell" rowspan="<?= $rowspan ?>"><?= htmlspecialchars($ecole) ?></td>
            <?php $first = false; endif; ?>
            <td><?= htmlspecialchars($parcours) ?></td>
            <td><?= intval($effectif) ?></td>
          </tr>
          <?php endforeach; ?>
        <?php endforeach; ?>
        <tr>
          <td colspan="2"><strong>TOTAL</strong></td>
          <td><strong><?= $totalDTSS ?></strong></td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- INGÉNIORAT -->
  <div class="box">
    <h4>Ingéniorat</h4>
    <table>
      <thead>
        <tr>
          <th>École</th>
          <th>Parcours</th>
          <th>Effectif</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($effectifsING as $ecole => $parcoursList): ?>
          <?php
          $rowspan = count($parcoursList);
          $first = true;
          ?>
          <?php foreach($parcoursList as $parcours => $effectif): ?>
          <tr>
            <?php if ($first): ?>
              <td class="ecole-cell" rowspan="<?= $rowspan ?>"><?= htmlspecialchars($ecole) ?></td>
            <?php $first = false; endif; ?>
            <td><?= htmlspecialchars($parcours) ?></td>
            <td><?= intval($effectif) ?></td>
          </tr>
          <?php endforeach; ?>
        <?php endforeach; ?>
        <tr>
          <td colspan="2"><strong>TOTAL</strong></td>
          <td><strong><?= $totalING ?></strong></td>
        </tr>
      </tbody>
    </table>
  </div>

</div>

<div class="footer-fixed">
  <div><strong>TOTAL Général : <?= $totalDTS + $totalDTSS + $totalING ?></strong></div>
  <div>Fait à Antsiranana, le <?= date("d/m/Y") ?></div>
</div>

</body>
</html>
