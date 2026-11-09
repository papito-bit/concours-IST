<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Concours IST-D 2025-2026</title>

  <!-- Google Font: Source Sans Pro -->
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
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader 
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>-->

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Accueil</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="deconnect.php" class="nav-link">Deconnexion</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>





      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Concours</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="index.php" class="d-block"><?php echo $_SESSION['nom']." ".$_SESSION['prenom'] ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form 
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>-->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      



<!-- ATAO ATO MENU  agnaty nav ndrek ul-->

          <li class="nav-item">
            <a href="index.php" class="nav-link <?php if (isset($menu_stat)) echo "active"; ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p class="text">Statistiques</p>
            </a>
          </li>


<?php if ($_SESSION['groupe']=="admin" OR $_SESSION['groupe']=="operateur" OR $_SESSION['groupe']=="superadmin") {?>

<?php if ($_SESSION['niveau']=="1" OR $_SESSION['niveau']=="ALL") {?>

          <li class=" <?php if (isset($menu_saisi)) {echo $menu_saisi;} else echo "nav-item"; ?> ">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Saisie DTS
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
<?php if ($_SESSION['ecole']=="EGI" OR $_SESSION['ecole']=="ALL") {?>
              <li class="nav-item">
                <a href="saisi_dts.php?ecole=EGI" class="<?php if (isset($menu_dts_egi)) {echo $menu_dts_egi;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>EGI</p>
                </a>
              </li>
<?php } ?>

<?php if ($_SESSION['ecole']=="EGCGN" OR $_SESSION['ecole']=="ALL") {?>
              <li class="nav-item">
                <a href="saisi_dts.php?ecole=EGCGN" class="<?php if (isset($menu_dts_egcgn)) {echo $menu_dts_egcgn;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>EGCGN</p>
                </a>
              </li>
<?php } ?>
<?php if ($_SESSION['ecole']=="EGMCS" OR $_SESSION['ecole']=="ALL") {?>
              <li class="nav-item">
                <a href="saisi_dts.php?ecole=EGMCS" class="<?php if (isset($menu_dts_egmcs)) {echo $menu_dts_egmcs;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>EGMCS</p>
                </a>
              </li>
<?php } ?>
            </ul>
          </li>
<?php } ?>



<?php if ($_SESSION['niveau']=="2" OR $_SESSION['niveau']=="ALL") {?>

          <li class=" <?php if (isset($menu_saisi_dtss)) {echo $menu_saisi_dtss;} else echo "nav-item"; ?> ">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Saisie DTSS
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
<?php if ($_SESSION['ecole']=="EGI" OR $_SESSION['ecole']=="ALL") {?>
              <li class="nav-item">
                <a href="saisi_dtss.php?ecole=EGI" class="<?php if (isset($menu_dtss_egi)) {echo $menu_dtss_egi;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>EGI</p>
                </a>
              </li>
<?php } ?>

<?php if ($_SESSION['ecole']=="EGCGN" OR $_SESSION['ecole']=="ALL") {?>
              <li class="nav-item">
                <a href="saisi_dtss.php?ecole=EGCGN" class="<?php if (isset($menu_dtss_egcgn)) {echo $menu_dtss_egcgn;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>EGCGN</p>
                </a>
              </li>
<?php } ?>

<?php if ($_SESSION['ecole']=="EGMCS" OR $_SESSION['ecole']=="ALL") {?>
              <li class="nav-item">
                <a href="saisi_dtss.php?ecole=EGMCS" class="<?php if (isset($menu_dtss_egmcs)) {echo $menu_dtss_egmcs;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>EGMCS</p>
                </a>
              </li>
            </ul>
          </li>
<?php } ?>
<?php } ?>


<?php if ($_SESSION['niveau']=="2" OR $_SESSION['niveau']=="ALL") {?>
          <li class=" <?php if (isset($menu_saisi_ing)) {echo $menu_saisi_ing;} else echo "nav-item"; ?> ">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Saisie INGENIORAT
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

<?php if ($_SESSION['ecole']=="EGI" OR $_SESSION['ecole']=="ALL") {?>
              <li class="nav-item">
                <a href="saisi_ing.php?ecole=EGI" class="<?php if (isset($menu_ing_egi)) {echo $menu_ing_egi;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>EGI</p>
                </a>
              </li>
<?php } ?>

<?php if ($_SESSION['ecole']=="EGCGN" OR $_SESSION['ecole']=="ALL") {?>
              <li class="nav-item">
                <a href="saisi_ing.php?ecole=EGCGN" class="<?php if (isset($menu_ing_egcgn)) {echo $menu_ing_egcgn;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>EGCGN</p>
                </a>
              </li>
<?php } ?>

<?php if ($_SESSION['ecole']=="EGMCS" OR $_SESSION['ecole']=="ALL") {?>
              <li class="nav-item">
                <a href="saisi_ing.php?ecole=EGMCS" class="<?php if (isset($menu_ing_egmcs)) {echo $menu_ing_egmcs;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>EGMCS</p>
                </a>
              </li>
            </ul>
          </li>
<?php } ?>
<?php } ?>


          <li class="nav-item">
            <a href="liste_saisi.php" class="nav-link">
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                Liste des candidats
                <span class="badge badge-info right"><?php  ?></span>
              </p>
            </a>
          </li>

<?php } ?>

