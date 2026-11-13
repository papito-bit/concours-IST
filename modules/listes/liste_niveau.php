<?php
ob_start();
session_start();
if(!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['email_concours'])){
    header("Location:../../login.php");
    exit;
}

require __DIR__ . '/../../config/connect.php';

// Récupérer tous les niveaux de l'année sélectionnée
$req = $bdd->prepare("SELECT DISTINCT niveau FROM candidats WHERE id_annee = ? ORDER BY niveau");
$req->execute([$_SESSION['id_annee_selected']]);
$niveaux = $req->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">Liste des candidats par niveau et parcours</h3>
    <button class="btn btn-sm btn-secondary" onclick="window.print()"><i class="fas fa-print"></i> Imprimer</button>
  </div>

  <div class="card-body p-3">

    <?php if(empty($niveaux)): ?>
      <p class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Aucun niveau trouvé pour cette année.</p>
    <?php else: ?>

      <!-- Onglets principaux : niveaux -->
      <ul class="nav nav-tabs" id="niveauTabs" role="tablist">
        <?php foreach($niveaux as $i => $niveau): ?>
          <li class="nav-item">
            <button class="nav-link <?= $i===0 ? 'active' : '' ?>"
                    id="tab-<?= htmlspecialchars($niveau) ?>"
                    data-bs-toggle="tab"
                    data-bs-target="#niveau-<?= htmlspecialchars($niveau) ?>"
                    type="button" role="tab">
              <?= htmlspecialchars($niveau) ?>
            </button>
          </li>
        <?php endforeach; ?>
      </ul>

      <div class="tab-content mt-3" id="niveauTabsContent">
        <?php foreach($niveaux as $i => $niveau): ?>
          <div class="tab-pane fade <?= $i===0 ? 'show active' : '' ?>"
               id="niveau-<?= htmlspecialchars($niveau) ?>" role="tabpanel">

            <?php
            // Récupérer les parcours de ce niveau
            $reqParcours = $bdd->prepare("
              SELECT DISTINCT parcours1 
              FROM candidats 
              WHERE id_annee = ? AND niveau = ? 
              ORDER BY parcours1
            ");
            $reqParcours->execute([$_SESSION['id_annee_selected'], $niveau]);
            $parcoursList = $reqParcours->fetchAll(PDO::FETCH_COLUMN);
            ?>

            <?php if(empty($parcoursList)): ?>
              <p class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Aucun parcours trouvé pour le niveau <?= htmlspecialchars($niveau) ?>.</p>
            <?php else: ?>

              <!-- Sous-onglets : parcours -->
              <ul class="nav nav-pills mb-3" id="parcoursTabs-<?= htmlspecialchars($niveau) ?>" role="tablist">
                <?php foreach($parcoursList as $j => $parcours): ?>
                  <li class="nav-item">
                    <button class="nav-link <?= $j===0 ? 'active' : '' ?>"
                            id="p-tab-<?= htmlspecialchars($niveau.'-'.$parcours) ?>"
                            data-bs-toggle="tab"
                            data-bs-target="#p-content-<?= htmlspecialchars($niveau.'-'.$parcours) ?>"
                            type="button" role="tab">
                      <?= htmlspecialchars($parcours) ?>
                    </button>
                  </li>
                <?php endforeach; ?>
              </ul>

              <div class="tab-content" id="parcoursContent-<?= htmlspecialchars($niveau) ?>">
                <?php foreach($parcoursList as $j => $parcours): ?>
                  <div class="tab-pane fade <?= $j===0 ? 'show active' : '' ?>"
                       id="p-content-<?= htmlspecialchars($niveau.'-'.$parcours) ?>"
                       role="tabpanel">

                    <?php
                    // Récupérer les candidats du parcours et niveau
                    $reqCand = $bdd->prepare("
                      SELECT nom, prenom, parcours1, id_centre
                      FROM candidats
                      WHERE id_annee = ? AND niveau = ? AND parcours1 = ?
                      ORDER BY nom, prenom
                    ");
                    $reqCand->execute([$_SESSION['id_annee_selected'], $niveau, $parcours]);
                    $candidats = $reqCand->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <?php if(empty($candidats)): ?>
                      <p class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Aucun candidat trouvé pour <?= htmlspecialchars($parcours) ?>.</p>
                    <?php else: ?>
                      <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                          <thead class="table-light text-center">
                            <tr>
                              <th style="width:5%">N°</th>
                              <th style="width:20%">Nom</th>
                              <th style="width:20%">Prénoms</th>
                              <th style="width:25%">Parcours</th>
                              <th style="width:20%">Centre</th>
                              <th style="width:10%">Signature</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $k=1;
                            foreach($candidats as $cand): 
                              $nom_centre = $bdd->prepare("SELECT nom_centre FROM centre WHERE id_centre = ?");
                              $nom_centre->execute([$cand['id_centre']]);
                              $centre = $nom_centre->fetchColumn() ?: "-";
                            ?>
                              <tr>
                                <td class="text-center"><?= $k++ ?></td>
                                <td><?= htmlspecialchars($cand['nom']) ?></td>
                                <td><?= htmlspecialchars($cand['prenom']) ?></td>
                                <td><?= htmlspecialchars($cand['parcours1']) ?></td>
                                <td><?= htmlspecialchars($centre) ?></td>
                                <td></td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                        <p><strong>Total candidats :</strong> <?= count($candidats) ?></p>
                      </div>
                    <?php endif; ?>

                  </div>
                <?php endforeach; ?>
              </div>

            <?php endif; ?>

          </div>
        <?php endforeach; ?>
      </div>

    <?php endif; ?>
  </div>
</div>

<!-- Style impression -->
<style>
@media print {
  .card-header, .nav, .btn, .navbar, .main-sidebar { display: none !important; }
  .card-body { padding: 0 !important; }
  table { font-size: 13px; }
  h3.card-title { text-align: center; margin-bottom: 10px; }
}
</style>

<?php
$content = ob_get_clean();
$menu = "";
$menu_niveau = "nav-item menu-open";
$liste_niveau = "nav-link active";
require __DIR__ . '/../../includes/template.php';
?>
