<?php
ob_start();

session_start();
if($_SESSION['groupe']!="admin" && $_SESSION['groupe']!="superadmin"){header("Location:login.php");}


require('connect.php');
$requette=$bdd->prepare('SELECT * FROM candidats WHERE parcours1=? ORDER BY id_candidat');
$requette->execute(array($_GET['id']));

$requette2=$bdd->prepare('SELECT * FROM parcours WHERE parcours_abrevie=?');
$requette2->execute(array($_GET['id']));
$requette_parcours=$requette2->fetch();

$requette_matiere=$bdd->prepare('SELECT * FROM matiere WHERE mention=? ORDER BY id_matiere');
$requette_matiere->execute(array($requette_parcours['mention_abrevie']));

?>







      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Déliberation <?php echo $requette_parcours['parcours_abrevie']; ?></h3>

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
                      <th style="">
                          Pos
                      </th>
                      <th style="">
                          Nom
                      </th>
                      <th style="">
                          Prénoms
                      </th>
                      <?php
                      while ($matiere=$requette_matiere->fetch()) {
                      ?>
                      <th style="" class="text-center">
                          <?php echo $matiere['nom_matiere'];?>
                      </th>
                      <?php } ?>

                      <th style="">
                          Moyenne
                      </th>
                      <th style="">
                        
                      </th>
                  </tr>
              </thead>
              <tbody>
                <?php

                $i=1;
                while ($donnees=$requette->fetch()) {
                

$requette3=$bdd->prepare('SELECT * FROM notes WHERE id_notes=?');
$requette3->execute(array($donnees["id_candidat"]));
$notes=$requette3->fetch();


                ?>
                                <tr>
                      <td>
                          <?php echo $i; ?>
                      </td>
                      <td>
                        <?php echo $donnees["nom"] ?>
                      </td>
                      <td>
                        <?php echo $donnees["prenom"] ?>
                      </td>
                      <td class="project-state">
                        <?php echo $notes['matiere1']; ?>
                      </td>
                      <td class="project-state">
                        <?php echo $notes['matiere2']; ?>
                      </td>
                      <td class="project-state">
                        <?php echo $notes['matiere3']; ?>
                      </td>
                      <td class="project-state">
                        <?php echo $notes['matiere4']; ?>
                      </td>
                      <td class="project-state">
                        <?php echo $notes['matiere5']; ?>
                      </td>
                      <td class="project-state">
                        <?php $moyenne = ($notes['matiere1']*$notes['coef1']+$notes['matiere2']*$notes['coef2']+$notes['matiere3']*$notes['coef3']+$notes['matiere4']*$notes['coef4']+$notes['matiere5']*$notes['coef5'])/8;
                              $moyenne_arrondie = round($moyenne, 2);
                              echo $moyenne_arrondie;
                         ?>
                      </td>
                      <td class="project-actions text-right">
<?php
$numero_notes = $donnees["id_candidat"];

// Requête pour vérifier si la valeur existe dans la table `etudiants`
$sql = "SELECT COUNT(*) FROM notes WHERE id_notes = :numero_notes";
$stmt = $bdd->prepare($sql);
$stmt->bindParam(':numero_notes', $numero_notes, PDO::PARAM_STR);
$stmt->execute();

// Récupérer le nombre de lignes correspondant à la requête
$existe = $stmt->fetchColumn();
$etu=$donnees['id_candidat'];
if ($existe)
{


        if ($requette_parcours['verrou_notes']=="OUI")
                    {
          
                      echo '<div class="fas fa-lock"></div>';
                    }

        else
                    {
        echo                  '<a class="btn btn-primary btn-sm" href=modif_note_etu.php?id_etu='.$etu.
                                      '><i class="fas fa-folder">
                                      </i>
                                      Modifier
                                  </a>';
                    }


} else {

echo                  '<a class="btn btn-danger btn-sm" href=saisi_note_etu.php?id_etu='.$etu.
                              '><i class="fas fa-folder">
                              </i>
                              Saisi
                          </a>';

}?>


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
                $i++;}
                ?>
              </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>

<?php if ($requette_parcours['verrou_notes']=="OUI") {?>
  
<form style="text-align:center;" action="#" method="POST">
  <input type="hidden" name="verrou" value="OUI">
  <input type="hidden" name="parcours" value="<?php echo $_GET['id'];?> ">
  <button type="submit" class="btn btn-warning">Déverrouiller les notes</button>
  <p></p>
</form>

<?php } else { ?>

<form style="text-align:center;" action="verrouiller_notes.php" method="POST">
  <input type="hidden" name="verrou" value="OUI">
  <input type="hidden" name="parcours" value="<?php echo $_GET['id'];?> ">
  <button type="submit" class="btn btn-danger">Verrouiller les notes</button>
  <p></p>
</form>

<?php } ?>



<?php $content = ob_get_clean();
$menu = "";
$menu_delib=""; 
?>
<?php require('template.php'); ?>