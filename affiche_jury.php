<?php
ob_start();
session_start();

if (!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['email_concours'])) {
  header("Location: login.php");
  exit;
}

require "connect.php";

// Année active
$id_annee = $_SESSION["id_annee_selected"] ?? 1;
$annees = $_SESSION['annees'] ?? [];
$annee_label = "Année inconnue";
foreach ($annees as $a) {
    if ($a['id_annee'] == $id_annee) {
        $annee_label = $a['libelle'];
        break;
    }
}

// === AJOUT JURY ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'ajouter_jury') {
    $nom = trim($_POST['nom_jury']);
    $fonction = trim($_POST['fonction']);
    $id_salle = intval($_POST['id_salle']);
    $niveau = trim($_POST['niveau']);
    $type_jury = trim($_POST['type_jury']);
    $capacite = !empty($_POST['capacite']) ? intval($_POST['capacite']) : null;

    if ($nom && $id_salle && $niveau) {
        $stmt = $bdd->prepare("INSERT INTO jurys (nom_jury, fonction, id_salle, id_annee, niveau, type_jury, capacite, actif)
                               VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->execute([$nom, $fonction, $id_salle, $id_annee, $niveau, $type_jury, $capacite]);
        header("Location: affiche_jury.php");
        exit;
    }
}

// === SUPPRESSION JURY ===
if (isset($_GET['supprimer_jury'])) {
    $id = intval($_GET['supprimer_jury']);
    $bdd->prepare("DELETE FROM jurys WHERE id_jury=?")->execute([$id]);
    header("Location: affiche_jury.php");
    exit;
}

// === MODIFICATION JURY ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'modifier_jury') {
    $id_jury = intval($_POST['id_jury']);
    $nom = trim($_POST['nom_jury']);
    $fonction = trim($_POST['fonction']);
    $capacite = intval($_POST['capacite']);
    $bdd->prepare("UPDATE jurys SET nom_jury=?, fonction=?, capacite=? WHERE id_jury=?")
        ->execute([$nom, $fonction, $capacite, $id_jury]);
    header("Location: affiche_jury.php");
    exit;
}

// === RÉCUPÉRATION DES SALLES ===
$salles = $bdd->query("SELECT * FROM salle ORDER BY nom_salle")->fetchAll(PDO::FETCH_ASSOC);

