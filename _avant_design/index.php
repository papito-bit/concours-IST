<?php
ob_start();
session_start();

if (isset($_SESSION['id_utilisateur']) && isset($_SESSION['email_concours'])) {

    require 'connect.php';
    require 'get_annee.php';

    if (isset($_GET['annee'])) {
        // Sécurité : on n'accepte qu'une année qui existe vraiment (nombre entier)
        $anneeDemandee = (int) $_GET['annee'];
        foreach (($_SESSION['annees'] ?? []) as $a) {
            if ((int) $a['id_annee'] === $anneeDemandee) {
                $_SESSION['id_annee_selected'] = $anneeDemandee;
                break;
            }
        }
    }

    // ===================== STATISTIQUES ANNUELLES =====================

    $sql = "SELECT niveau, ecole, COUNT(*) as nb
            FROM candidats
            WHERE id_annee = " . (int) $_SESSION['id_annee_selected'] . "
            GROUP BY niveau, ecole";

    $result = $bdd->query($sql);

    $data = [];
    $totaux_niveau = [
        'DTS' => 0,
        'DTSS' => 0,
        'INGENIORAT' => 0
    ];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

        $niveau = $row['niveau'];
        $ecole  = $row['ecole'];
        $nb     = (int)$row['nb'];

        $data[$niveau][$ecole] = $nb;

        $totaux_niveau[$niveau] =
            ($totaux_niveau[$niveau] ?? 0) + $nb;
    }

    $total_candidats = array_sum($totaux_niveau);

    // ===================== DONNÉES HISTORIQUES =====================

    $sqlEvolution = "
        SELECT
            a.libelle AS annee,
            c.niveau,
            c.ecole,
            c.parcours1,
            c.parcours2,
            cen.nom_centre,
            COUNT(c.id_candidat) AS total

        FROM candidats c

        JOIN annees a
            ON a.id_annee = c.id_annee

        LEFT JOIN centre cen
            ON cen.id_centre = c.id_centre

        GROUP BY
            a.libelle,
            c.niveau,
            c.ecole,
            c.parcours1,
            c.parcours2,
            cen.nom_centre

        ORDER BY a.libelle ASC
    ";

    $rows = $bdd->query($sqlEvolution)->fetchAll(PDO::FETCH_ASSOC);

    $annees = array_values(array_unique(array_column($rows, 'annee')));
    sort($annees);

    $ecoles = array_values(array_unique(array_filter(array_column($rows, 'ecole'))));

    $centres = array_values(array_unique(array_filter(array_column($rows, 'nom_centre'))));

    $niveaux = array_values(array_unique(array_filter(array_column($rows, 'niveau'))));

    $parcoursList = array_values(array_unique(array_filter(array_merge(
        array_column($rows, 'parcours1'),
        array_column($rows, 'parcours2')
    ))));

    sort($parcoursList);
?>

<!-- ===================== TABLEAUX ===================== -->

<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $totaux_niveau['DTS'] ?></h3>
                <p>Candidats DTS</p>
            </div>

            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $totaux_niveau['DTSS'] ?></h3>
                <p>Candidats DTSS</p>
            </div>

            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $totaux_niveau['INGENIORAT'] ?></h3>
                <p>Candidats Ingéniorat</p>
            </div>

            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $total_candidats ?></h3>
                <p>Total candidats</p>
            </div>

            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
        </div>
    </div>

</div>

<?php
// ===================== STATISTIQUES DÉTAILLÉES DE L'ANNÉE =====================
$idA = (int) ($_SESSION['id_annee_selected'] ?? 0);
$k = ['total' => 0, 'controles' => 0, 'affectes' => 0, 'femmes' => 0];
$avec_notes = 0;
$g_sexe = $g_type = $g_centre = $g_parcours = [];
$activites = [];
$est_admin_dash = in_array(strtolower($_SESSION['groupe'] ?? ''), ['admin', 'superadmin'], true);

