<?php
ob_start();

session_start();


require('connect.php');
if (isset($_GET["id_utili"])) {$change=$_GET["id_utili"];} else {header("Location:index.php");}

   $changement=$bdd->query("SELECT * FROM utilisateur WHERE id_utilisateur=$change");
     $champ=$changement->fetch();

?>



            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Modification utilisateur</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="action_modif_utili.php" method="POST">
                

<div class="card-body">
  <div class="row">
                  <div class="form-group col-md-6">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" name="nom" value="<?php echo $champ['nom']; ?>" >
                  </div>
                  <div class="form-group  col-md-6">
                    <label for="">Prénoms</label>
                    <input type="text" class="form-control" name="prenom" value="<?php echo $champ['prenom']; ?>">
                  </div>
  </div>

    <div class="row">
                  <div class="form-group col-md-6">
                    <label for="nom">Mail</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $champ['email']; ?>">
                  </div>
                  <div class="form-group  col-md-6">
                    <label for="">Pseudo</label>
                    <input type="text" class="form-control" name="pseudo" value="<?php echo $champ['pseudo']; ?>">
                  </div>
    </div>


    <div class="row">
                  <div class="form-group col-md-6">
                    <label for="nom">Mot de passe</label>
                    <input type="password" class="form-control" name="mdp" value="<?php echo $champ['mdp']; ?>">
                  </div>
                  <div class="form-group  col-md-6">
                    <label for="">Groupe</label>
                    <select class="form-control" name="id_role">
                      <option value="<?php echo $champ['id_role']; ?>"><?php echo $champ['id_role']; ?></option>
                      <option value="5">Visiteur</option>
                      <option value="4">Operateur</option>
                      <option value="3">Controleur</option>
                      <option value="2">Administrateur</option>
                      <option value="1">SuperAdministrateur</option>
                    </select>
                  </div>
    </div>


                <!-- /.card-body -->

</div>

<input type="hidden" name="id_util" value="<?php echo $change;?> ">

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Enregistrer la modification</button>
                </div>

              </form>
            </div>



<?php $content = ob_get_clean();
$menu = "Nouveau utilisateur";
$menu_utili=""; 
?>
<?php require('template.php'); ?>