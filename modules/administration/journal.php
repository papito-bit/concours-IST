<?php
ob_start();
require_once __DIR__ . '/../../includes/auth.php';
exiger_role(['superadmin', 'admin']);
require_once __DIR__ . '/../../includes/journal_helper.php';

$libelles = [
    'connexion'         => ['Connexion', 'success'],
    'connexion_echec'   => ['Échec de connexion', 'danger'],
    'deconnexion'       => ['Déconnexion', 'secondary'],
    'candidat_ajout'    => ['Candidat ajouté', 'primary'],
    'candidat_modif'    => ['Candidat modifié', 'info'],
    'notes_saisie'      => ['Notes enregistrées', 'warning'],
    'salle_changement'  => ['Changement de salle', 'info'],
    'utilisateur_ajout' => ['Utilisateur ajouté', 'primary'],
    'utilisateur_modif' => ['Utilisateur modifié', 'info'],
    'utilisateur_suppr' => ['Utilisateur supprimé', 'danger'],
    'annee_ajout'       => ['Année créée', 'dark'],
];

$f_user   = trim($_GET['utilisateur'] ?? '');
$f_action = trim($_GET['action'] ?? '');
$f_debut  = trim($_GET['debut'] ?? '');
$f_fin    = trim($_GET['fin'] ?? '');
$f_texte  = trim($_GET['q'] ?? '');

$par_page = 50;
$page = max(1, (int) ($_GET['page'] ?? 1));
$erreur = '';
$lignes = [];
$users = [];
$actions = [];
$total = 0;
$pages = 1;

try {
    $where = [];
    $params = [];
    if ($f_user !== '')   { $where[] = 'nom_utilisateur = ?'; $params[] = $f_user; }
    if ($f_action !== '') { $where[] = 'action = ?';          $params[] = $f_action; }
    if ($f_debut !== '')  { $where[] = 'created_at >= ?';     $params[] = $f_debut . ' 00:00:00'; }
    if ($f_fin !== '')    { $where[] = 'created_at <= ?';     $params[] = $f_fin . ' 23:59:59'; }
    if ($f_texte !== '')  { $where[] = 'details LIKE ?';      $params[] = '%' . $f_texte . '%'; }
    $sqlw = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $st = $bdd->prepare("SELECT COUNT(*) FROM journal_activite $sqlw");
    $st->execute($params);
    $total = (int) $st->fetchColumn();
    $pages = max(1, (int) ceil($total / $par_page));
    $page = min($page, $pages);
    $offset = ($page - 1) * $par_page;

    $st = $bdd->prepare("SELECT * FROM journal_activite $sqlw ORDER BY id DESC LIMIT $par_page OFFSET $offset");
    $st->execute($params);
    $lignes = $st->fetchAll(PDO::FETCH_ASSOC);

    $users   = $bdd->query('SELECT DISTINCT nom_utilisateur FROM journal_activite ORDER BY nom_utilisateur')->fetchAll(PDO::FETCH_COLUMN);
    $actions = $bdd->query('SELECT DISTINCT action FROM journal_activite ORDER BY action')->fetchAll(PDO::FETCH_COLUMN);
} catch (Throwable $e) {
    $erreur = "La table du journal est introuvable. Lance INSTALLER.bat (ou exécute base.sql dans phpMyAdmin).";
}

$base_qs = $_GET;
unset($base_qs['page']);
?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Filtres</h3>
  </div>
  <div class="card-body">
    <form method="get" class="form-row">
      <div class="col-md-2 mb-2">
        <select name="utilisateur" class="form-control">
          <option value="">Tous les utilisateurs</option>
          <?php foreach ($users as $u) { ?>
            <option value="<?= h($u) ?>" <?= $u === $f_user ? 'selected' : '' ?>><?= h($u) ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-md-2 mb-2">
        <select name="action" class="form-control">
          <option value="">Toutes les actions</option>
          <?php foreach ($actions as $a) { ?>
            <option value="<?= h($a) ?>" <?= $a === $f_action ? 'selected' : '' ?>><?= h($libelles[$a][0] ?? $a) ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-md-2 mb-2"><input type="date" name="debut" class="form-control" value="<?= h($f_debut) ?>" title="Du"></div>
      <div class="col-md-2 mb-2"><input type="date" name="fin" class="form-control" value="<?= h($f_fin) ?>" title="Au"></div>
      <div class="col-md-2 mb-2"><input type="text" name="q" class="form-control" placeholder="Rechercher dans les détails" value="<?= h($f_texte) ?>"></div>
      <div class="col-md-2 mb-2">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filtrer</button>
        <a href="journal.php" class="btn btn-default">Réinitialiser</a>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title"><?= (int) $total ?> action(s) enregistrée(s)</h3>
  </div>
  <div class="card-body table-responsive p-0">
    <?php if ($erreur) { ?>
      <div class="alert alert-warning m-3"><?= h($erreur) ?></div>
    <?php } else { ?>
      <table class="table table-striped table-hover text-nowrap">
        <thead>
          <tr><th>Date</th><th>Utilisateur</th><th>Action</th><th>Détails</th><th>Adresse IP</th></tr>
        </thead>
        <tbody>
          <?php if (!$lignes) { ?>
            <tr><td colspan="5" class="text-muted text-center">Aucune action trouvée.</td></tr>
          <?php } ?>
          <?php foreach ($lignes as $l) {
              $lib = $libelles[$l['action']] ?? [$l['action'], 'secondary']; ?>
            <tr>
              <td><?= h(date('d/m/Y H:i:s', strtotime($l['created_at']))) ?></td>
              <td><?= h($l['nom_utilisateur']) ?></td>
              <td><span class="badge badge-<?= h($lib[1]) ?>"><?= h($lib[0]) ?></span></td>
              <td style="white-space: normal;"><?= h($l['details']) ?></td>
              <td><?= h($l['ip']) ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } ?>
  </div>
  <?php if ($pages > 1) { ?>
    <div class="card-footer">
      <ul class="pagination pagination-sm m-0 float-right">
        <?php for ($p = 1; $p <= $pages; $p++) { ?>
          <li class="page-item <?= $p === $page ? 'active' : '' ?>">
            <a class="page-link" href="journal.php?<?= h(http_build_query($base_qs + ['page' => $p])) ?>"><?= $p ?></a>
          </li>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>
</div>
<?php
$content = ob_get_clean();
$menu = "Journal d'activité";
require __DIR__ . '/../../includes/template.php';
