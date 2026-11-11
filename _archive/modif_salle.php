<?php
ob_start();

session_start();
if($_SESSION['groupe']!="admin"){header("Location:login.php");}

require('connect.php');
?>


<?php
if (isset($_POST["id_change"])) {$change=$_POST["id_change"];} else {header("Location:index.php");}

	 $changement=$bdd->query("SELECT * FROM candidats WHERE id_candidat=$change");
     $champ=$changement->fetch();
?>

<div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Modification</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="action_modif_salle.php" method="POST">
                <div class="card-body">
                  <div class="form-group row">
                    <label for="nom" class="col-sm-2 col-form-label">Nom</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="nom" placeholder="Nom" value="<?php echo $champ['nom']; ?>" readonly>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Prénoms</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="prenom" placeholder="Prénoms" value="<?php echo $champ['prenom']; ?>" readonly>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Parcours</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="prenom" placeholder="Parcours" value="<?php echo $champ['parcours1']; ?>" readonly>
                    </div>
                  </div>

                    <div class="form-group row">
                    <label for="salle" class="col-sm-2 col-form-label">SALLE</label>
                    <div class="col-sm-10">
                    <select class="form-control" name="salle" required>
                    <option value="<?php echo $champ['salle']; ?>"><?php echo $champ['salle']; ?></option>
                    <option value="0">0</option>
<?php
    $reponse1=$bdd->prepare('SELECT * FROM salle ORDER BY id_salle ');
    $reponse1->execute();
    while($donnees1=$reponse1->fetch())

    {
        $salle=$donnees1['id_salle'];
?>
<option  value="<?php echo $salle ?>"><?php echo $salle ?></option>
<?php } ?>
                      </select>
                    </div>
                    </div>

                    <div class="form-group row">
                    <label for="jury" class="col-sm-2 col-form-label">JURY</label>
                    <div class="col-sm-10">
                    <select class="form-control" name="jury" required>
                    <option value="<?php echo $champ['jury']; ?>"><?php echo $champ['jury']; ?></option>
                    <option value="0">0</option>
                    <option value="JURY 1">JURY 1</option>
                    <option value="JURY 2">JURY 2</option>
                    <option value="JURY 3">JURY 3</option>
                    <option value="JURY 4">JURY 4</option>
                    <option value="JURY 5">JURY 5</option>
                    <option value="JURY 6">JURY 6</option>
                    <option value="JURY 7">JURY 7</option>
                    <option value="JURY 8">JURY 8</option>
                    <option value="JURY 9">JURY 9</option>
                    <option value="JURY 10">JURY 10</option>
                    <option value="JURY 11">JURY 11</option>
                    <option value="JURY 12">JURY 12</option>
                    <option value="JURY 13">JURY 13</option>
                    <option value="JURY 14">JURY 14</option>
                    <option value="JURY 15">JURY 15</option>
                    <option value="JURY 16">JURY 16</option>
                  </select>
                    </div>
                    </div>



                  	<input type="hidden" name="id_modif" value="<?php echo $change; ?>">
                  	<input type="hidden" name="modifie_par" value="<?php echo $_SESSION['nom']." ".$_SESSION['prenom'] ?>">
                  	<input type="hidden" name="date_modification" value="<?php echo date('d')."/".date('M')."/".date('Y')." ".date('H')."h".date('i'); ?>" >

              
                
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-info">Enregistrer</button>
                  <button type="submit" class="btn btn-default float-right"><a href="index.php">Annuler</a></button>
                </div>
                <!-- /.card-footer -->
              </form>
</div>






<?php $content = ob_get_clean();
$menu = "";
?>
<?php require('template.php'); ?>
