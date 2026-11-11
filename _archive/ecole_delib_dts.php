<?php
ob_start();

session_start();
if($_SESSION['groupe']!="admin" && $_SESSION['groupe']!="superadmin"){header("Location:login.php");}

require('connect.php');
//include 'compl/compte.php';
$requette=$bdd->query('SELECT * FROM utilisateur ORDER BY id_utilisateur');
?>






<table class="table table-bordered text-center">
  <tbody>
                    <td>
                      <a href="liste_niveau.php?id=DTS"><button type="button" class="btn btn-block bg-gradient-primary btn-lg">DTS</button></a>
                    </td>
                    <td>
                      <a href="liste_niveau.php?id=DTSS"><button type="button" class="btn btn-block bg-gradient-primary btn-lg">DTSS</button></a>
                    </td>
                    <td>
                      <a href="liste_niveau.php?id=ING"><button type="button" class="btn btn-block bg-gradient-primary btn-lg">INGENIORAT</button></a>
                    </td>
  </tbody>
</table>


<?php
if (isset($_GET['id'])) {
  $niveau=$_GET['id'];
?>


     <div class="card">
        <div class="card-body p-0">
          <table class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="">
                          Parcours
                      </th>
                      <th style="">
                          Effectif
                      </th>
                      <th style="">
                          ....
                      </th>

                      <th style="">
                        ...
                      </th>
                  </tr>
              </thead>
              <tbody>
                <?php

                $requette=$bdd->prepare('SELECT * FROM parcours WHERE grade_abrevie=? ORDER BY parcours_abrevie');
                $requette->execute(array($niveau));
                $i=0;
                while ($donnees=$requette->fetch()) {
                $i=$i+1;
                ?>
                                <tr>
                      <td>
                        <?php echo $donnees["parcours_abrevie"]; ?>
                      </td>
                      <td>
                        <?php ?>
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
                      <td class="project-actions text-right">
                          <a class="btn btn-primary btn-sm" href="repartition_parcours.php?id=<?php echo $donnees["parcours_abrevie"]; ?>">
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




<?php
}
?>





<?php $content = ob_get_clean();
$menu="Déliberation"; 
$menu_utili=""; 
?>
<?php require('template.php'); ?>