try {
    $st = $bdd->prepare("SELECT COUNT(*) AS total, SUM(controle = 1) AS controles,
                                SUM(id_salle IS NOT NULL) AS affectes, SUM(sexe = 'F') AS femmes
                         FROM candidats WHERE id_annee = ?");
    $st->execute([$idA]);
    foreach ($st->fetch(PDO::FETCH_ASSOC) as $cle => $val) { $k[$cle] = (int) $val; }

    $st = $bdd->prepare('SELECT COUNT(DISTINCT id_candidat) FROM notes WHERE id_annee = ?');
    $st->execute([$idA]);
    $avec_notes = (int) $st->fetchColumn();

    $groupe = function ($sql) use ($bdd, $idA) {
        $s = $bdd->prepare($sql);
        $s->execute([$idA]);
        return $s->fetchAll(PDO::FETCH_KEY_PAIR);
    };
    $g_sexe     = $groupe("SELECT sexe, COUNT(*) FROM candidats WHERE id_annee = ? GROUP BY sexe");
    $g_type     = $groupe("SELECT COALESCE(NULLIF(type_candidat, ''), 'Non précisé'), COUNT(*) FROM candidats WHERE id_annee = ? GROUP BY 1");
    $g_centre   = $groupe("SELECT COALESCE(cen.nom_centre, 'Sans centre'), COUNT(*) AS n FROM candidats c LEFT JOIN centre cen ON cen.id_centre = c.id_centre WHERE c.id_annee = ? GROUP BY 1 ORDER BY n DESC");
    $g_parcours = $groupe("SELECT parcours1, COUNT(*) AS n FROM candidats WHERE id_annee = ? AND parcours1 IS NOT NULL AND parcours1 <> '' GROUP BY 1 ORDER BY n DESC LIMIT 10");

    if ($est_admin_dash) {
        $activites = $bdd->query('SELECT * FROM journal_activite ORDER BY id DESC LIMIT 8')->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Throwable $e) {
    // Une table manquante ne doit pas casser le tableau de bord
}

$pct = function ($n, $t) { return $t > 0 ? (int) round(100 * $n / $t) : 0; };
$libelles_sexe = ['M' => 'Masculin', 'F' => 'Féminin'];
$vers_js = function (array $g, array $renomme = []) {
    $labels = [];
    foreach (array_keys($g) as $c) { $labels[] = $renomme[$c] ?? (string) $c; }
    return ['labels' => $labels, 'values' => array_map('intval', array_values($g))];
};
$statsJs = [
    'sexe'     => $vers_js($g_sexe, $libelles_sexe),
    'type'     => $vers_js($g_type),
    'centre'   => $vers_js($g_centre),
    'parcours' => $vers_js($g_parcours),
];
$libelles_actions = [
    'connexion' => 'Connexion', 'connexion_echec' => 'Échec de connexion', 'deconnexion' => 'Déconnexion',
    'candidat_ajout' => 'Candidat ajouté', 'candidat_modif' => 'Candidat modifié', 'notes_saisie' => 'Notes enregistrées',
    'salle_changement' => 'Changement de salle', 'utilisateur_ajout' => 'Utilisateur ajouté',
    'utilisateur_suppr' => 'Utilisateur supprimé', 'annee_ajout' => 'Année créée',
];
$e = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
?>

<!-- ===================== INDICATEURS DE L'ANNÉE ===================== -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Dossiers contrôlés</span>
        <span class="info-box-number"><?= $k['controles'] ?> / <?= $k['total'] ?></span>
        <div class="progress"><div class="progress-bar bg-success" style="width: <?= $pct($k['controles'], $k['total']) ?>%"></div></div>
        <span class="progress-description"><?= $pct($k['controles'], $k['total']) ?> % du total</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-info"><i class="fas fa-door-open"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Affectés à une salle</span>
        <span class="info-box-number"><?= $k['affectes'] ?> / <?= $k['total'] ?></span>
        <div class="progress"><div class="progress-bar bg-info" style="width: <?= $pct($k['affectes'], $k['total']) ?>%"></div></div>
        <span class="progress-description"><?= $pct($k['affectes'], $k['total']) ?> % du total</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-warning"><i class="fas fa-edit"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Candidats notés</span>
        <span class="info-box-number"><?= $avec_notes ?> / <?= $k['total'] ?></span>
        <div class="progress"><div class="progress-bar bg-warning" style="width: <?= $pct($avec_notes, $k['total']) ?>%"></div></div>
        <span class="progress-description"><?= $pct($avec_notes, $k['total']) ?> % du total</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-danger"><i class="fas fa-venus"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Part de candidates</span>
        <span class="info-box-number"><?= $pct($k['femmes'], $k['total']) ?> %</span>
        <div class="progress"><div class="progress-bar bg-danger" style="width: <?= $pct($k['femmes'], $k['total']) ?>%"></div></div>
        <span class="progress-description"><?= $k['femmes'] ?> femme(s) sur <?= $k['total'] ?></span>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-3">
    <div class="card"><div class="card-header"><h3 class="card-title">Répartition par sexe</h3></div>
      <div class="card-body"><div style="position:relative;height:240px"><canvas id="statSexe"></canvas></div></div></div>
  </div>
  <div class="col-md-3">
    <div class="card"><div class="card-header"><h3 class="card-title">Type de candidat</h3></div>
      <div class="card-body"><div style="position:relative;height:240px"><canvas id="statType"></canvas></div></div></div>
  </div>
  <div class="col-md-6">
    <div class="card"><div class="card-header"><h3 class="card-title">Candidats par centre</h3></div>
      <div class="card-body"><div style="position:relative;height:240px"><canvas id="statCentre"></canvas></div></div></div>
  </div>
</div>

<div class="row">
  <div class="<?= $est_admin_dash ? 'col-md-8' : 'col-md-12' ?>">
    <div class="card"><div class="card-header"><h3 class="card-title">Candidats par parcours (1er choix, 10 premiers)</h3></div>
      <div class="card-body"><div style="position:relative;height:260px"><canvas id="statParcours"></canvas></div></div></div>
  </div>
  <?php if ($est_admin_dash) { ?>
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Dernières activités</h3>
        <div class="card-tools"><a href="journal.php" class="btn btn-tool" title="Voir le journal complet"><i class="fas fa-external-link-alt"></i></a></div>
      </div>
      <div class="card-body p-0">
        <?php if (!$activites) { ?>
          <p class="text-muted m-3">Aucune activité enregistrée.</p>
        <?php } else { ?>
          <ul class="list-group list-group-flush">
            <?php foreach ($activites as $a) { ?>
              <li class="list-group-item py-2" style="font-size:.85rem">
                <strong><?= $e($libelles_actions[$a['action']] ?? $a['action']) ?></strong>
                <span class="float-right text-muted"><?= $e(date('d/m H:i', strtotime($a['created_at']))) ?></span><br>
                <span class="text-muted"><?= $e($a['nom_utilisateur']) ?><?= $a['details'] !== null && $a['details'] !== '' ? ' : ' . $e(mb_strimwidth($a['details'], 0, 60, '...')) : '' ?></span>
              </li>
            <?php } ?>
          </ul>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>
</div>

<!-- ===================== SÉLECTEURS ===================== -->

<?php

function renderYearSelectors($idPrefix, $annees)
{
    echo '<select id="'.$idPrefix.'Start" class="form-select d-inline-block w-auto">';
    echo '<option value="">Début</option>';

    foreach ($annees as $a) {
        echo "<option value='$a'>$a</option>";
    }

    echo '</select>';

    echo '<select id="'.$idPrefix.'End" class="form-select d-inline-block w-auto ms-2">';
    echo '<option value="">Fin</option>';

    foreach ($annees as $a) {
        echo "<option value='$a'>$a</option>";
    }

    echo '</select>';

    echo '<select id="'.$idPrefix.'Range" class="form-select d-inline-block w-auto ms-2">';
    echo '<option value="all">Toutes les années</option>';
    echo '<option value="5">5 dernières années</option>';
    echo '<option value="10">10 dernières années</option>';
    echo '<option value="15">15 dernières années</option>';
    echo '</select>';
}
?>

<!-- ===================== GLOBAL ===================== -->

<div class="card mt-5">

    <div class="card-header bg-light d-flex justify-content-between align-items-center">

        <h3 class="card-title">
            <i class="fas fa-chart-line"></i>
            Évolution globale du nombre de candidats
        </h3>

        <div>
            <?php renderYearSelectors('global', $annees); ?>
        </div>

    </div>

    <div class="card-body">
        <div id="chartGlobalContainer" style="height:400px;">
        <canvas id="chartGlobal"></canvas>
</div>
    </div>

</div>

<!-- ===================== NIVEAU ===================== -->

<div class="card mt-4">

    <div class="card-header bg-light d-flex justify-content-between align-items-center">

        <h3 class="card-title">
            <i class="fas fa-graduation-cap"></i>
            Évolution des candidats par niveau
        </h3>

        <div>

            <select id="selectNiveau" class="form-select d-inline-block w-auto">

                <option value="all">Tous les niveaux</option>

                <?php
                foreach ($niveaux as $n) {
                    echo "<option value='$n'>$n</option>";
                }
                ?>

            </select>

            <?php renderYearSelectors('niveau', $annees); ?>

        </div>

    </div>

    <div class="card-body">
        <div id="chartNiveauContainer" style="height:400px;">
          <canvas id="chartNiveau"></canvas>
        </div>
    </div>

</div>

<!-- ===================== CENTRE ===================== -->

<div class="card mt-4">

    <div class="card-header bg-light d-flex justify-content-between align-items-center">

        <h3 class="card-title">
            <i class="fas fa-map-marker-alt"></i>
            Évolution des candidats par centre
        </h3>

        <div>

            <select id="selectCentre" class="form-select d-inline-block w-auto">

                <option value="all">Tous les centres</option>

                <?php
                foreach ($centres as $c) {
                    echo "<option value='$c'>$c</option>";
                }
                ?>

            </select>

            <?php renderYearSelectors('centre', $annees); ?>

        </div>

    </div>

    <div class="card-body">
        <div id="chartCentreContainer" style="height:400px;">
            <canvas id="chartCentre"></canvas>
</div>
    </div>

</div>

<!-- ===================== ECOLE ===================== -->

<div class="card mt-4">

    <div class="card-header bg-light d-flex justify-content-between align-items-center">

        <h3 class="card-title">
            <i class="fas fa-university"></i>
            Évolution des candidats par école
        </h3>

        <div>

            <select id="selectEcole" class="form-select d-inline-block w-auto">

                <option value="all">Toutes les écoles</option>

                <?php
                foreach ($ecoles as $e) {
                    echo "<option value='$e'>$e</option>";
                }
                ?>

            </select>

            <?php renderYearSelectors('ecole', $annees); ?>

        </div>

    </div>

    <div class="card-body">
        <div id="chartEcoleContainer" style="height:400px;">
    <canvas id="chartEcole"></canvas>
</div>
    </div>

</div>

<!-- ===================== PARCOURS ===================== -->

<div class="card mt-4 mb-5">

    <div class="card-header bg-light d-flex justify-content-between align-items-center">

        <h3 class="card-title">
            <i class="fas fa-route"></i>
            Évolution des candidats par parcours
        </h3>

        <div>

            <select id="selectParcours" class="form-select d-inline-block w-auto">

                <option value="all">Tous les parcours</option>

                <?php
                foreach ($parcoursList as $p) {
                    echo "<option value='$p'>$p</option>";
                }
                ?>

            </select>

            <?php renderYearSelectors('parcours', $annees); ?>

        </div>

    </div>

    <div class="card-body">
        <div id="chartParcoursContainer" style="height:400px;">
            <canvas id="chartParcours"></canvas>
        </div>
    </div>

</div>

<!-- ===================== SCRIPTS ===================== -->

<script src="plugins/chart.js/Chart.min.js"></script>

<script src="plugins/jquery-knob/jquery.knob.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$(function () {
    $('.knob').knob();
});

const rawData = <?= json_encode($rows) ?>;

const allYears = [...new Set(rawData.map(r => r.annee))].sort();

function filterData(start, end, range)
{
    let years = [...allYears];

    if (range !== 'all') {
        years = years.slice(-parseInt(range));
    }

    if (start) {
        years = years.filter(y => y >= start);
    }

    if (end) {
        years = years.filter(y => y <= end);
    }

    return years;
}

function groupData(field, filterKey = null, filterValue = 'all')
{
    const grouped = {};

    rawData.forEach(r => {

        if (
            filterKey &&
            filterValue !== 'all' &&
            r[filterKey] !== filterValue
        ) {
            return;
        }

        const key = r[field] || 'Inconnu';

        if (!grouped[key]) {
            grouped[key] = {};
        }

        grouped[key][r.annee] =
            (grouped[key][r.annee] || 0) + parseInt(r.total);
    });

    return grouped;
}

function buildChart(id, grouped, years)
{
    const ctx = document.getElementById(id).getContext('2d');

    const colors = [
        '#007bff',
        '#28a745',
        '#ffc107',
        '#dc3545',
        '#6f42c1',
        '#17a2b8',
        '#fd7e14',
        '#20c997',
        '#6610f2',
        '#e83e8c',
        '#adb5bd'
    ];

    const datasets = Object.keys(grouped).map((k, i) => ({

        label: k,

        data: years.map(y => grouped[k][y] || 0),

        borderColor: colors[i % colors.length],

        backgroundColor: colors[i % colors.length],

        fill: false,

        tension: 0.3,

        borderWidth: 3,

        pointRadius: 4,

        pointHoverRadius: 6
    }));

    new Chart(ctx, {

        type: 'line',

        data: {
            labels: years,
            datasets: datasets
        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            // ====================================
            // CHART.JS VERSION 2
            // ====================================

            legend: {

                display: true,

                position: 'bottom',

                align: 'center',

                labels: {

                    padding: 20,

                    boxWidth: 30,

                    fontSize: 13
                }
            },

            layout: {

                padding: {
                    top: 10,
                    bottom: 10
                }
            },

            scales: {

                yAxes: [{

                    ticks: {

                        beginAtZero: true,

                        stepSize: 100
                    }
                }]
            }
        }
    });
}

function renderChart(prefix, field, filterKey = null, selectId = null)
{
    const startSel = '#' + prefix + 'Start';
    const endSel = '#' + prefix + 'End';
    const rangeSel = '#' + prefix + 'Range';

    const canvasId =
        'chart' +
        prefix.charAt(0).toUpperCase() +
        prefix.slice(1);

    function render()
    {
        const start = $(startSel).val();
        const end = $(endSel).val();
        const range = $(rangeSel).val();

        const years = filterData(start, end, range);

        const filterValue =
            selectId ? $('#' + selectId).val() : 'all';

        // ===============================
        // SUPPRESSION ANCIEN CANVAS
        // ===============================

        $('#' + canvasId).remove();

        // ===============================
        // NOUVEAU CANVAS
        // ===============================

        $('#' + canvasId + 'Container').html(
            '<canvas id="' + canvasId + '"></canvas>'
        );

        buildChart(
            canvasId,
            groupData(field, filterKey, filterValue),
            years
        );
    }

    $(startSel + ',' + endSel + ',' + rangeSel +
      (selectId ? ',#' + selectId : ''))
      .on('change', render);

    render();
}

$(function () {

    renderChart('global', null);

    renderChart(
        'niveau',
        'niveau',
        'niveau',
        'selectNiveau'
    );

    renderChart(
        'centre',
        'nom_centre',
        'nom_centre',
        'selectCentre'
    );

    renderChart(
        'ecole',
        'ecole',
        'ecole',
        'selectEcole'
    );

    renderChart(
        'parcours',
        'parcours1',
        'parcours1',
        'selectParcours'
    );
});

</script>

<script>
(function () {
  var couleurs = ['#17a2b8','#28a745','#ffc107','#dc3545','#6f42c1','#fd7e14','#20c997','#007bff','#e83e8c','#6c757d'];
  function dessiner(id, type, donnees) {
    var el = document.getElementById(id);
    if (!el) { return; }
    if (!donnees.values.length) {
      el.parentNode.innerHTML = '<p class="text-muted text-center mt-5">Aucune donnée</p>';
      return;
    }
    var circulaire = (type === 'doughnut');
    new Chart(el.getContext('2d'), {
      type: type,
      data: { labels: donnees.labels, datasets: [{ data: donnees.values, backgroundColor: circulaire ? couleurs : '#17a2b8' }] },
      options: {
        maintainAspectRatio: false,
        legend: { display: circulaire, position: 'bottom' },
        scales: circulaire ? {} : { yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }] }
      }
    });
  }
  var d = <?= json_encode($statsJs, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
  dessiner('statSexe', 'doughnut', d.sexe);
  dessiner('statType', 'doughnut', d.type);
  dessiner('statCentre', 'bar', d.centre);
  dessiner('statParcours', 'bar', d.parcours);
})();
</script>

<?php

$content = ob_get_clean();

$menu = "Tableau de bord";

require('template.php');

} else {

    header("Location:login.php");

    exit;
}
?>