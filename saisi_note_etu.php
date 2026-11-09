<?php
ob_start();

session_start();
if($_SESSION['groupe']!="admin"){header("Location:login.php");}

require('connect.php');
if (isset($_GET["id_etu"])) {$change=$_GET["id_etu"];}

   $changement=$bdd->query("SELECT * FROM candidats WHERE id_candidat=$change");
     $champ=$changement->fetch();




$requette2=$bdd->prepare('SELECT * FROM parcours WHERE parcours_abrevie=?');
$requette2->execute(array($champ['parcours1']));
$requette_parcours=$requette2->fetch();

$requette_matiere=$bdd->prepare('SELECT * FROM matiere WHERE mention=? ORDER BY id_matiere');
$requette_matiere->execute(array($requette_parcours['mention_abrevie']));
?>



            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Saisi de note</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="action_saisi_note_etu.php" method="POST">
                

<div class="card-body">
  <div class="row">
                      <?php
                      $i=1;
                      while ($matiere=$requette_matiere->fetch()) {
                      ?>


                  <div class="form-group col-md-2">
                    <label for="nom"><?php echo $matiere['nom_matiere'];?></label>
                    <input type="number" class="form-control" name="<?php echo "matiere".$i;?>" value="" step="0.01" min="0" max="20" required>
                    <input type="hidden" name="<?php echo "coef".$i;?>" value="<?php echo $matiere['coef'];?>">
                  </div>


                      <?php
                      $i++;}
                      ?>

  </div>
</div>

<input type="hidden" name="id_etu" value="<?php echo $change;?> ">
<input type="hidden" name="parcours" value="<?php echo $champ['parcours1'];?> ">

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>

              </form>
            </div>



<?php $content = ob_get_clean();
$menu = $champ['nom']." ".$champ['prenom']." ".$champ['parcours1'];
$menu_delib=""; 
?>
<?php require('template.php'); ?>



