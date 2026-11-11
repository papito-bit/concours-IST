<?php
// delib.php
ob_start();
session_start();
if (!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['email_concours'])) {
    header("Location: login.php");
    exit;
}

require 'connect.php';

$id_annee = $_SESSION['id_annee_selected'] ?? null;
$niveau_selected = $_GET['niveau'] ?? 'DTS';
$mention_selected = $_GET['mention'] ?? '';
$id_centre_selected = $_GET['centre'] ?? '';
$search = trim($_GET['search'] ?? '');

// ---------- Enregistrement AJAX ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_save'])) {
    require_once __DIR__ . '/journal_helper.php';
    $id_candidat = $_POST['id_candidat'];
    $notes = $_POST['notes'] ?? [];
    $moyenne = $_POST['moyenne'] ?? null;

    $stmt = $bdd->prepare("SELECT id_notes FROM notes WHERE id_candidat = :c AND id_annee = :a");
    $stmt->execute([':c' => $id_candidat, ':a' => $id_annee]);
    $note_exist = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($note_exist) {
        $bdd->prepare("UPDATE notes SET moyenne = :m WHERE id_notes = :id")
            ->execute([':m' => $moyenne, ':id' => $note_exist['id_notes']]);
        $id_notes = $note_exist['id_notes'];
    } else {
        $bdd->prepare("INSERT INTO notes (id_candidat, moyenne, id_annee) VALUES (:c, :m, :a)")
            ->execute([':c' => $id_candidat, ':m' => $moyenne, ':a' => $id_annee]);
        $id_notes = $bdd->lastInsertId();
    }

    foreach ($notes as $id_matiere => $note_val) {
        $note_val = ($note_val === '' ? null : floatval(str_replace(',', '.', $note_val)));
        if ($note_val === null) continue;
        $chk = $bdd->prepare("SELECT id_note FROM notes_details WHERE id_notes = :n AND id_matiere = :m");
        $chk->execute([':n' => $id_notes, ':m' => $id_matiere]);
        $exists = $chk->fetch(PDO::FETCH_ASSOC);
        if ($exists) {
            $bdd->prepare("UPDATE notes_details SET note = :v WHERE id_note = :id")
                ->execute([':v' => $note_val, ':id' => $exists['id_note']]);
        } else {
            $bdd->prepare("INSERT INTO notes_details (id_notes, id_matiere, note) VALUES (:n,:m,:v)")
                ->execute([':n' => $id_notes, ':m' => $id_matiere, ':v' => $note_val]);
        }
    }

    journaliser('notes_saisie', 'Moyenne ' . $moyenne . ' (candidat n°' . (int) $id_candidat . ')', 'candidat', (int) $id_candidat);
    echo json_encode(['success' => true]);
    exit;
}

// ---------- Récupération des données ----------
$niveaux = ['DTS', 'DTSS', 'INGENIORAT'];

$mentions_stmt = $bdd->prepare("SELECT DISTINCT parcours1 FROM candidats WHERE id_annee = :a AND niveau = :n ORDER BY parcours1");
$mentions_stmt->execute([':a' => $id_annee, ':n' => $niveau_selected]);
$mentions = $mentions_stmt->fetchAll(PDO::FETCH_COLUMN);

$centres = $bdd->query("SELECT id_centre, nom_centre FROM centre ORDER BY nom_centre")->fetchAll(PDO::FETCH_ASSOC);

$mstmt = $bdd->prepare("SELECT * FROM matiere WHERE niveau = :n ORDER BY id_matiere");
$mstmt->execute([':n' => $niveau_selected]);
$matieres = $mstmt->fetchAll(PDO::FETCH_ASSOC);

$cand_sql = "SELECT can.*, cen.nom_centre as centre_name 
             FROM candidats can 
             JOIN centre cen ON can.id_centre = cen.id_centre 
             WHERE can.id_annee = :a AND can.niveau = :n";
