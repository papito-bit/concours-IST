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



                $requette=$bdd->query('SELECT * FROM salle WHERE etat="OUI" ORDER BY num ASC');
                $i=0;
                while ($donnees=$requette->fetch()) {
                $i=$i+1;

                $nbdts=$bdd->prepare('SELECT COUNT(*) AS nombre_dts FROM candidats WHERE salle=? AND centre="Antsiranana" AND niveau="DTS"');
                $nbdts->execute(array($donnees["id_salle"]));
                $nombre_dts=$nbdts->fetch();
                
                ?>
                                <tr>
                      <td>
                        <?php echo $donnees["id_salle"] ?>
                      </td>
                      <td>
                        <?php echo $donnees["capacite"] ?>
                      </td>
                      <td>
                        <?php echo $nombre_dts["nombre_dts"] ?>
                      </td>
                      <td class="project_progress">
                        <?php //echo $donnees["mdp"] ?>
                      </td>
                      <td class="project-state">
                        <?php //echo $donnees["groupe"] ?>
                      </td>
                      <td class="project-actions text-right">
                          <a class="btn btn-primary btn-sm" href="repartition_salle.php?id=<?php echo $donnees["id_salle"]; ?>">
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
$menu = "Repartition des salles";
$menu_salle_liste="nav-link active";
$menu_salle="nav-item menu-open";
?>
<?php require('template.php'); ?>

<?php }else{header("Location:login.php");} ?>