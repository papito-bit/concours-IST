<?php
ob_start();

session_start();
if(isset($_SESSION['id_utilisateur']) AND isset($_SESSION['email_concours'])){


require'connect.php';
$nbts=$bdd->query('SELECT COUNT(*) AS nbdts FROM candidats WHERE niveau="DTS"');
$nbrdts=$nbts->fetch();

$nbtss=$bdd->query('SELECT COUNT(*) AS nbdtss FROM candidats WHERE niveau="DTSS"');
$nbrdtss=$nbtss->fetch();

$nbing=$bdd->query('SELECT COUNT(*) AS nbing FROM candidats WHERE niveau="INGENIORAT"');
$nbring=$nbing->fetch();
?>





        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $nbrdts['nbdts']; ?></h3>

                <p>Candidat DTS</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="effectif_niveau.php" class="small-box-footer">Plus d'info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $nbrdtss['nbdtss']; ?><sup style="font-size: 20px">   </sup></h3>

                <p>Candidat DTSS</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="effectif_niveau.php" class="small-box-footer">Plus d'info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $nbring['nbing']; ?></h3>

                <p>Candidat Ingéniorat</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="effectif_niveau.php" class="small-box-footer">Plus d'info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo $nbrdts['nbdts']+$nbrdtss['nbdtss']+$nbring['nbing']; ?></h3>

                <p>Total des candidats</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">Plus d'info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>






<?php

$nb_dts=$bdd->query('SELECT COUNT(*) AS nb_dts_total FROM candidats WHERE niveau="DTS"');
$nb_dts_total=$nb_dts->fetch();

$nb1=$bdd->query('SELECT COUNT(*) AS nb_dts_egi FROM candidats WHERE niveau="DTS" AND ecole="EGI"');
$nb_dts_egi=$nb1->fetch();

$nb2=$bdd->query('SELECT COUNT(*) AS nb_dts_egcgn FROM candidats WHERE niveau="DTS" AND ecole="EGCGN"');
$nb_dts_egcgn=$nb2->fetch();

$nb3=$bdd->query('SELECT COUNT(*) AS nb_dts_egmcs FROM candidats WHERE niveau="DTS" AND ecole="EGMCS"');
$nb_dts_egmcs=$nb3->fetch();

$nb_dtss=$bdd->query('SELECT COUNT(*) AS nb_dtss_total FROM candidats WHERE niveau="DTSS"');
$nb_dtss_total=$nb_dtss->fetch();

$nb4=$bdd->query('SELECT COUNT(*) AS nb_dtss_egi FROM candidats WHERE niveau="DTSS" AND ecole="EGI"');
$nb_dtss_egi=$nb4->fetch();

$nb5=$bdd->query('SELECT COUNT(*) AS nb_dtss_egcgn FROM candidats WHERE niveau="DTSS" AND ecole="EGCGN"');
$nb_dtss_egcgn=$nb5->fetch();

$nb3=$bdd->query('SELECT COUNT(*) AS nb_dtss_egmcs FROM candidats WHERE niveau="DTSS" AND ecole="EGMCS"');
$nb_dtss_egmcs=$nb3->fetch();

?>
        <div class="row">
          <div class="col-12">
            <!-- jQuery Knob -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Effectif des candidats DTS et DTSS par école
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-6 col-md-3 text-center">
                    <input type="text" class="knob" value="<?php echo $nb_dts_egi['nb_dts_egi']; ?>" data-width="90" data-max="<?php echo $nb_dts_total['nb_dts_total']; ?>" data-height="90" data-fgColor="#3c8dbc" readonly>

                    <div class="knob-label">DTS EGI</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-6 col-md-3 text-center">
                    <input type="text" class="knob" value="<?php echo $nb_dts_egcgn['nb_dts_egcgn']; ?>" data-width="90" data-height="90" data-max="<?php echo $nb_dts_total['nb_dts_total']; ?>" data-fgColor="#f56954" readonly>

                    <div class="knob-label">DTS EGCGN</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-6 col-md-3 text-center">
                    <input type="text" class="knob" value="<?php echo $nb_dtss_egi['nb_dtss_egi']; ?>" data-min="0" data-max="<?php echo $nb_dtss_total['nb_dtss_total']; ?>" data-width="90"
                           data-height="90" data-fgColor="#00a65a" readonly>

                    <div class="knob-label">DTSS EGI</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-6 col-md-3 text-center">
                    <input type="text" class="knob" value="<?php echo $nb_dtss_egcgn['nb_dtss_egcgn']; ?>" data-min="0" data-max="<?php echo $nb_dtss_total['nb_dtss_total']; ?>" data-width="90" data-height="90" data-fgColor="#00c0ef" readonly>

                    <div class="knob-label">DTSS EGCGN</div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->

                <div class="row">
                  <div class="col-6 text-center">
                    <input type="text" class="knob" value="<?php echo $nb_dts_egmcs['nb_dts_egmcs']; ?>" data-width="90" data-height="90" data-max="<?php echo $nb_dts_total['nb_dts_total']; ?>" data-fgColor="#932ab6" readonly>

                    <div class="knob-label"> DTS EGMCS</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-6 text-center">
                    <input type="text" class="knob" value="<?php echo $nb_dtss_egmcs['nb_dtss_egmcs']; ?>" data-min="0" data-max="<?php echo $nb_dtss_total['nb_dtss_total']; ?>" data-width="90" data-height="90" data-fgColor="#39CCCC" readonly>

                    <div class="knob-label">DTSS EGMCS</div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>

<div class="row">
<div class="col-lg-3 col-6">
<button type="button" class="btn btn-block bg-gradient-primary btn-lg"><a style="color:white;" href="effectif_niveau.php">Effectif par niveau</a></button>
</div>
<div class="col-lg-3 col-6">
<button type="button" class="btn btn-block bg-gradient-primary btn-lg"><a style="color:white;" href="effectif_centre.php">Effectif par centre DTS</a></button>
</div>
<div class="col-lg-3 col-6">
<button type="button" class="btn btn-block bg-gradient-primary btn-lg"><a style="color:white;" href="effectif_centre_dtss.php">Effectif par centre DTSS</a></button>
</div>
<div class="col-lg-3 col-6">
<button type="button" class="btn btn-block bg-gradient-primary btn-lg">Effectif par centre ING</button>
</div>
</div>
<p></p>




<?php $content = ob_get_clean();
$menu = "Tableau de bord";
$menu_stat=""; 
?>
<?php require('template.php'); ?>

<?php }else{header("Location:login.php");} ?>