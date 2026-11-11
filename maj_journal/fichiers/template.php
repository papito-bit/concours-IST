<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si pas connecté -> redirect
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/journal_helper.php';
$nb_notif = notifications_non_lues((int) $_SESSION['id_utilisateur']);
$notifs_recentes = notifications_recentes((int) $_SESSION['id_utilisateur'], 5);

// Variables de session
$annees_list = $_SESSION['annees'] ?? [];
$annee_selected_id = $_SESSION['id_annee_selected'] ?? null;
$user_nom = ($_SESSION['nom'] ?? '') . ' ' . ($_SESSION['prenom'] ?? '');
$user_nom = trim($user_nom);

// Helper pour marquer menu actif
$currentFile = basename($_SERVER['PHP_SELF']);
function isActiveByFile($files) {
    global $currentFile;
    if (!is_array($files)) $files = [$files];
    return in_array($currentFile, $files, true) ? 'active' : '';
}
function isMenuOpen($files) {
    global $currentFile;
    if (!is_array($files)) $files = [$files];
    return in_array($currentFile, $files, true) ? 'menu-open' : '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Concours IST-D</title>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">

  <style>
    .navbar .form-control { min-width: 160px; display: inline-block; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link <?= (isset($menu_stat) && $menu_stat === 'active') ? 'active' : '' ?>">Accueil</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="deconnect.php" class="nav-link">Déconnexion</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" title="Notifications">
          <i class="far fa-bell"></i>
          <?php if ($nb_notif > 0) { ?><span class="badge badge-danger navbar-badge"><?= (int) $nb_notif ?></span><?php } ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><?= (int) $nb_notif ?> notification(s) non lue(s)</span>
          <?php foreach ($notifs_recentes as $n) { ?>
            <div class="dropdown-divider"></div>
            <a href="notifications.php?ouvrir=<?= (int) $n['id'] ?>" class="dropdown-item" style="white-space: normal;">
              <i class="fas fa-circle <?= $n['lu'] ? 'text-muted' : 'text-primary' ?> mr-2" style="font-size:.6rem"></i>
              <?= htmlspecialchars($n['message']) ?>
              <span class="d-block text-muted text-sm"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($n['created_at']))) ?></span>
            </a>
          <?php } ?>
          <div class="dropdown-divider"></div>
          <a href="notifications.php" class="dropdown-item dropdown-footer">Voir toutes les notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <select class="form-control" onchange="updateYear(this.value)">
          <?php 
          if (!empty($annees_list)) {
              foreach($annees_list as $annee) {
                $idA = $annee['id_annee'] ?? '';
                $lib = $annee['libelle'] ?? '';
                $selected = ($annee_selected_id == $idA) ? ' selected' : '';
                echo '<option value="'.htmlspecialchars($idA).'"'.$selected.'>'.htmlspecialchars($lib).'</option>';
              }
          } else {
              echo '<option value="">Aucune année</option>';
          }
          ?>
        </select>
      </li>
      &nbsp;&nbsp;
      <li class="nav-item">
        <button type="button" class="btn btn-md bg-gradient-primary text-white" data-toggle="modal" data-target="#modal-new-year">
          <i class="fas fa-plus"></i> Nouvelle année
        </button>
      </li>
    </ul>
  </nav>

