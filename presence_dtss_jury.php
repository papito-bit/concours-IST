<?php
ob_start();

session_start();
if(isset($_SESSION['id_utilisateur']) AND isset($_SESSION['email_concours'])){


require'connect.php';
?>

      <div class="card">
        <div class="card-body p-0">
          <table class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="">
                          Jury
                      </th>
                      <th style="">
                          Capacité
                      </th>
                      <th style="">
                          Effectif
                      </th>

                      <th style="">
                        EGI
                      </th>
                      <th style="">
                        EGCGN
                      </th>
                      <th style="">
                        EGMCS
                      </th>

                  </tr>
              </thead>
              <tbody>
                <?php
                $requette=$bdd->query('SELECT * FROM jury');
                $i=0;
                while ($donnees=$requette->fetch()) {
                $i=$i+1;
                ?>
                                <tr>
                      <td>
                        <?php echo $donnees["id_jury"] ?>
                      </td>
                      <td>
                        <?php echo $donnees["capacite"] ?>
                      </td>
                      <td>
                        <?php //echo $donnees["email"] Effectif ?>
                      </td>
                      <td class="project_progress">
                          <a class="btn btn-primary btn-sm" href="presence_par_jury.php?id=<?php echo $donnees["id_jury"]; ?>&ecole=EGI">
                              <i class="fas fa-folder">
                              </i>
                              Afficher
                          </a>
                      </td>
                      <td class="project-state">
                          <a class="btn btn-primary btn-sm" href="presence_par_jury.php?id=<?php echo $donnees["id_jury"]; ?>&ecole=EGCGN">
                              <i class="fas fa-folder">
                              </i>
                              Afficher
                          </a>
                      </td>
                      <td class="project-actions text-right">
                          <a class="btn btn-primary btn-sm" href="presence_par_jury.php?id=<?php echo $donnees["id_jury"]; ?>&ecole=EGMCS">
                              <i class="fas fa-folder">
                              </i>
                              Afficher
                          </a>
                      </td>
                  </tr>
                <?php
                }
                ?>

              </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>














<?php $content = ob_get_clean();
$menu = "Repartition des jurys";
$menu_presence_dtss_jury="nav-link active";
$menu_liste="nav-item menu-open";
?>
<?php require('template.php'); ?>

<?php }else{header("Location:login.php");} ?>