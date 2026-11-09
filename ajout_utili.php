<?php
ob_start();

session_start();
?>



            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Informations du nouveau utilisateur</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="action_ajout_utili.php" method="POST">
                

<div class="card-body">
  <div class="row">
                  <div class="form-group col-md-6">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" name="nom" placeholder="" required>
                  </div>
                  <div class="form-group  col-md-6">
                    <label for="">Prénoms</label>
                    <input type="text" class="form-control" name="prenom" placeholder="" required>
                  </div>
  </div>

    <div class="row">
                  <div class="form-group col-md-6">
                    <label for="nom">Mail</label>
                    <input type="email" class="form-control" name="email" placeholder="" required>
                  </div>
                  <div class="form-group  col-md-6">
                    <label for="">Pseudo</label>
                    <input type="text" class="form-control" name="pseudo" placeholder="" required>
                  </div>
    </div>


    <div class="row">
                  <div class="form-group col-md-6">
                    <label for="nom">Mot de passe</label>
                    <input type="password" class="form-control" name="mdp" placeholder="" required>
                  </div>
                  <div class="form-group  col-md-6">
                    <label for="">Groupe</label>
                    <select class="form-control" name="id_role" required>
                      <option value=""></option>
                      <option value="visiteur">Visiteur</option>
                      <option value="operateur">Operateur</option>
                      <option value="controleur">Controleur</option>
                      <option value="admin">Administrateur</option>
                      <option value="superadmin">SuperAdministrateur</option>
                    </select>
                  </div>
    </div>


                <!-- /.card-body -->

</div>

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>

              </form>
            </div>



<?php $content = ob_get_clean();
$menu = "Nouveau utilisateur";
$menu_utili=""; 
?>
<?php require('template.php'); ?>