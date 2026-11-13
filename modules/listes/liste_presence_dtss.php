<?php
ob_start();
session_start();
if (!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['email_concours'])) {
    header("Location: ../../login.php");
    exit;
}

require __DIR__ . '/../../config/connect.php';

// === AJOUT D’UN CENTRE ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_centre') {
    $nomCentre = trim($_POST['nom_centre']);
    $id_annee = $_SESSION["id_annee_selected"] ?? 1; 
    if (!empty($nomCentre)) {
        $stmt = $bdd->prepare("INSERT INTO centre (nom_centre, id_annee) VALUES (?, ?)");
        $stmt->execute([$nomCentre, $id_annee]);
        header("Location: liste_presence_dtss.php");
        exit;
    }
}

// === AJOUT D’UN JURY ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_jury') {
    $id_salle = intval($_POST['id_salle']);
    $nomJury = trim($_POST['nom_jury']);
    $fonction = trim($_POST['fonction']);
    $id_annee = $_SESSION["id_annee_selected"] ?? 1;
    if (!empty($nomJury) && $id_salle > 0) {
        $stmt = $bdd->prepare("INSERT INTO jurys (id_salle, nom_jury, fonction, id_annee) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_salle, $nomJury, $fonction, $id_annee]);
        header("Location: liste_presence_dtss.php?centre=" . ($_GET['centre'] ?? ''));
        exit;
    }
}

// Récupération des centres disponibles
$centres = $bdd->query("SELECT * FROM centre ORDER BY nom_centre")->fetchAll(PDO::FETCH_ASSOC);
$centreActif = isset($_GET['centre']) ? $_GET['centre'] : ($centres[0]['id_centre'] ?? null);

// Niveaux
$niveaux = ['DTSS', 'INGENIORAT'];

// --- AJOUT D'UNE SALLE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_salle') {
    $nomSalle = trim($_POST['nom_salle']);
    $capacite = !empty($_POST['capacite']) ? intval($_POST['capacite']) : null;
    if (!empty($nomSalle)) {
        $stmt = $bdd->prepare("INSERT INTO salle (nom_salle, id_centre, capacite) VALUES (?, ?, ?)");
        $stmt->execute([$nomSalle, $centreActif, $capacite]);
        header("Location: liste_presence_dtss.php?centre=" . $centreActif);
        exit;
    }
}

// --- MODIFICATION DE CAPACITÉ ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'modifier_capacite') {
    $id_salle = intval($_POST['id_salle']);
    $capacite = intval($_POST['capacite']);
    $stmt = $bdd->prepare("UPDATE salle SET capacite=? WHERE id_salle=?");
    $stmt->execute([$capacite, $id_salle]);
    header("Location: liste_presence_dtss.php?centre=" . $centreActif);
    exit;
}

// --- SUPPRESSION D'UNE SALLE ---
if (isset($_GET['supprimer_salle'])) {
    $id_salle = intval($_GET['supprimer_salle']);
    $bdd->prepare("DELETE FROM salle WHERE id_salle = ?")->execute([$id_salle]);
    header("Location: liste_presence_dtss.php?centre=" . $centreActif);
    exit;
}

