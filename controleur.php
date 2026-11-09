<?php
ob_start();

session_start();
if(isset($_SESSION['id_utilisateur']) AND isset($_SESSION['email_concours'])){


require('connect.php');
$requette=$bdd->query('SELECT * FROM candidats ORDER BY id_candidat');
?>







      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Controle <a href="controleur.php"> &nbsp Voir tout</a></h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        
        <div class="card-body p-0">
          <table class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 1%">
                          Pos
                      </th>
                      <th style="width: 20%">
                          Nom
                      </th>
                      <th style="width: 20%">
                          Prénoms
                      </th>
                      <th>
                          Centre
                      </th>
                      <th style="width: 8%" class="text-center">
                          Parcours
                      </th>
                      <th style="width: 25%">
                        
                      </th>
                  </tr>
              </thead>
              <tbody>
                <?php
                while ($donnees=$requette->fetch()) {
                
                ?>
                                <tr>
                      <td>
                          <?php echo $donnees["id_candidat"] ?>
                      </td>
                      <td>
                        <?php echo $donnees["nom"] ?>
                      </td>
                      <td>
                        <?php echo $donnees["prenom"] ?>
                      </td>
                      <td>
                        <?php echo $donnees["centre"] ?>
                      </td>
                      <td class="project-state">
                        <?php echo $donnees["parcours1"] ?>
                      </td>
                      <td class="project-actions text-right">
                          <a class="btn btn-<?php if ($donnees["controle"]=="NON") {echo "danger";} else echo "primary";?> btn-sm" href="validation.php<?php echo $donnees["id_candidat"]; ?>">
                              <i class="fas fa-folder">
                              </i>
                              Voir
                          </a>
                      <!--    <a class="btn btn-info btn-sm" href="#">
                              <i class="fas fa-pencil-alt">
                              </i>
                              Modif
                          </a>
                          <a class="btn btn-danger btn-sm" href="#">
                              <i class="fas fa-trash">
                              </i>
                              Suppr
                          </a> -->
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
$menu = "";
$menu_controle="nav-item menu-open";
$liste_controle="nav-link active";
?>
<?php require('template.php'); ?>

<?php }else{header("Location:login.php");} ?>