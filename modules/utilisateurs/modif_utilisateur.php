<?php
ob_start();

session_start();
require_once __DIR__ . '/../../includes/auth.php';
exiger_role(['superadmin', 'admin']);

require __DIR__ . '/../../config/connect.php';
$change = filter_input(INPUT_GET, 'id_utili', FILTER_VALIDATE_INT);
if (!$change) { header("Location:utilisateur.php"); exit; }

$changement = $bdd->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
$changement->execute([$change]);
$champ = $changement->fetch();
if (!$champ) { header("Location:utilisateur.php"); exit; }

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
                    <input type="text" class="form-control" name="nom" value="<?= h($champ['nom']) ?>" >
                  </div>
                  <div class="form-group  col-md-6">
                    <label for="">Prénoms</label>
                    <input type="text" class="form-control" name="prenom" value="<?= h($champ['prenom']) ?>">
                  </div>
  </div>

    <div class="row">
                  <div class="form-group col-md-6">
                    <label for="nom">Mail</label>
                    <input type="email" class="form-control" name="email" value="<?= h($champ['email']) ?>">
                  </div>
                  <div class="form-group  col-md-6">
                    <label for="">Pseudo</label>
                    <input type="text" class="form-control" name="pseudo" value="<?= h($champ['pseudo']) ?>">
                  </div>
    </div>


    <div class="row">
                  <div class="form-group col-md-6">
                    <label for="nom">Mot de passe</label>
                    <input type="password" class="form-control" name="mdp" value="" autocomplete="new-password" placeholder="Laisser vide pour conserver le mot de passe actuel">
                  </div>
                  <div class="form-group  col-md-6">
                    <label for="">Groupe</label>
                    <select class="form-control" name="id_role">
                      <option value="<?= h($champ['id_role']) ?>"><?= h($champ['id_role']) ?></option>
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

<input type="hidden" name="id_util" value="<?= (int) $change ?>">

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Enregistrer la modification</button>
                </div>

              </form>
            </div>



<?php $content = ob_get_clean();
$menu = "Nouveau utilisateur";
$menu_utili=""; 
?>
<?php require __DIR__ . '/../../includes/template.php'; ?>