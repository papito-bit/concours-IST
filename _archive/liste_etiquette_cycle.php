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
                          Salle
                      </th>
                      <th style="">
                          Capacité
                      </th>
                      <th style="">
                          Effectif
                      </th>

                      <th style="">
                        
                      </th>
                  </tr>
              </thead>
              <tbody>
                <?php
                $requette=$bdd->query('SELECT * FROM salle');
                $i=0;
                while ($donnees=$requette->fetch()) {
                $i=$i+1;
                ?>
                                <tr>
                      <td>
                        <?php echo $donnees["id_salle"] ?>
                      </td>
                      <td>
                        <?php echo $donnees["capacite"] ?>
                      </td>
                      <td>
                        <?php //echo $donnees["email"] ?>
                      </td>
                      <td class="project_progress">
                        <?php //echo $donnees["mdp"] ?>
                      </td>
                      <td class="project-state">
                        <?php //echo $donnees["groupe"] ?>
                      </td>
                      <td class="project-state">
                        <?php //echo $donnees["groupe"] ?>
                      </td>
                      <td class="project-actions text-right">
                          <a class="btn btn-primary btn-sm" href="etiquette_cycle_orale.php?id=<?php echo $donnees["id_salle"]; ?>">
                              <i class="fas fa-folder">
                              </i>
                              Afficher ORALE
                          </a>
                      </td>

                      <td class="project-actions text-right">
                          <a class="btn btn-primary btn-sm" href="etiquette_cycle.php?id=<?php echo $donnees["id_salle"]; ?>">
                              <i class="fas fa-folder">
                              </i>
                              Afficher ECRITE
                          </a>
                      </td>
                  </tr>
                <?php
                }
                ?>




                <?php
                $requette_centre=$bdd->query('SELECT * FROM centre WHERE actif="OUI"');
                $i=0;
                while ($donnees=$requette_centre->fetch()) {
                $i=$i+1;
                ?>
                                <tr>
                      <td>
                        <?php echo $donnees["nom_centre"]; ?>
                      </td>
                      <td>
                        <?php  ?>
                      </td>
                      <td>
                        <?php //echo $donnees["nom_centre"]; ?>
                      </td>

                      <td class="project-actions text-right">
                          <a class="btn btn-primary btn-sm" href="etiquette_cycle_orale.php?centre=<?php echo $donnees["nom_centre"]; ?>">
                              <i class="fas fa-folder">
                              </i>
                              Afficher ORALE
                          </a>
                      </td>

                      <td class="project-actions text-right">
                          <a class="btn btn-primary btn-sm" href="etiquette_cycle.php?centre=<?php echo $donnees["nom_centre"]; ?>">
                              <i class="fas fa-folder">
                              </i>
                              Afficher ECRITE
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
$menu = "Etiquette 2nd Cycle";
$menu_etiquette_cycle="";
//$menu_salle="nav-link active";
//$menu_presence="nav-item menu-open";
?>
<?php require('template.php'); ?>

<?php }else{header("Location:login.php");} ?>