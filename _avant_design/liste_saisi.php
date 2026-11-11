<?php
ob_start();
require_once __DIR__ . '/auth.php';
require 'connect.php';

$id_annee  = $_SESSION['id_annee_selected'] ?? null;
$est_admin = in_array(strtolower($_SESSION['groupe'] ?? ''), ['admin', 'superadmin'], true);

// ---------- Filtres ----------
$q        = trim($_GET['q'] ?? '');
$f_niveau = trim($_GET['niveau'] ?? '');
$f_ecole  = trim($_GET['ecole'] ?? '');
$f_centre = (int) ($_GET['centre'] ?? 0);
$f_parc   = trim($_GET['parcours'] ?? '');
$f_sexe   = trim($_GET['sexe'] ?? '');
$f_ctrl   = $_GET['controle'] ?? '';
$par_page = (int) ($_GET['par_page'] ?? 25);
if (!in_array($par_page, [25, 50, 100], true)) {
    $par_page = 25;
}
$page = max(1, (int) ($_GET['page'] ?? 1));

$where  = ['can.id_annee = ?'];
$params = [$id_annee];

if (!$est_admin) {
    // Un opérateur ne voit que les candidats qu'il a saisis
    $where[]  = 'can.saisi_par = ?';
    $params[] = (int) $_SESSION['id_utilisateur'];
    $where[]  = "(can.obs IS NULL OR can.obs <> 'bz')";
}
if ($q !== '') {
    $where[] = "(can.nom LIKE ? OR can.prenom LIKE ? OR CONCAT(can.nom, ' ', can.prenom) LIKE ? OR can.telephone LIKE ? OR can.N_CIN LIKE ?)";
    $like = '%' . $q . '%';
    array_push($params, $like, $like, $like, $like, $like);
}
if ($f_niveau !== '') { $where[] = 'can.niveau = ?';    $params[] = $f_niveau; }
if ($f_ecole !== '')  { $where[] = 'can.ecole = ?';     $params[] = $f_ecole; }
if ($f_centre > 0)    { $where[] = 'can.id_centre = ?'; $params[] = $f_centre; }
if ($f_parc !== '')   { $where[] = 'can.parcours1 = ?'; $params[] = $f_parc; }
if ($f_sexe !== '')   { $where[] = 'can.sexe = ?';      $params[] = $f_sexe; }
if ($f_ctrl === '0' || $f_ctrl === '1') { $where[] = 'can.controle = ?'; $params[] = (int) $f_ctrl; }

$sqlw = 'WHERE ' . implode(' AND ', $where);
$from = 'FROM candidats can
         LEFT JOIN centre cen ON cen.id_centre = can.id_centre
         LEFT JOIN utilisateur u ON u.id_utilisateur = can.saisi_par';

$erreur = '';
$lignes = [];
$total  = 0;
$pages  = 1;
$niveaux = $ecoles = $parcours = $centres = [];

try {
    $st = $bdd->prepare("SELECT COUNT(*) $from $sqlw");
    $st->execute($params);
    $total = (int) $st->fetchColumn();
    $pages = max(1, (int) ceil($total / $par_page));
    $page  = min($page, $pages);
    $offset = ($page - 1) * $par_page;

    $st = $bdd->prepare(
        "SELECT can.*, cen.nom_centre AS centre, CONCAT_WS(' ', u.nom, u.prenom) AS saisi_par_nom
         $from $sqlw ORDER BY can.id_candidat LIMIT $par_page OFFSET $offset"
    );
    $st->execute($params);
    $lignes = $st->fetchAll(PDO::FETCH_ASSOC);

    $distinct = function ($col) use ($bdd, $id_annee) {
        $s = $bdd->prepare("SELECT DISTINCT $col FROM candidats WHERE id_annee = ? AND $col IS NOT NULL AND $col <> '' ORDER BY $col");
        $s->execute([$id_annee]);
        return $s->fetchAll(PDO::FETCH_COLUMN);
    };
    $niveaux  = $distinct('niveau');
    $ecoles   = $distinct('ecole');
    $parcours = $distinct('parcours1');

    $s = $bdd->prepare('SELECT id_centre, nom_centre FROM centre WHERE id_annee = ? ORDER BY nom_centre');
    $s->execute([$id_annee]);
    $centres = $s->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $erreur = "Erreur lors de la lecture des candidats : " . $e->getMessage();
}