$params = [':a' => $id_annee, ':n' => $niveau_selected];
if ($mention_selected !== '') { $cand_sql .= " AND can.parcours1 = :p"; $params[':p'] = $mention_selected; }
if ($id_centre_selected !== '' && $id_centre_selected !== 'all') { $cand_sql .= " AND can.id_centre = :c"; $params[':c'] = $id_centre_selected; }
if ($search !== '') { $cand_sql .= " AND (can.nom LIKE :s OR can.prenom LIKE :s OR can.numero LIKE :s)"; $params[':s'] = "%$search%"; }
$cand_sql .= " ORDER BY can.numero";
$cand_stmt = $bdd->prepare($cand_sql);
$cand_stmt->execute($params);
$candidates = $cand_stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Liste des admis (parcours, moyenne >=10, triée par moyenne DESC)
$admis_stmt = $bdd->prepare("
    SELECT c.id_candidat, c.nom, c.prenom, c.parcours1, n.moyenne 
    FROM notes n 
    JOIN candidats c ON c.id_candidat = n.id_candidat 
    WHERE n.moyenne >= 10 AND c.id_annee = :a AND c.niveau = :n
    ORDER BY c.parcours1, n.moyenne DESC
");
$admis_stmt->execute([':a' => $id_annee, ':n' => $niveau_selected]);
$admis = $admis_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Délibération</h3>
    <div class="card-tools">
      <a class="btn btn-secondary btn-sm" href="index.php"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>
  </div>

  <div class="card-body">
    <!-- Onglets -->
    <ul class="nav nav-tabs" id="delibTabs" role="tablist">
      <li class="nav-item">
        <button class="nav-link active" id="saisie-tab" data-bs-toggle="tab" data-bs-target="#saisie" type="button">Saisie des notes</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" id="admis-tab" data-bs-toggle="tab" data-bs-target="#admis" type="button">Liste des admis</button>
      </li>
    </ul>

    <div class="tab-content mt-4">
      <!-- Onglet 1 : Saisie -->
      <div class="tab-pane fade show active" id="saisie" role="tabpanel">
        <form method="get" class="form-inline mb-3">
          <label class="mr-2">Niveau:</label>
          <select name="niveau" class="form-control mr-3" onchange="this.form.submit()">
            <?php foreach ($niveaux as $n): ?>
              <option value="<?= $n ?>" <?= $n === $niveau_selected ? 'selected' : '' ?>><?= $n ?></option>
            <?php endforeach; ?>
          </select>

          <label class="mr-2">Parcours:</label>
          <select name="mention" class="form-control mr-3" onchange="this.form.submit()">
            <option value="">-- Tous --</option>
            <?php foreach ($mentions as $m): ?>
              <option value="<?= htmlspecialchars($m) ?>" <?= $m === $mention_selected ? 'selected' : '' ?>><?= htmlspecialchars($m) ?></option>
            <?php endforeach; ?>
          </select>

          <label class="mr-2">Centre:</label>
          <select name="centre" class="form-control mr-3" onchange="this.form.submit()">
            <option value="all">-- Tous --</option>
            <?php foreach ($centres as $c): ?>
              <option value="<?= $c['id_centre'] ?>" <?= ($c['id_centre'] == $id_centre_selected ? 'selected' : '') ?>><?= htmlspecialchars($c['nom_centre']) ?></option>
            <?php endforeach; ?>
          </select>

          <input type="text" name="search" class="form-control mr-2" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
          <button class="btn btn-outline-primary" type="submit">Rechercher</button>
        </form>

        <div class="table-responsive">
          <table class="table table-bordered table-sm">
            <thead class="thead-light">
              <tr>
                <th>#</th><th>Num</th><th>Nom</th><th>Prénom</th>
                <?php foreach ($matieres as $m): ?>
                  <th><?= htmlspecialchars($m['nom_matiere']) ?><br><small>(coef <?= $m['coef'] ?>)</small></th>
                <?php endforeach; ?>
                <th>Moyenne</th><th>Décision</th>
              </tr>
            </thead>
            <tbody>
            <?php if (empty($candidates)): ?>
              <tr><td colspan="<?= 6 + count($matieres) ?>">Aucun candidat trouvé.</td></tr>
            <?php endif; ?>

            <?php foreach ($candidates as $cand):
              $note_stmt = $bdd->prepare("SELECT * FROM notes WHERE id_candidat = :c AND id_annee = :a LIMIT 1");
              $note_stmt->execute([':c' => $cand['id_candidat'], ':a' => $id_annee]);
              $note_row = $note_stmt->fetch(PDO::FETCH_ASSOC);
              $moy_init = $note_row['moyenne'] ?? '';
              $decision = ($moy_init === '' ? '' : ($moy_init >= 10 ? 'Admis' : 'Ajourné'));
            ?>
              <tr>
                <td><?= $cand['id_candidat'] ?></td>
                <td><?= $cand['numero'] ?></td>
                <td><?= htmlspecialchars($cand['nom']) ?></td>
                <td><?= htmlspecialchars($cand['prenom']) ?></td>
                <?php foreach ($matieres as $m):
                  $existing_note = '';
                  if ($note_row) {
                      $dstmt = $bdd->prepare("SELECT note FROM notes_details WHERE id_notes = :n AND id_matiere = :m LIMIT 1");
                      $dstmt->execute([':n' => $note_row['id_notes'], ':m' => $m['id_matiere']]);
                      $dr = $dstmt->fetch(PDO::FETCH_ASSOC);
                      $existing_note = $dr['note'] ?? '';
                  }
                ?>
                  <td><input type="text" value="<?= $existing_note ?>" class="form-control note-input" 
                             data-cand="<?= $cand['id_candidat'] ?>" data-mat="<?= $m['id_matiere'] ?>" 
                             data-coef="<?= $m['coef'] ?>"></td>
                <?php endforeach; ?>
                <td><input readonly type="text" class="form-control moyenne-field" id="moy-<?= $cand['id_candidat'] ?>" value="<?= $moy_init ?>"></td>
                <td><input readonly type="text" class="form-control decision-field" id="dec-<?= $cand['id_candidat'] ?>" value="<?= $decision ?>"></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Onglet 2 : Liste des admis -->
      <div class="tab-pane fade" id="admis" role="tabpanel">
        <div class="text-end mb-3">
          <button class="btn btn-outline-secondary" onclick="window.location.reload()">🔄 Actualiser</button>
          <button class="btn btn-outline-primary" onclick="window.print()">🖨 Imprimer</button>
        </div>

        <div id="print-header" class="text-center mb-4 d-print-block">
          <h4><strong>Institut Supérieur de Technologie d’Antsiranana</strong></h4>
          <p><strong>Année scolaire <?= date('Y') ?> - <?= date('Y')+1 ?></strong></p>
          <hr>
          <h5>Liste des candidats admis — <?= htmlspecialchars($niveau_selected) ?></h5>
        </div>

        <?php
          $parcours_group = [];
          foreach ($admis as $a) {
              $parcours_group[$a['parcours1']][] = $a;
          }
          if (empty($parcours_group)) {
              echo "<p class='text-center text-danger'>Aucun admis trouvé.</p>";
          } else {
              $first = true;
              foreach ($parcours_group as $p => $liste) {
                  if (!$first) echo "<div class='page-break'></div>";
                  $first = false;
                  echo "<h6 class='mt-4 text-primary text-center'>Parcours : <strong>".htmlspecialchars($p)."</strong></h6>";
                  echo "<table class='table table-bordered table-sm mt-3'><thead><tr><th>#</th><th>Nom</th><th>Prénom</th><th>Décision</th></tr></thead><tbody>";
                  $i=1;
                  foreach ($liste as $row) {
                      echo "<tr><td>{$i}</td><td>".htmlspecialchars($row['nom'])."</td><td>".htmlspecialchars($row['prenom'])."</td><td><strong>Admis</strong></td></tr>";
                      $i++;
                  }
                  echo "</tbody></table>";
              }
          }
        ?>
      </div>
    </div>
  </div>
</div>

<style>
@media print {
  body { background: white; }
  .card-header, .nav, .btn, .text-end { display: none !important; }
  .page-break { page-break-before: always; }
}
</style>

<script>
document.querySelectorAll('.note-input').forEach(input=>{
  input.addEventListener('input',()=>{
    const id = input.dataset.cand;
    computeMoy(id);
    saveNotes(id);
  });
});

function computeMoy(id){
  let inputs=document.querySelectorAll('.note-input[data-cand="'+id+'"]');
  let sum=0,coef=0,filled=0,total=inputs.length;

  inputs.forEach(i=>{
    let v=i.value.trim(); if(v==='')return;
    v=parseFloat(v.replace(',','.')); if(isNaN(v))return;
    let c=parseFloat(i.dataset.coef)||1;
    sum+=v*c; coef+=c; filled++;
  });

  let niveau = document.querySelector('select[name="niveau"]').value;
  let required = (niveau === 'DTS') ? 5 : 2;

  if(filled < required || coef === 0){
    document.getElementById('moy-'+id).value='';
    document.getElementById('dec-'+id).value='Non admis';
    return;
  }

  let moy=(coef>0)?Math.round((sum/coef)*100)/100:'';
  document.getElementById('moy-'+id).value=moy;
  document.getElementById('dec-'+id).value=(moy>=10?'Admis':'Ajourné');
}

function saveNotes(id){
  const inputs=document.querySelectorAll('.note-input[data-cand="'+id+'"]');
  let notes={};
  inputs.forEach(i=>{ notes[i.dataset.mat]=i.value; });
  const moy=document.getElementById('moy-'+id).value;
  const fd=new FormData();
  fd.append('ajax_save',1);
  fd.append('id_candidat',id);
  fd.append('moyenne',moy);
  for(let k in notes){fd.append('notes['+k+']',notes[k]);}
  fetch('',{method:'POST',body:fd})
    .then(r=>r.json())
    .then(data=>{ console.log('Auto-sauvegarde OK', data); });
}
</script>

<?php
$content = ob_get_clean();
$menu = "Délibération";
require 'template.php';
?>
