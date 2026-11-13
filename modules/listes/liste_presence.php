<?php
ob_start();
session_start();
if(!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['email_concours'])){
    header("Location: ../../login.php");
    exit;
}

require __DIR__ . '/../../config/connect.php';

// Niveau fixe : DTS
$niveau = "DTS";

// === AJOUT D’UN CENTRE ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_centre') {
    $nomCentre = trim($_POST['nom_centre']);
    $id_annee = $_SESSION["id_annee_selected"] ?? 1; // lie à l'année active

    if (!empty($nomCentre)) {
        $stmt = $bdd->prepare("INSERT INTO centre (nom_centre, id_annee) VALUES (?, ?)");
        $stmt->execute([$nomCentre, $id_annee]);
        header("Location: liste_presence.php");
        exit;
    }
}

// === RÉCUPÉRATION DES CENTRES ===
$centres = $bdd->query("SELECT * FROM centre ORDER BY nom_centre")->fetchAll(PDO::FETCH_ASSOC);
$centreActif = isset($_GET['centre']) ? $_GET['centre'] : ($centres[0]['id_centre'] ?? null);

// === AJOUT D’UNE SALLE ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_salle') {
    $nomSalle = trim($_POST['nom_salle']);
    $capacite = !empty($_POST['capacite']) ? intval($_POST['capacite']) : null;

    if (!empty($nomSalle)) {
        $stmt = $bdd->prepare("INSERT INTO salle (nom_salle, id_centre, capacite) VALUES (?, ?, ?)");
        $stmt->execute([$nomSalle, $centreActif, $capacite]);
        header("Location: liste_presence.php?centre=" . $centreActif);
        exit;
    }
}

// === MODIFICATION DE CAPACITÉ ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'modifier_capacite') {
    $id_salle = intval($_POST['id_salle']);
    $capacite = intval($_POST['capacite']);
    $stmt = $bdd->prepare("UPDATE salle SET capacite=? WHERE id_salle=?");
    $stmt->execute([$capacite, $id_salle]);
    header("Location: liste_presence.php?centre=" . $centreActif);
    exit;
}

// === SUPPRESSION D’UNE SALLE ===
if (isset($_GET['supprimer_salle'])) {
    $id_salle = intval($_GET['supprimer_salle']);
    $bdd->prepare("DELETE FROM salle WHERE id_salle = ?")->execute([$id_salle]);
    header("Location: liste_presence.php?centre=" . $centreActif);
    exit;
}

// === RÉCUPÉRATION DES SALLES ===
$salles = [];
if ($centreActif) {
    $stmt = $bdd->prepare("SELECT * FROM salle WHERE id_centre=? ORDER BY id_salle");
    $stmt->execute([$centreActif]);
    $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Fiche de présence — DTS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; }
