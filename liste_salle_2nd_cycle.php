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

                $nbdtss=$bdd->prepare('SELECT COUNT(*) AS nombre_dtss FROM candidats WHERE salle=? AND niveau!="DTS" AND centre="Antsiranana"');
                $nbdtss->execute(array($donnees["id_salle"]));
                $nombre_dtss=$nbdtss->fetch();
                
                ?>
                                <tr>
                      <td>
                        <?php echo $donnees["id_salle"] ?>
                      </td>
                      <td>
                        <?php echo $donnees["capacite2"] ?>
                      </td>
                      <td>
                        <?php echo $nombre_dtss["nombre_dtss"] ?>
                      </td>
                      <td class="project_progress">
                        <?php //echo $donnees["mdp"] ?>
                      </td>
                      <td class="project-state">
                        <?php //echo $donnees["groupe"] ?>
                      </td>
                      <td class="project-actions text-right">
                          <a class="btn btn-primary btn-sm" href="repartition_salle_2nd.php?id=<?php echo $donnees["id_salle"]; ?>">
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
$menu = "Repartition des salles 2nd cycle";
$menu_salle_2nd="nav-link active";
?>
<?php require('template.php'); ?>

<?php }else{header("Location:login.php");} ?>