<?php if ($_SESSION['groupe']=="admin" or $_SESSION['groupe']=="controleur" OR $_SESSION['groupe']=="superadmin") {?>
            <li class="<?php if (isset($menu_convoc)) {echo $menu_convoc;} else echo "nav-item"; ?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Convocation
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right"></span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="convocation.php" class="<?php if (isset($liste_convoc)) {echo $liste_convoc;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Liste convocation</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="convocation_a_imprimer.php" class="<?php if (isset($convoc_a_impr)) {echo $convoc_a_impr;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>A imprimer</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="convoc_dts_egi.php" class="<?php if (isset($convoc_dts_egi)) {echo $convoc_dts_egi;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>DTS EGI</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="convoc_dts_egcgn.php" class="<?php if (isset($convoc_dts_egcgn)) {echo $convoc_dts_egcgn;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>DTS EGCGN</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="convoc_dts_egmcs.php" class="<?php if (isset($convoc_dts_egmcs)) {echo $convoc_dts_egmcs;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>DTS EGMCS</small></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="convoc_dtss.php" class="<?php if (isset($convoc_dtss)) {echo $convoc_dtss;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>DTSS</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="convoc_ingeniorat.php" class="<?php if (isset($convoc_ingeniorat)) {echo $convoc_ingeniorat;} else echo "nav-link"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>INGENIORAT</p>
                </a>
              </li>
            </ul>
          </li>


            <li class="<?php if (isset($menu_presence)) {echo $menu_presence;} else echo "nav-item"; ?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Fiche de présence
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right"></span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="liste_presence.php" class="nav-link <?php if (isset($menu_presence)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>DTS</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="liste_presence_dtss.php" class="nav-link <?php if (isset($menu_fiche_dtss)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>DTSS et ING</p>
                </a>
              </li>
<!--              <li class="nav-item">
                <a href="liste_presence_ingeniorat.php" class="nav-link <?php if (isset($menu_fiche_ing)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>INGENIORAT</p>
                </a>
              </li>
-->
            </ul>
          </li>



        <li class="<?php if (isset($menu_liste)) {echo $menu_liste;} else echo "nav-item"; ?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Liste par centre/jury
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right"></span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="liste_liste.php" class="nav-link <?php if (isset($menu_liste_dts)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>DTS</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="liste_liste_dtss.php" class="nav-link <?php if (isset($menu_liste_liste_dtss)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>DTSS</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="liste_liste_ingeniorat.php" class="nav-link <?php if (isset($menu_liste_liste_ingeniorat)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>INGENIORAT</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="liste_dtss_jury.php" class="nav-link <?php if (isset($menu_liste_dtss_jury)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Liste par jury</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="presence_dtss_jury.php" class="nav-link <?php if (isset($menu_presence_dtss_jury)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Présence par jury</p>
                </a>
              </li>

            </ul>
          </li>

<?php } ?>
<?php if ($_SESSION['groupe']=="admin" OR $_SESSION['groupe']=="superadmin") {?>



                      <li class="<?php if (isset($menu_salle)) {echo $menu_salle;} else echo "nav-item"; ?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Salle
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right"></span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="liste_salle.php" class="nav-link <?php if (isset($menu_salle_liste)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Liste par salle</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="liste_niveau.php" class="nav-link <?php if (isset($menu_salle_niveau)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Liste par parcours</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="liste_salle_2nd_cycle.php" class="nav-link <?php if (isset($menu_salle_2nd)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Liste par salle 2nd cycle</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="effectif_salle.php" class="nav-link <?php if (isset($menu_effec_salle)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Effectif par salle</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="liste_modif_salle.php" class="nav-link <?php if (isset($m)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Modifier par salle</p>
                </a>
              </li>

            </ul>
          </li>
<?php } ?>
<?php if ($_SESSION['groupe']=="superadmin") {?>

          <li class="nav-item">
            <a href="liste_etiquette.php" class="nav-link <?php if (isset($menu_etiquette)) {echo "active";} ?>">
              <i class="fas fa-circle nav-icon"></i>
              <p class="text">Etiquette</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="liste_etiquette_cycle.php" class="nav-link <?php if (isset($menu_etiquette_cycle)) {echo "active";} ?>">
              <i class="fas fa-circle nav-icon"></i>
              <p class="text">Etiquette 2nd</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="centre_affiche.php" class="nav-link <?php if (isset($menu_centre_affiche)) {echo "active";} ?>">
              <i class="fas fa-circle nav-icon"></i>
              <p class="text">Centre Affiche</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="utilisateur.php" class="nav-link <?php if (isset($menu_utili)) {echo "active";} ?>">
              <i class="fas fa-circle nav-icon"></i>
              <p class="text">Utilisateur</p>
            </a>
          </li>

            <li class="nav-item">
            <a href="acces_salle.php" class="nav-link <?php if (isset($menu_acces_salle)) {echo "active";} ?>">
              <i class="fas fa-circle nav-icon"></i>
              <p class="text">Répartissez par salle</p>
            </a>
          </li>

            <li class="nav-item">
            <a href="acces_salle_2nd_cycle.php" class="nav-link <?php if (isset($menu_acces_salle)) {echo "active";} ?>">
              <i class="fas fa-circle nav-icon"></i>
              <p class="text">Répartissez salle second</p>
            </a>
          </li>
          <li class="<?php if (isset($menu_presence)) {echo $menu_presence;} else echo "nav-item"; ?>">
<?php } ?>
<?php if ($_SESSION['groupe']=="admin" OR $_SESSION['groupe']=="superadmin") {?>

              <li class="nav-item">
                <a href="delib.php" class="nav-link <?php if (isset($menu_delib)) echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>DELIBERATION</p>
                </a>
              </li>













<?php } ?>


    </ul>
    </nav>
      <!-- /.sidebar-menu -->





    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?php echo $menu; ?></h1>
          </div><!-- /.col -->


<!--
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="saisi_dts_egi.php">EGI</a></li>
              <li class="breadcrumb-item"><a href="saisi_dts_egcgn.php">EGCGN</a></li>
              <li class="breadcrumb-item"><a href="saisi_dts_egmcs.php">EGMCS</a></li>
            </ol>
          </div>
-->


          <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
    <?= $content ?>
</div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>IST Antsiranana 2025</a></strong>
    
    <div class="float-right d-none d-sm-inline-block">
      <b>Concours </b> 2025-2026
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<script>

$( document ).ready(function() {
    console.log( "ready!" );
});
    console.log("we are here")
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
    <?php

    if(isset( $_SESSION['message'])){?>
    Toast.fire({
        icon: 'success',
        title: '<?php echo  $_SESSION["message"]; ?>'
      });  
 <?php
    }
    $_SESSION['message']=null;

     ?>
   




</script>
</body>
</html>