// === RÉCUPÉRATION DES JURYS ===
$jurys = $bdd->query("SELECT j.*, s.nom_salle 
                      FROM jurys j 
                      LEFT JOIN salle s ON j.id_salle = s.id_salle 
                      WHERE j.id_annee = $id_annee 
                      ORDER BY j.niveau, j.type_jury, j.nom_jury")->fetchAll(PDO::FETCH_ASSOC);

// Groupement par catégorie
$categories = [
  'DTS' => [],
  'DTSS Écrit' => [],
  'DTSS Oral' => [],
  'INGENIORAT Écrit' => [],
  'INGENIORAT Oral' => []
];

foreach ($jurys as $jury) {
  if ($jury['niveau'] === 'DTS') {
      $categories['DTS'][] = $jury;
  } elseif ($jury['niveau'] === 'DTSS' && $jury['type_jury'] === 'Écrit') {
      $categories['DTSS Écrit'][] = $jury;
  } elseif ($jury['niveau'] === 'DTSS' && $jury['type_jury'] === 'Oral') {
      $categories['DTSS Oral'][] = $jury;
  } elseif ($jury['niveau'] === 'INGENIORAT' && $jury['type_jury'] === 'Écrit') {
      $categories['INGENIORAT Écrit'][] = $jury;
  } elseif ($jury['niveau'] === 'INGENIORAT' && $jury['type_jury'] === 'Oral') {
      $categories['INGENIORAT Oral'][] = $jury;
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion des Jurys</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
@media print {
  .no-print { display: none !important; }
  body { background: white; margin: 0; font-size: 14px; }
  .print-header { text-align: center; margin-bottom: 15px; }
  .print-header h2 { font-size: 20px; margin: 0; }
  .print-header h3 { font-size: 16px; margin: 0; font-weight: normal; }
}
</style>
</head>

<body>
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">Gestion des jurys</h3>
      <div>
        <button class="btn btn-sm btn-success no-print" data-bs-toggle="modal" data-bs-target="#addJuryModal"><i class="fas fa-plus"></i> Ajouter un jury</button>
        <button class="btn btn-sm btn-secondary no-print" onclick="imprimerSection()"><i class="fas fa-print"></i> Imprimer</button>
      </div>
    </div>

    <div class="card-body">
      <!-- Onglets -->
      <ul class="nav nav-tabs" id="juryTabs" role="tablist">
        <?php $first = true; foreach ($categories as $cat => $list): ?>
          <li class="nav-item" role="presentation">
            <button class="nav-link <?= $first ? 'active' : ''; ?>" data-bs-toggle="tab"
                    data-bs-target="#tab-<?= md5($cat); ?>" type="button" role="tab">
              <?= htmlspecialchars($cat); ?>
            </button>
          </li>
        <?php $first = false; endforeach; ?>
      </ul>

      <!-- Contenu des onglets -->
      <div class="tab-content mt-3">
        <?php $first = true; foreach ($categories as $cat => $list): ?>
          <div class="tab-pane fade <?= $first ? 'show active' : ''; ?>" id="tab-<?= md5($cat); ?>" role="tabpanel">
            <div class="print-section">
              <div class="print-header">
                <h2>IST Antsiranana</h2>
                <h3>Année scolaire : <?= htmlspecialchars($annee_label); ?></h3>
                <h4 class="mt-2">LISTE DES JURYS - <?= strtoupper(htmlspecialchars($cat)); ?></h4>
              </div>

              <?php if (empty($list)): ?>
                <p class="text-center text-muted">Aucun jury pour cette catégorie.</p>
              <?php else: ?>
                <table class="table table-bordered table-striped align-middle">
                  <thead class="table-dark text-center">
                    <tr>
                      <th>Nom</th>
                      <th>Fonction</th>
                      <th>Salle</th>
                      <th>Capacité</th>
                      <th class="no-print">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($list as $jury): ?>
                      <tr>
                        <td><?= htmlspecialchars($jury['nom_jury']); ?></td>
                        <td><?= htmlspecialchars($jury['fonction']); ?></td>
                        <td><?= htmlspecialchars($jury['nom_salle']); ?></td>
                        <td><?= htmlspecialchars($jury['capacite'] ?? '—'); ?></td>
                        <td class="text-center no-print">
                          <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                  data-bs-target="#editJuryModal<?= $jury['id_jury']; ?>"><i class="fas fa-pen"></i> Modifier</button>
                          <a href="?supprimer_jury=<?= $jury['id_jury']; ?>"
                             class="btn btn-sm btn-danger"
                             onclick="return confirm('Confirmez-vous la suppression de ce jury ?');"><i class="fas fa-trash"></i> Supprimer</a>
                        </td>
                      </tr>

                      <!-- MODAL MODIFIER -->
                      <div class="modal fade" id="editJuryModal<?= $jury['id_jury']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <form method="post">
                              <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title">Modifier jury</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body">
                                <input type="hidden" name="action" value="modifier_jury">
                                <input type="hidden" name="id_jury" value="<?= $jury['id_jury']; ?>">
                                <div class="mb-3">
                                  <label>Nom :</label>
                                  <input type="text" name="nom_jury" class="form-control" value="<?= htmlspecialchars($jury['nom_jury']); ?>" required>
                                </div>
                                <div class="mb-3">
                                  <label>Fonction :</label>
                                  <input type="text" name="fonction" class="form-control" value="<?= htmlspecialchars($jury['fonction']); ?>">
                                </div>
                                <div class="mb-3">
                                  <label>Capacité :</label>
                                  <input type="number" name="capacite" class="form-control" min="1" value="<?= htmlspecialchars($jury['capacite'] ?? ''); ?>">
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-warning">Enregistrer</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              <?php endif; ?>
            </div>
          </div>
        <?php $first = false; endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- MODAL AJOUT JURY -->
<div class="modal fade" id="addJuryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Ajouter un jury</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action" value="ajouter_jury">

          <div class="mb-3">
            <label>Nom du jury :</label>
            <input type="text" name="nom_jury" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Fonction :</label>
            <input type="text" name="fonction" class="form-control">
          </div>
          <div class="mb-3">
            <label>Salle :</label>
            <select name="id_salle" class="form-control" required>
              <option value="">-- Sélectionner une salle --</option>
              <?php foreach ($salles as $s): ?>
                <option value="<?= $s['id_salle']; ?>"><?= htmlspecialchars($s['nom_salle']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label>Niveau :</label>
            <select name="niveau" class="form-control" required>
              <option value="">-- Choisir --</option>
              <option value="DTS">DTS</option>
              <option value="DTSS">DTSS</option>
              <option value="INGENIORAT">INGENIORAT</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Type de jury :</label>
            <select name="type_jury" class="form-control">
              <option value="">-- Non spécifié --</option>
              <option value="Écrit">Écrit</option>
              <option value="Oral">Oral</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Capacité :</label>
            <input type="number" name="capacite" class="form-control" min="1">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-success">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function imprimerSection() {
  const activeTab = document.querySelector('.tab-pane.active .print-section');
  if (!activeTab) return alert("Aucun contenu à imprimer.");
  const original = document.body.innerHTML;
  document.body.innerHTML = activeTab.innerHTML;
  window.print();
  document.body.innerHTML = original;
  location.reload();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$content = ob_get_clean();
$menu = "Afficher les jurys";
require('template.php');
?>