<!-- Modal nouvelle année -->
<div class="modal fade" id="modal-new-year" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="new_year.php" method="POST">
        <div class="modal-header">
          <h4 class="modal-title">Nouvelle année scolaire</h4>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label>Année scolaire</label>
            <?php
              $currentYear = null;
              if (!empty($_SESSION['annees']) && isset($_SESSION['id_annee_selected'])) {
                  foreach ($_SESSION['annees'] as $a) {
                      if ($a['id_annee'] == $_SESSION['id_annee_selected']) {
                          $currentYear = $a['libelle'];
                          break;
                      }
                  }
              }
              if ($currentYear && preg_match('/(\d{4})-(\d{4})/', $currentYear, $m)) {
                  $nextYear = ($m[1] + 1) . '-' . ($m[2] + 1);
              } else {
                  $year = date('Y');
                  $nextYear = $year . '-' . ($year + 1);
              }
            ?>
            <input type="text" class="form-control text-center" 
                   name="annee_display"
                   value="<?= htmlspecialchars($nextYear) ?>" 
                   readonly>
            <input type="hidden" name="annee" value="<?= htmlspecialchars($nextYear) ?>">
          </div>
        </div>

        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
          <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity:.8">
      <span class="brand-text font-weight-light">Concours</span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image"><img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User"></div>
        <div class="info"><a href="index.php" class="d-block"><?= htmlspecialchars($user_nom ?: ($_SESSION['email_concours'] ?? 'Utilisateur')) ?></a></div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

          <!-- Statistiques -->
          <li class="nav-item">
            <a href="index.php" class="nav-link <?= (isset($menu_stat) ? $menu_stat : isActiveByFile('index.php')) ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i><p>Statistiques</p>
            </a>
          </li>

          <?php if (in_array($_SESSION['groupe'] ?? '', ['admin','operateur','superadmin'], true)) { ?>
            
            <!-- Saisie DTS -->
            <?php if (($_SESSION['niveau'] ?? '') === "1" || ($_SESSION['niveau'] ?? '') === "ALL") { ?>
            <li class="nav-item <?= isMenuOpen(['saisi_dts.php']) ?>">
              <a href="#" class="nav-link"><i class="nav-icon fas fa-edit"></i><p>Saisie DTS<i class="right fas fa-angle-left"></i></p></a>
              <ul class="nav nav-treeview">
                <li class="nav-item"><a href="saisi_dts.php?ecole=EGI" class="nav-link <?= isActiveByFile('saisi_dts.php') ?>"><i class="far fa-circle nav-icon"></i><p>EGI</p></a></li>
                <li class="nav-item"><a href="saisi_dts.php?ecole=EGCGN" class="nav-link <?= isActiveByFile('saisi_dts.php') ?>"><i class="far fa-circle nav-icon"></i><p>EGCGN</p></a></li>
                <li class="nav-item"><a href="saisi_dts.php?ecole=EGMCS" class="nav-link <?= isActiveByFile('saisi_dts.php') ?>"><i class="far fa-circle nav-icon"></i><p>EGMCS</p></a></li>
              </ul>
            </li>
            <?php } ?>

            <!-- Saisie DTSS / ING -->
            <?php if (($_SESSION['niveau'] ?? '') === "2" || ($_SESSION['niveau'] ?? '') === "ALL") { ?>
            <li class="nav-item <?= isMenuOpen(['saisi_dtss.php','saisi_ing.php']) ?>">
              <a href="#" class="nav-link"><i class="nav-icon fas fa-edit"></i><p>Saisie DTSS / INGENIORAT<i class="right fas fa-angle-left"></i></p></a>
              <ul class="nav nav-treeview">
                <li class="nav-item"><a href="saisi_dtss.php?ecole=EGI" class="nav-link <?= isActiveByFile('saisi_dtss.php') ?>"><i class="far fa-circle nav-icon"></i><p>DTSS - EGI</p></a></li>
                <li class="nav-item"><a href="saisi_dtss.php?ecole=EGCGN" class="nav-link <?= isActiveByFile('saisi_dtss.php') ?>"><i class="far fa-circle nav-icon"></i><p>DTSS - EGCGN</p></a></li>
                <li class="nav-item"><a href="saisi_dtss.php?ecole=EGMCS" class="nav-link <?= isActiveByFile('saisi_dtss.php') ?>"><i class="far fa-circle nav-icon"></i><p>DTSS - EGMCS</p></a></li>
                <li class="nav-item"><a href="saisi_ing.php?ecole=EGI" class="nav-link <?= isActiveByFile('saisi_ing.php') ?>"><i class="far fa-circle nav-icon"></i><p>ING - EGI</p></a></li>
                <li class="nav-item"><a href="saisi_ing.php?ecole=EGCGN" class="nav-link <?= isActiveByFile('saisi_ing.php') ?>"><i class="far fa-circle nav-icon"></i><p>ING - EGCGN</p></a></li>
                <li class="nav-item"><a href="saisi_ing.php?ecole=EGMCS" class="nav-link <?= isActiveByFile('saisi_ing.php') ?>"><i class="far fa-circle nav-icon"></i><p>ING - EGMCS</p></a></li>
              </ul>
            </li>
            <?php } ?>

            <li class="nav-item"><a href="liste_saisi.php" class="nav-link <?= isActiveByFile('liste_saisi.php') ?>"><i class="nav-icon far fa-calendar-alt"></i><p>Liste des candidats</p></a></li>

          <?php } ?>

          <?php if (in_array($_SESSION['groupe'] ?? '', ['admin','controleur','superadmin'], true)) { ?>

            <!-- Convocations -->
            <li class="nav-item <?= isMenuOpen(['convocation.php','convoc_dts_egi.php','convoc_dtss.php','convoc_ingeniorat.php','date_concours.php']) ?>">
              <a href="#" class="nav-link"><i class="nav-icon fas fa-copy"></i><p>Convocations<i class="right fas fa-angle-left"></i></p></a>
              <ul class="nav nav-treeview">
                <li class="nav-item"><a href="convocation.php" class="nav-link <?= isActiveByFile('convocation.php') ?>"><i class="far fa-circle nav-icon"></i><p>Liste convocation</p></a></li>
                <li class="nav-item"><a href="convoc_dts_egi.php" class="nav-link <?= isActiveByFile('convoc_dts_egi.php') ?>"><i class="far fa-circle nav-icon"></i><p>DTS</p></a></li>
                <li class="nav-item"><a href="convoc_dtss.php" class="nav-link <?= isActiveByFile('convoc_dtss.php') ?>"><i class="far fa-circle nav-icon"></i><p>DTSS</p></a></li>
                <li class="nav-item"><a href="convoc_ingeniorat.php" class="nav-link <?= isActiveByFile('convoc_ingeniorat.php') ?>"><i class="far fa-circle nav-icon"></i><p>INGENIORAT</p></a></li>
                <!-- ✅ Nouveau lien pour gérer les dates du concours -->
                <li class="nav-item"><a href="date_concours.php" class="nav-link <?= isActiveByFile('date_concours.php') ?>"><i class="far fa-calendar-alt nav-icon"></i><p>Dates du concours</p></a></li>
              </ul>
            </li>

            <!-- Fiche de présence -->
            <li class="nav-item <?= isMenuOpen(['liste_presence.php','liste_presence_dtss.php']) ?>">
              <a href="#" class="nav-link"><i class="nav-icon fas fa-th"></i><p>Fiche de présence<i class="right fas fa-angle-left"></i></p></a>
              <ul class="nav nav-treeview">
                <li class="nav-item"><a href="liste_presence.php" class="nav-link <?= isActiveByFile('liste_presence.php') ?>"><i class="far fa-circle nav-icon"></i><p>DTS</p></a></li>
                <li class="nav-item"><a href="liste_presence_dtss.php" class="nav-link <?= isActiveByFile('liste_presence_dtss.php') ?>"><i class="far fa-circle nav-icon"></i><p>DTSS & INGENIORAT</p></a></li>
              </ul>
            </li>

            <!-- Listes -->
            <li class="nav-item <?= isMenuOpen(['liste_niveau.php','effectif_salle.php','liste_modif_salle.php','affiche_jury.php']) ?>">
              <a href="#" class="nav-link"><i class="nav-icon fas fa-th"></i><p>Listes<i class="right fas fa-angle-left"></i></p></a>
              <ul class="nav nav-treeview">
                <li class="nav-item"><a href="liste_niveau.php" class="nav-link <?= isActiveByFile('liste_niveau.php') ?>"><i class="far fa-circle nav-icon"></i><p>Liste par parcours</p></a></li>
                <li class="nav-item"><a href="effectif_salle.php" class="nav-link <?= isActiveByFile('effectif_salle.php') ?>"><i class="far fa-circle nav-icon"></i><p>Effectif par salle</p></a></li>
                <li class="nav-item"><a href="liste_modif_salle.php" class="nav-link <?= isActiveByFile('liste_modif_salle.php') ?>"><i class="far fa-circle nav-icon"></i><p>Modifier par salle</p></a></li>
                <li class="nav-item"><a href="affiche_jury.php" class="nav-link <?= isActiveByFile('affiche_jury.php') ?>"><i class="far fa-circle nav-icon"></i><p>Afficher les jurys</p></a></li>
              </ul>
            </li>

            <li class="nav-item"><a href="liste_etiquette.php" class="nav-link <?= isActiveByFile('liste_etiquette.php') ?>"><i class="fas fa-circle nav-icon"></i><p>Etiquette</p></a></li>

            <?php if (($_SESSION['groupe'] ?? '') === "superadmin") { ?>
              <li class="nav-item"><a href="utilisateur.php" class="nav-link <?= isActiveByFile('utilisateur.php') ?>"><i class="fas fa-circle nav-icon"></i><p>Utilisateurs</p></a></li>
            <?php } ?>

            <?php if (in_array($_SESSION['groupe'] ?? '', ['admin','superadmin'], true)) { ?>
              <li class="nav-item"><a href="journal.php" class="nav-link <?= isActiveByFile('journal.php') ?>"><i class="fas fa-history nav-icon"></i><p>Journal d'activité</p></a></li>
            <?php } ?>
            <li class="nav-item"><a href="delib.php" class="nav-link <?= isActiveByFile('delib.php') ?>"><i class="far fa-circle nav-icon"></i><p>DELIBERATION</p></a></li>
          
          <?php } ?>

        </ul>
      </nav>
    </div>
  </aside>

  <!-- Contenu principal -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid"><h1 class="m-0"><?= isset($menu) ? htmlspecialchars($menu) : '' ?></h1></div>
    </div>
    <section class="content">
      <div class="container-fluid"><?= isset($content) ? $content : '' ?></div>
    </section>
  </div>

  <footer class="main-footer">
    <strong>IST Antsiranana</strong>
    <div class="float-right d-none d-sm-inline-block">
      <b>Année actuelle :</b>
      <?php 
        if (!empty($annees_list) && $annee_selected_id !== null) {
            foreach ($annees_list as $a) {
                if (($a['id_annee'] ?? '') == $annee_selected_id) {
                    echo htmlspecialchars($a['libelle'] ?? '');
                    break;
                }
            }
        } else echo "Non définie";
      ?>
    </div>
  </footer>
</div>

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="dist/js/adminlte.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  $(function () { $('.knob').knob(); });
  function updateYear(year) {
    if (!year) return;
    let currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('annee', year);
    window.location.href = currentUrl.toString();
  }
</script>

</body>
</html>