// --- Récupérer les salles du centre actif ---
$salles = $bdd->prepare("SELECT * FROM salle WHERE id_centre=? ORDER BY id_salle");
$salles->execute([$centreActif]);
$salles = $salles->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Fiche de présence — DTSS / INGÉNIORAT</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; }
.header-liste { text-align: center; margin-bottom: 20px; }
.header-liste h2 { text-transform: uppercase; font-weight: bold; }
.infos-salle { margin-bottom: 10px; font-size: 15px; }
.table-liste th, .table-liste td { border: 1px solid #000 !important; vertical-align: middle !important; padding: 5px !important; }
.signature { height: 35px; }
.page-break { page-break-before: always; }

/* Masquer les boutons à l'impression */
@media print {
  #btnPrint, #btnRepartir, #btnAddSalle, #btnDeleteSalle, #btnAddCentre, #btnAddJury, .nav-tabs, .card-header, form, .no-print {
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
    <h3 class="card-title">Fiche de présence — DTSS / INGÉNIORAT</h3>
    <div>
      <button class="btn btn-sm btn-success" id="btnAddCentre" data-bs-toggle="modal" data-bs-target="#addCentreModal"><i class="fas fa-school"></i> Ajouter un centre</button>
      <button class="btn btn-sm btn-success" id="btnAddSalle" data-bs-toggle="modal" data-bs-target="#addSalleModal"><i class="fas fa-plus"></i> Ajouter une salle</button>
      <button class="btn btn-sm btn-primary" id="btnAddJury" data-bs-toggle="modal" data-bs-target="#addJuryModal"><i class="fas fa-chalkboard-teacher"></i> Ajouter un jury</button>
      <button class="btn btn-sm btn-danger" id="btnDeleteSalle" data-bs-toggle="modal" data-bs-target="#deleteSalleModal"><i class="fas fa-trash-alt"></i> Supprimer une salle</button>
      <a href="#" id="btnRepartir" class="btn btn-sm btn-primary" onclick="return confirmRepartir();"><i class="fas fa-cogs"></i> Répartir automatiquement</a>
      <button id="btnPrint" class="btn btn-sm btn-secondary" onclick="window.print();"><i class="fas fa-print"></i> Imprimer</button>
    </div>
  </div>

  <div class="card-body p-3">
    <!-- Onglets des centres -->
    <ul class="nav nav-tabs" id="centreTabs" role="tablist">
      <?php foreach ($centres as $index => $centre): ?>
        <li class="nav-item" role="presentation">
          <a class="nav-link <?= ($centre['id_centre'] == $centreActif) ? 'active' : ''; ?>"
             href="liste_presence_dtss.php?centre=<?= $centre['id_centre']; ?>">
             <?= htmlspecialchars($centre['nom_centre']); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Onglets des niveaux -->
    <ul class="nav nav-tabs mt-3" id="niveauTabs" role="tablist">
      <?php foreach ($niveaux as $index => $niveau): ?>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= $index === 0 ? 'active' : ''; ?>"
                  id="tab-<?= $niveau; ?>"
                  data-bs-toggle="tab"
                  data-bs-target="#content-<?= $niveau; ?>"
                  type="button" role="tab"><?= $niveau; ?></button>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Contenu des niveaux -->
    <div class="tab-content mt-4">
      <?php foreach ($niveaux as $index => $niveau): ?>
        <div class="tab-pane fade <?= $index === 0 ? 'show active' : ''; ?>" id="content-<?= $niveau; ?>" role="tabpanel">
          <?php
          $nb_salles = count($salles);
          $i_salle = 0;
          foreach ($salles as $salle):
              $i_salle++;
              $req = $bdd->prepare("SELECT * FROM candidats WHERE id_annee=? AND id_salle=? AND niveau=? ORDER BY nom, prenom");
              $req->execute([$_SESSION["id_annee_selected"], $salle['id_salle'], $niveau]);
              $candidats = $req->fetchAll(PDO::FETCH_ASSOC);
          ?>
          <div class="header-liste">
            <h2>FICHE DE PRÉSENCE</h2>
            <p class="infos-salle">
              Centre : <strong><?php
                  $nomCentre = $bdd->prepare("SELECT nom_centre FROM centre WHERE id_centre=?");
                  $nomCentre->execute([$centreActif]);
                  echo htmlspecialchars($nomCentre->fetchColumn());
              ?></strong> |
              Niveau : <strong><?= $niveau; ?></strong> |
              Salle : <strong><?= htmlspecialchars($salle['nom_salle']); ?></strong> |
              Capacité :
              <strong><?= $salle['capacite'] ?? '—'; ?></strong> 
            </p>
          </div>

          <?php if (empty($candidats)): ?>
            <p class="text-center text-danger">Aucun candidat affecté à cette salle.</p>
          <?php else: ?>
            <table class="table table-bordered table-liste">
              <thead class="text-center">
                <tr>
                  <th style="width:5%">N°</th>
                  <th style="width:20%">Nom</th>
                  <th style="width:20%">Prénoms</th>
                  <th style="width:20%">Parcours</th>
                  <th style="width:35%">Signature</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1; foreach ($candidats as $cand): ?>
                  <tr>
                    <td class="text-center"><?= $i++; ?></td>
                    <td><?= htmlspecialchars($cand['nom']); ?></td>
                    <td><?= htmlspecialchars($cand['prenom']); ?></td>
                    <td><?= htmlspecialchars($cand['parcours1']); ?></td>
                    <td class="signature"></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <p><strong>Total :</strong> <?= count($candidats); ?> candidat(s)</p>
          <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
</div>

<!-- MODAL AJOUT JURY -->
<div class="modal fade" id="addJuryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Ajouter un jury</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action" value="ajouter_jury">
          <div class="mb-3">
            <label>Salle :</label>
            <select name="id_salle" class="form-control" required>
              <option value="">-- Sélectionnez une salle --</option>
              <?php foreach ($salles as $s): ?>
                <option value="<?= $s['id_salle']; ?>"><?= htmlspecialchars($s['nom_salle']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label>Nom du jury :</label>
            <input type="text" name="nom_jury" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Fonction :</label>
            <input type="text" name="fonction" class="form-control" placeholder="Président, Membre, Secrétaire...">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmRepartir() {
  const activeTab = document.querySelector('#niveauTabs button.nav-link.active');
  const niveau = activeTab.textContent.trim();
  const centreId = new URLSearchParams(window.location.search).get('centre');
  if(confirm('Répartir automatiquement les candidats sans salle pour ' + niveau + ' dans ce centre ?')) {
      window.location.href = 'repartir_salles.php?niveau=' + encodeURIComponent(niveau) + '&centre=' + centreId;
  }
  return false;
}
</script>
</body>
</html>

<?php
$content = ob_get_clean();
$menu = "";
$menu_presence = "nav-item menu-open";
$liste_presence = "nav-link active";
require __DIR__ . '/../../includes/template.php';
?>
