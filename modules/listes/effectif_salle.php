<?php
ob_start();
session_start();
if(!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['email_concours'])){
    header("Location:../../login.php");
    exit;
}

require __DIR__ . '/../../config/connect.php';

// Les niveaux à afficher
$niveaux = ["DTS", "DTSS", "INGENIORAT"];

// Récupération des centres
$centres = $bdd->query("SELECT * FROM centre ORDER BY nom_centre")->fetchAll(PDO::FETCH_ASSOC);

// Centre actif (par défaut : le premier)
$centreActif = isset($_GET['centre']) ? $_GET['centre'] : ($centres[0]['id_centre'] ?? null);

// Récupération des salles du centre actif
$salles = $bdd->prepare("SELECT * FROM salle WHERE id_centre=? ORDER BY nom_salle");
$salles->execute([$centreActif]);
$salles = $salles->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Effectif par salle</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background: #f8f9fa; }
.header-liste { text-align: center; margin-bottom: 20px; }
.header-liste h2 { text-transform: uppercase; font-weight: bold; }
.table-effectif th, .table-effectif td {
    border: 1px solid #000 !important;
    vertical-align: middle !important;
    padding: 6px !important;
}
.page-break { page-break-before: always; }
@media print {
    #btnPrint, .nav-tabs, .card-header, .navbar, .main-sidebar {
        display: none !important;
    }
    body { background: white; margin: 0; }
}
</style>
</head>

<body>

<div class="container mt-4">

<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">Effectif par salle</h3>
    <button id="btnPrint" class="btn btn-sm btn-secondary" onclick="window.print();">
      <i class="fas fa-print"></i> Imprimer
    </button>
  </div>

  <div class="card-body p-3">

    <!-- Onglets des centres -->
    <ul class="nav nav-tabs" id="centreTabs" role="tablist">
      <?php foreach($centres as $index => $centre): ?>
        <li class="nav-item" role="presentation">
          <a class="nav-link <?php echo ($centre['id_centre'] == $centreActif) ? 'active' : ''; ?>"
             href="effectif_salle.php?centre=<?php echo $centre['id_centre']; ?>">
             <?php echo htmlspecialchars($centre['nom_centre']); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Onglets des niveaux -->
    <ul class="nav nav-tabs mt-3" id="niveauTabs" role="tablist">
      <?php foreach($niveaux as $index => $niveau): ?>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?php echo $index===0 ? 'active' : ''; ?>"
                  id="tab-<?php echo $niveau; ?>"
                  data-bs-toggle="tab"
                  data-bs-target="#content-<?php echo $niveau; ?>"
                  type="button" role="tab">
            <?php echo $niveau; ?>
          </button>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Contenu de chaque niveau -->
    <div class="tab-content mt-4">
      <?php foreach($niveaux as $index => $niveau): ?>
        <div class="tab-pane fade <?php echo $index===0 ? 'show active' : ''; ?>"
             id="content-<?php echo $niveau; ?>" role="tabpanel">

          <div class="header-liste">
            <h2>TABLEAU DES EFFECTIFS PAR SALLE</h2>
            <p>
              Centre :
              <strong>
                <?php
                  $nomCentre = $bdd->prepare("SELECT nom_centre FROM centre WHERE id_centre=?");
                  $nomCentre->execute([$centreActif]);
                  echo htmlspecialchars($nomCentre->fetchColumn());
                ?>
              </strong> |
              Niveau : <strong><?php echo htmlspecialchars($niveau); ?></strong> |
              Date : <strong><?php echo date("d/m/Y"); ?></strong>
            </p>
          </div>

          <?php if(empty($salles)): ?>
            <p class="text-center text-danger">Aucune salle trouvée pour ce centre.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-bordered table-effectif">
                <thead class="table-light text-center">
                  <tr>
                    <th style="width:5%">N°</th>
                    <th style="width:30%">Salle</th>
                    <th style="width:20%">Effectif total</th>
                    <th style="width:45%">Détail par parcours</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $totalGeneral = 0;
                  $i = 1;
                  foreach($salles as $salle):
                      // Total global par salle
                      $reqTotal = $bdd->prepare("
                        SELECT COUNT(*) FROM candidats
                        WHERE id_annee=? AND id_salle=? AND niveau=?
                      ");
                      $reqTotal->execute([$_SESSION["id_annee_selected"], $salle['id_salle'], $niveau]);
                      $total = $reqTotal->fetchColumn();

                      // Détail par parcours
                      $reqParcours = $bdd->prepare("
                        SELECT parcours1, COUNT(*) AS nb
                        FROM candidats
                        WHERE id_annee=? AND id_salle=? AND niveau=?
                        GROUP BY parcours1 ORDER BY parcours1
                      ");
                      $reqParcours->execute([$_SESSION["id_annee_selected"], $salle['id_salle'], $niveau]);
                      $details = $reqParcours->fetchAll(PDO::FETCH_ASSOC);

                      $totalGeneral += $total;
                  ?>
                  <tr>
                    <td class="text-center"><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($salle['nom_salle']); ?></td>
                    <td class="text-center fw-bold"><?php echo $total; ?></td>
                    <td>
                      <?php if(empty($details)): ?>
                        <em>Aucun candidat</em>
                      <?php else: ?>
                        <ul class="mb-0">
                          <?php foreach($details as $d): ?>
                            <li><?php echo htmlspecialchars($d['parcours1']) . " : " . $d['nb']; ?></li>
                          <?php endforeach; ?>
                        </ul>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                  <tr class="table-secondary fw-bold">
                    <td colspan="2" class="text-end">TOTAL GÉNÉRAL :</td>
                    <td class="text-center"><?php echo $totalGeneral; ?></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$content = ob_get_clean();
$menu = "";
$menu_salle = "nav-item menu-open";
$effectif_salle = "nav-link active";
require __DIR__ . '/../../includes/template.php';
?>
