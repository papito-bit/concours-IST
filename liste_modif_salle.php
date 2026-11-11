<?php
ob_start();
session_start();
if(!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['email_concours'])){
    header("Location:login.php");
    exit;
}

require "connect.php";

// Récupération de tous les centres
$centres = $bdd->query("SELECT * FROM centre ORDER BY nom_centre")->fetchAll(PDO::FETCH_ASSOC);

// Centre actif (par défaut le premier)
$centreActif = isset($_GET['centre']) ? $_GET['centre'] : ($centres[0]['id_centre'] ?? null);

// Traitement de la modification
if(isset($_POST['update_salle'])) {
    $id_candidat = $_POST['id_candidat'];
    $new_salle = $_POST['id_salle'];
    $update = $bdd->prepare("UPDATE candidats SET id_salle = ? WHERE id_candidat = ?");
    $update->execute([$new_salle, $id_candidat]);
    require_once __DIR__ . '/journal_helper.php';
    journaliser('salle_changement', 'Candidat n°' . (int) $id_candidat . ' -> salle n°' . (int) $new_salle, 'candidat', (int) $id_candidat);
    $_SESSION['msg_success'] = "Salle modifiée avec succès pour le candidat #$id_candidat.";
    header("Location: liste_modif_salle.php?centre=".$centreActif);
    exit;
}

// Récupération des salles du centre sélectionné
$salles = $bdd->prepare("SELECT * FROM salle WHERE id_centre=? ORDER BY nom_salle");
$salles->execute([$centreActif]);
$salles = $salles->fetchAll(PDO::FETCH_ASSOC);

// Récupération des niveaux
$niveaux = ["DTS", "DTSS", "INGENIORAT"];
?>

<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">Modifier la salle des candidats</h3>
    <?php if(isset($_SESSION['msg_success'])): ?>
      <span class="text-success fw-bold"><?= $_SESSION['msg_success']; unset($_SESSION['msg_success']); ?></span>
    <?php endif; ?>
  </div>

  <div class="card-body p-3">

    <!-- Onglets des centres -->
    <ul class="nav nav-tabs" id="centreTabs" role="tablist">
      <?php foreach($centres as $index => $centre): ?>
        <li class="nav-item" role="presentation">
          <a class="nav-link <?= ($centre['id_centre'] == $centreActif) ? 'active' : ''; ?>"
             href="liste_modif_salle.php?centre=<?= $centre['id_centre']; ?>">
             <?= htmlspecialchars($centre['nom_centre']); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Onglets des niveaux -->
    <ul class="nav nav-tabs mt-3" id="niveauTabs" role="tablist">
      <?php foreach($niveaux as $index => $niveau): ?>
        <li class="nav-item">
          <button class="nav-link <?= $index===0 ? 'active' : ''; ?>"
                  id="tab-<?= $niveau; ?>"
                  data-bs-toggle="tab"
                  data-bs-target="#content-<?= $niveau; ?>"
                  type="button" role="tab">
            <?= $niveau; ?>
          </button>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Contenu par niveau -->
    <div class="tab-content mt-4">
      <?php foreach($niveaux as $index => $niveau): ?>
        <div class="tab-pane fade <?= $index===0 ? 'show active' : ''; ?>"
             id="content-<?= $niveau; ?>" role="tabpanel">

          <?php
          // Récupérer les candidats du centre et niveau sélectionnés
          $reqCandidats = $bdd->prepare("
            SELECT c.id_candidat, c.nom, c.prenom, c.niveau, c.parcours1, c.id_salle, s.nom_salle
            FROM candidats c
            LEFT JOIN salle s ON c.id_salle = s.id_salle
            WHERE c.id_annee = ? AND c.id_centre = ? AND c.niveau = ?
            ORDER BY c.nom, c.prenom
          ");
          $reqCandidats->execute([$_SESSION["id_annee_selected"], $centreActif, $niveau]);
          $candidats = $reqCandidats->fetchAll(PDO::FETCH_ASSOC);
          ?>

          <?php if(empty($candidats)): ?>
            <p class="text-center text-danger">Aucun candidat trouvé pour ce niveau.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-bordered table-striped align-middle">
                <thead class="table-light text-center">
                  <tr>
                    <th style="width:5%">#</th>
                    <th style="width:20%">Nom</th>
                    <th style="width:20%">Prénoms</th>
                    <th style="width:20%">Parcours</th>
                    <th style="width:20%">Salle actuelle</th>
                    <th style="width:15%">Nouvelle salle</th>
                    <th style="width:10%">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1; foreach($candidats as $cand): ?>
                    <tr>
                      <td class="text-center"><?= $i++; ?></td>
                      <td><?= htmlspecialchars($cand['nom']); ?></td>
                      <td><?= htmlspecialchars($cand['prenom']); ?></td>
                      <td><?= htmlspecialchars($cand['parcours1']); ?></td>
                      <td class="text-center">
                        <?= htmlspecialchars($cand['nom_salle'] ?? '-') ?>
                      </td>
                      <td>
                        <form method="post" class="d-flex align-items-center justify-content-center">
                          <input type="hidden" name="id_candidat" value="<?= $cand['id_candidat']; ?>">
                          <select name="id_salle" class="form-select form-select-sm" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach($salles as $s): ?>
                              <option value="<?= $s['id_salle']; ?>"
                                <?= ($s['id_salle'] == $cand['id_salle']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($s['nom_salle']); ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                      </td>
                      <td class="text-center">
                          <button type="submit" name="update_salle" class="btn btn-sm btn-primary" title="Enregistrer la salle">
                            <i class="fas fa-save"></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>

        </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<style>
.table select {
    min-width: 120px;
}
</style>

<?php
$content = ob_get_clean();
$menu = "";
$menu_salle = "nav-item menu-open";
$modif_salle = "nav-link active";
require('template.php');
?>