.header-liste { text-align: center; margin-bottom: 20px; }
.header-liste h2 { text-transform: uppercase; font-weight: bold; }
.infos-salle { margin-bottom: 10px; font-size: 15px; }
.table-liste th, .table-liste td { border: 1px solid #000 !important; vertical-align: middle !important; padding: 5px !important; }
.signature { height: 35px; }
.page-break { page-break-before: always; }

/* Masquer certains éléments à l'impression */
@media print {
  #btnPrint, #btnRepartir, #btnAddSalle, #btnAddCentre, #btnDeleteSalle, .nav-tabs, .card-header, form, .no-print {
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
      <h3 class="card-title">Fiche de présence — DTS</h3>
      <div>
        <button class="btn btn-sm btn-success" id="btnAddCentre" data-bs-toggle="modal" data-bs-target="#addCentreModal"><i class="fas fa-school"></i> Ajouter un centre</button>
        <button class="btn btn-sm btn-success" id="btnAddSalle" data-bs-toggle="modal" data-bs-target="#addSalleModal"><i class="fas fa-plus"></i> Ajouter une salle</button>
        <button class="btn btn-sm btn-danger" id="btnDeleteSalle" data-bs-toggle="modal" data-bs-target="#deleteSalleModal"><i class="fas fa-trash-alt"></i> Supprimer une salle</button>
        <a href="#" id="btnRepartir" class="btn btn-sm btn-primary" onclick="return confirmRepartir();"><i class="fas fa-cogs"></i> Répartir automatiquement</a>
        <button id="btnPrint" class="btn btn-sm btn-secondary" onclick="window.print();"><i class="fas fa-print"></i> Imprimer</button>
      </div>
    </div>

    <div class="card-body p-3">
      <!-- Onglets des centres -->
      <ul class="nav nav-tabs" id="centreTabs" role="tablist">
        <?php foreach($centres as $index => $centre): ?>
          <li class="nav-item" role="presentation">
            <a class="nav-link <?= ($centre['id_centre'] == $centreActif) ? 'active' : ''; ?>"
               href="liste_presence.php?centre=<?= $centre['id_centre']; ?>">
               <?= htmlspecialchars($centre['nom_centre']); ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Contenu des salles -->
      <div class="mt-4">
        <?php
        $nb_salles = count($salles);
        $i_salle = 0;
        foreach($salles as $salle):
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
            Capacité : <strong><?= $salle['capacite'] ?? '—'; ?></strong>
            <!-- Bouton "Modifier" masqué à l'impression -->
            <button class="btn btn-sm btn-outline-secondary ms-2 no-print"
                    data-bs-toggle="modal"
                    data-bs-target="#editCapaciteModal<?= $salle['id_salle'] ?>">
              Modifier
            </button> 
          
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
              <?php $i=1; foreach($candidats as $cand): ?>
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

        <!-- MODAL MODIFIER CAPACITÉ -->
        <div class="modal fade" id="editCapaciteModal<?= $salle['id_salle'] ?>" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="post">
                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title">Modifier capacité — <?= htmlspecialchars($salle['nom_salle']) ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="action" value="modifier_capacite">
                  <input type="hidden" name="id_salle" value="<?= $salle['id_salle'] ?>">
                  <div class="mb-3">
                    <label>Nouvelle capacité :</label>
                    <input type="number" name="capacite" class="form-control" min="1" value="<?= htmlspecialchars($salle['capacite'] ?? '') ?>" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <?php if ($i_salle < $nb_salles): ?>
          <div class="page-break"></div>
        <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- MODAL AJOUT CENTRE -->
<div class="modal fade" id="addCentreModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Ajouter un centre</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action" value="ajouter_centre">
          <div class="mb-3">
            <label>Nom du centre :</label>
            <input type="text" name="nom_centre" class="form-control" required placeholder="Ex : Antananarivo">
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

<!-- MODAL AJOUT SALLE -->
<div class="modal fade" id="addSalleModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Ajouter une salle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action" value="ajouter_salle">
          <div class="mb-3">
            <label>Nom de la salle :</label>
            <input type="text" name="nom_salle" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Capacité :</label>
            <input type="number" name="capacite" class="form-control" min="1" placeholder="Ex : 30">
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

<!-- MODAL SUPPRESSION SALLE -->
<div class="modal fade" id="deleteSalleModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Supprimer une salle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if (empty($salles)): ?>
          <p>Aucune salle à supprimer.</p>
        <?php else: ?>
          <ul class="list-group">
            <?php foreach ($salles as $s): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($s['nom_salle']); ?>
                <a href="?centre=<?= $centreActif ?>&supprimer_salle=<?= $s['id_salle'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette salle ?');">Supprimer</a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmRepartir() {
  const centreId = new URLSearchParams(window.location.search).get('centre');
  if(confirm('Répartir automatiquement les candidats sans salle pour le centre sélectionné ?')) {
      window.location.href = 'repartir_salles.php?niveau=DTS&centre=' + centreId;
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