$base_qs = $_GET;
unset($base_qs['page']);
$filtres_actifs = ($q !== '' || $f_niveau !== '' || $f_ecole !== '' || $f_centre > 0 || $f_parc !== '' || $f_sexe !== '' || $f_ctrl !== '');
?>
<style>
  #candidatsTable thead th { position: sticky; top: 0; background: #343a40; color: #fff; z-index: 2; white-space: nowrap; }
  #candidatsTable td[contenteditable="true"] { background: #fff8e1; cursor: text; }
  #candidatsTable td[contenteditable="true"]:focus { background: #ffecb3; outline: 2px solid #ffc107; }
  .table-scroll { max-height: 70vh; overflow: auto; }
</style>

<div class="card">
  <div class="card-header"><h3 class="card-title">Rechercher et filtrer</h3></div>
  <div class="card-body">
    <form method="get" class="form-row">
      <div class="col-md-3 mb-2">
        <input type="text" name="q" class="form-control" placeholder="Nom, prénom, téléphone, CIN..." value="<?= h($q) ?>">
      </div>
      <div class="col-md-2 mb-2">
        <select name="niveau" class="form-control">
          <option value="">Tous les niveaux</option>
          <?php foreach ($niveaux as $v) { ?><option value="<?= h($v) ?>" <?= $v === $f_niveau ? 'selected' : '' ?>><?= h($v) ?></option><?php } ?>
        </select>
      </div>
      <div class="col-md-2 mb-2">
        <select name="ecole" class="form-control">
          <option value="">Toutes les écoles</option>
          <?php foreach ($ecoles as $v) { ?><option value="<?= h($v) ?>" <?= $v === $f_ecole ? 'selected' : '' ?>><?= h($v) ?></option><?php } ?>
        </select>
      </div>
      <div class="col-md-2 mb-2">
        <select name="centre" class="form-control">
          <option value="0">Tous les centres</option>
          <?php foreach ($centres as $c) { ?><option value="<?= (int) $c['id_centre'] ?>" <?= (int) $c['id_centre'] === $f_centre ? 'selected' : '' ?>><?= h($c['nom_centre']) ?></option><?php } ?>
        </select>
      </div>
      <div class="col-md-3 mb-2">
        <select name="parcours" class="form-control">
          <option value="">Tous les parcours</option>
          <?php foreach ($parcours as $v) { ?><option value="<?= h($v) ?>" <?= $v === $f_parc ? 'selected' : '' ?>><?= h($v) ?></option><?php } ?>
        </select>
      </div>
      <div class="col-md-2 mb-2">
        <select name="sexe" class="form-control">
          <option value="">Tous les sexes</option>
          <option value="F" <?= $f_sexe === 'F' ? 'selected' : '' ?>>Féminin</option>
          <option value="M" <?= $f_sexe === 'M' ? 'selected' : '' ?>>Masculin</option>
        </select>
      </div>
      <div class="col-md-2 mb-2">
        <select name="controle" class="form-control">
          <option value="">Contrôlé ou non</option>
          <option value="1" <?= $f_ctrl === '1' ? 'selected' : '' ?>>Contrôlés</option>
          <option value="0" <?= $f_ctrl === '0' ? 'selected' : '' ?>>Non contrôlés</option>
        </select>
      </div>
      <div class="col-md-2 mb-2">
        <select name="par_page" class="form-control" title="Lignes par page">
          <?php foreach ([25, 50, 100] as $n) { ?><option value="<?= $n ?>" <?= $n === $par_page ? 'selected' : '' ?>><?= $n ?> par page</option><?php } ?>
        </select>
      </div>
      <div class="col-md-4 mb-2">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filtrer</button>
        <?php if ($filtres_actifs) { ?><a href="liste_saisi.php" class="btn btn-default">Réinitialiser</a><?php } ?>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title"><?= (int) $total ?> candidat(s) <?= $filtres_actifs ? 'trouvé(s)' : '' ?></h3>
    <div class="card-tools text-muted text-sm">Clique sur une cellule jaune pour la modifier : l'enregistrement est automatique.</div>
  </div>
  <div class="card-body p-0">
    <?php if ($erreur) { ?>
      <div class="alert alert-warning m-3"><?= h($erreur) ?></div>
    <?php } elseif ($id_annee === null) { ?>
      <div class="alert alert-info m-3">Aucune année scolaire sélectionnée.</div>
    <?php } else { ?>
      <div class="table-scroll">
        <table class="table table-bordered table-hover table-sm" id="candidatsTable">
          <thead>
            <tr>
              <th>Pos.</th><th>N° inscription</th><th>Nom</th><th>Prénom</th><th>Date naiss.</th>
              <th>Lieu naiss.</th><th>Sexe</th><th>Adresse</th><th>Téléphone</th><th>Type</th>
              <th>Série</th><th>Année Bacc</th><th>Centre</th><th>Parcours 1</th><th>Parcours 2</th>
              <th>École</th><th>Niveau</th><th>Contrôlé</th><th>Saisi par</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$lignes) { ?>
              <tr><td colspan="19" class="text-center text-muted">Aucun candidat ne correspond.</td></tr>
            <?php } ?>
            <?php
            $pos = ($page - 1) * $par_page;
            foreach ($lignes as $r) {
                $pos++;
                $num = sprintf('%03d', $r['numero']) . '24' . $r['parcours1'];
                $editables = [
                    'nom' => $r['nom'], 'prenom' => $r['prenom'], 'date_naissance' => $r['date_naissance'],
                    'lieu_naissance' => $r['lieu_naissance'], 'sexe' => $r['sexe'], 'adresse' => $r['adresse'],
                    'telephone' => $r['telephone'], 'type_candidat' => $r['type_candidat'], 'serie_bacc' => $r['serie_bacc'],
                    'annee_bacc' => $r['annee_bacc'], 'centre' => $r['centre'], 'parcours1' => $r['parcours1'],
                    'parcours2' => $r['parcours2'],
                ]; ?>
              <tr data-id="<?= (int) $r['id_candidat'] ?>">
                <td><?= $pos ?></td>
                <td><?= h($num) ?></td>
                <?php foreach ($editables as $champ => $valeur) { ?>
                  <td contenteditable="true" data-field="<?= h($champ) ?>"><?= h($valeur) ?></td>
                <?php } ?>
                <td><?= h($r['ecole']) ?></td>
                <td><?= h($r['niveau']) ?></td>
                <td><?= !empty($r['controle']) ? '<span class="badge badge-success">Oui</span>' : '<span class="badge badge-secondary">Non</span>' ?></td>
                <td><?= h($r['saisi_par_nom']) ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php } ?>
  </div>
  <?php if ($pages > 1) { ?>
    <div class="card-footer">
      <span class="text-muted text-sm">Page <?= $page ?> sur <?= $pages ?></span>
      <ul class="pagination pagination-sm m-0 float-right">
        <?php if ($page > 1) { ?>
          <li class="page-item"><a class="page-link" href="liste_saisi.php?<?= h(http_build_query($base_qs + ['page' => $page - 1])) ?>">&laquo;</a></li>
        <?php } ?>
        <?php for ($p = max(1, $page - 3); $p <= min($pages, $page + 3); $p++) { ?>
          <li class="page-item <?= $p === $page ? 'active' : '' ?>">
            <a class="page-link" href="liste_saisi.php?<?= h(http_build_query($base_qs + ['page' => $p])) ?>"><?= $p ?></a>
          </li>
        <?php } ?>
        <?php if ($page < $pages) { ?>
          <li class="page-item"><a class="page-link" href="liste_saisi.php?<?= h(http_build_query($base_qs + ['page' => $page + 1])) ?>">&raquo;</a></li>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>
</div>

<script>
// Édition directe : n'envoie la modification que si la valeur a changé
document.querySelectorAll("td[contenteditable='true']").forEach(function (cell) {
  cell.addEventListener("focus", function () { this.dataset.old = this.innerText.trim(); });
  cell.addEventListener("blur", function () {
    var cellule = this;
    var valeur = cellule.innerText.trim();
    if (valeur === cellule.dataset.old) { return; }
    var id = cellule.closest("tr").dataset.id;
    var champ = cellule.dataset.field;
    fetch("update_candidat_ajax.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "id=" + encodeURIComponent(id) + "&field=" + encodeURIComponent(champ) + "&value=" + encodeURIComponent(valeur)
    })
    .then(function (r) { return r.text(); })
    .then(function (t) {
      if (t.trim() === "ok") {
        cellule.dataset.old = valeur;
        cellule.style.background = "#d4edda";
        setTimeout(function () { cellule.style.background = ""; }, 1200);
      } else {
        cellule.innerText = cellule.dataset.old;
        alert("Modification refusée : " + t);
      }
    })
    .catch(function () { cellule.innerText = cellule.dataset.old; alert("Erreur réseau."); });
  });
});
</script>
<?php
$content = ob_get_clean();
$menu = "Liste des candidats";
require 'template.php';
