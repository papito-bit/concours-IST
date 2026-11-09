<?php
ob_start();

session_start();
if(isset($_SESSION['id_utilisateur']) AND isset($_SESSION['email_concours'])){

require('connect.php');
?>


<?php
if (isset($_POST["id_change"])) {$change=$_POST["id_change"];} else {header("Location:index.php");}

	 $changement=$bdd->query("SELECT * FROM candidats WHERE id_candidat=$change");
     $champ=$changement->fetch();
?>

<div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Validation</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="action_modif.php" method="POST">
                <div class="card-body">
                  <div class="form-group row">
                    <label for="nom" class="col-sm-2 col-form-label">Nom</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="nom" placeholder="Nom" value="<?php echo $champ['nom']; ?>" >
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Prénoms</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="prenom" placeholder="Prénoms" value="<?php echo $champ['prenom']; ?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Sexe</label>
                    <div class="col-sm-10">
                      <select class="form-control" name="sexe">
                      	<option value="<?php echo $champ['sexe']; ?>"> <?php echo $champ['sexe']; ?> </option>
                      	<option value="M">M</option>
                      	<option value="F">F</option>
                      </select>
                    </div>
                    </div>


                    <div class="form-group row">
                    <label for="date_naissance" class="col-sm-2 col-form-label">Date de naissance</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="date_naissance" placeholder="JJ/MM/AAAA" value="<?php echo $champ['date_naissance']; ?>" >
                    </div>
                  	</div>

                  	<div class="form-group row">
                    <label for="lieu_naissance" class="col-sm-2 col-form-label">Lieu de naissance</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="lieu_naissance" placeholder="Lieu de naissance" value="<?php echo $champ['lieu_naissance']; ?>" >
                    </div>
                  	</div>

                  	<div class="form-group row">
                    <label for="adresse" class="col-sm-2 col-form-label">Adresse</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="adresse" placeholder="Lieu de naissance" value="<?php echo $champ['adresse']; ?>" >
                    </div>
                  	</div>

                  	<div class="form-group row">
                    <label for="telephone" class="col-sm-2 col-form-label">Téléphone</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="telephone" placeholder="Téléphone" value="<?php echo $champ['telephone']; ?>" >
                    </div>
                  	</div>

                  	<div class="form-group row">
                    <label for="type_candidat" class="col-sm-2 col-form-label">Type de candidat</label>
                    <div class="col-sm-10">
                      <select class="form-control" name="type_candidat" required>
                    <option value="<?php echo $champ['type_candidat']; ?>"><?php echo $champ['type_candidat']; ?></option>
                    <option value="Scolaire">Scolaire</option>
                    <option value="Entreprise">Entreprise</option>
                    <option value="Etranger">Etranger</option>
                </select>
                    </div>
                  	</div>

                  	<div class="form-group row">
                    <label for="serie_bacc" class="col-sm-2 col-form-label">Série Bacc</label>
                    <div class="col-sm-10">
                      <select class="form-control" name="serie_bacc" required>
                    <option value="<?php echo $champ['serie_bacc']; ?>"><?php echo $champ['serie_bacc']; ?></option>
                    <option value="A1">A1</option>
                    <option value="A2">A2</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="L">L</option>
                    <option value="OSE">OSE</option>
                    <option value="S">S</option>
                    <option value="G1">G1</option>
                    <option value="G2">G2</option>
                    <option value="FTG">FTG</option>
                    <option value="Technique Tertiaire">Tech. Tertiaire</option>
                    <option value="Technique Industriel">Tech. Industriel</option>
                    <option value="Technique Génie Civil">Tech. Génie Civil</option>
                </select>
                    </div>
                  	</div>

                  	<div class="form-group row">
                    <label for="serie_bacc" class="col-sm-2 col-form-label">Mention Bacc</label>
                    <div class="col-sm-10">
                      <select class="form-control" name="mention_bacc" required>
                    <option value="<?php echo $champ['mention_bacc']; ?>"><?php echo $champ['mention_bacc']; ?></option>
                    <option value="Passable">Passable</option>
                    <option value="Assez Bien">Assez Bien</option>
                    <option value="Bien">Bien</option>
                    <option value="Très Bien">Très Bien</option>
                    <option value="En cours">En cours</option>
                </select>
                    </div>
                  	</div>

                  	<div class="form-group row">
                    <label for="annee_bacc" class="col-sm-2 col-form-label">Année Bacc</label>
                    <div class="col-sm-10">
                      <input type="number" class="form-control" name="annee_bacc" placeholder="Téléphone" min="2015" value="<?php echo $champ['annee_bacc']; ?>" >
                    </div>
                  	</div>

                  	<div class="form-group row">
                    <label for="centre" class="col-sm-2 col-form-label">Centre de concours</label>
                    <div class="col-sm-10">
                    <select class="form-control" name="centre" required>
                    <option value="<?php echo $champ['centre']; ?>"><?php echo $champ['centre']; ?></option>
<?php
    $reponse_centre=$bdd->prepare('SELECT * FROM centre WHERE etat_centre="OUI" ORDER BY id_centre');
    $reponse_centre->execute();
    while($donnees_centre=$reponse_centre->fetch())

    {
        $centre=$donnees_centre['nom_centre'];
?>
<option  value="<?php echo $centre ?>"><?php echo $centre ?></option>
<?php } ?>
                </select>
                    </div>
                  	</div>


                  	<div class="form-group row">
                    <label for="parcours1" class="col-sm-2 col-form-label">Parcours 1er choix</label>
                    <div class="col-sm-10">
                    <select class="form-control" name="parcours1" required>
                    <option value="<?php echo $champ['parcours1']; ?>"><?php echo $champ['parcours1']; ?></option>
<?php
    $reponse1=$bdd->prepare('SELECT * FROM parcours GROUP BY parcours_abrevie ORDER BY ecole_abrevie, id_parcours ');
    $reponse1->execute();
    while($donnees1=$reponse1->fetch())

    {
        $parcours1=$donnees1['parcours_abrevie'];
?>
<option  value="<?php echo $parcours1 ?>"><?php echo $parcours1 ?></option>
<?php } ?>
                    	</select>
                    </div>
                  	</div>

                  	<div class="form-group row">
                    <label for="parcours2" class="col-sm-2 col-form-label">Parcours 2e choix</label>
                    <div class="col-sm-10">
                    <select class="form-control" name="parcours2" required>
                    <option value="<?php echo $champ['parcours2']; ?>"><?php echo $champ['parcours2']; ?></option>
<?php
    $reponse2=$bdd->prepare('SELECT * FROM parcours GROUP BY parcours_abrevie ORDER BY ecole_abrevie, id_parcours ');
    $reponse2->execute();
    while($donnees2=$reponse2->fetch())

    {
        $parcours2=$donnees2['parcours_abrevie'];
?>
<option  value="<?php echo $parcours2 ?>"><?php echo $parcours2 ?></option>
<?php } ?>
                    	</select>
                    </div>
                  	</div>

                  	<div class="form-group row">
                    <label for="num_arrivee" class="col-sm-2 col-form-label">Numéro d'arrivée</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="num_arrivee" placeholder="" value="<?php echo $champ['num_arrivee']; ?>" >
                    </div>
                  	</div>


                  	<div class="form-group row">
                    <label for="date_arrivee" class="col-sm-2 col-form-label">Date d'arrivée</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="date_arrivee" placeholder="JJ/MM/AAAA" value="<?php echo $champ['date_arrivee']; ?>" >
                    </div>
                  	</div>


                  	<div class="form-group row">
                    <label for="mode_paiement" class="col-sm-2 col-form-label">Mode de paiement</label>
                    <div class="col-sm-10">
                    <select class="form-control" name="mode_paiement" required>
                    <option value="<?php echo $champ['mode_paiement']; ?>"><?php echo $champ['mode_paiement']; ?></option>
                    <option value="Espece">Espèce</option>
                    <option value="Versement bancaire">Versement bancaire</option>
                    <option value="Virement bancaire">Virement bancaire</option>
                    <option value="Cheque">Chèque</option>
                	</select>
                    </div>
                  	</div>


                  	<div class="form-group row">
                    <label for="dossier_complet" class="col-sm-2 col-form-label">Dossier complet</label>
                    <div class="col-sm-10">
                    
                <select class="form-control" name="dossier_complet" required>
                <option value="<?php echo $champ['dossier_complet']; ?>"><?php echo $champ['dossier_complet']; ?></option>
                    <option value="OUI">OUI</option>
                    <option value="NON">NON</option>
                </select>
                    </div>
                  	</div>


                  	<div class="form-group row">
                    <label for="obs" class="col-sm-2 col-form-label">Observation</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="obs" placeholder="" value="<?php echo $champ['obs']; ?>" >
                    </div>
                  	</div>

                  	<div class="form-group row">
                    <label for="obs" class="col-sm-2 col-form-label"><i>Saisi par</i></label>
                    <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo $champ['saisi_par']; if (isset($champ['date_saisi'])){ echo ' le '.$champ['date_saisi'];} ?>" readonly>
                    </div>
                  	</div>

                  	<div class="form-group row">
                    <label for="obs" class="col-sm-2 col-form-label"><i>Modifié par</i></label>
                    <div class="col-sm-10">
                    <input class="form-control" type="text" value="<?php echo $champ['modifie_par']; if (isset($champ['date_modification'])){ echo ' le '.$champ['date_modification'];} ?>" readonly>
                    </div>
                  	</div>
                      <div class="form-group row">
                    <label for="obs" class="col-sm-2 col-form-label"><i>Vérifié</i></label>
                    <button type="checkbox" class="btn btn-info"></button>
                  	</div>


                  	<input type="hidden" name="id_modif" value="<?php echo $change; ?>">
                  	<input type="hidden" name="modifie_par" value="<?php echo $_SESSION['nom']." ".$_SESSION['prenom'] ?>">
                  	<input type="hidden" name="date_modification" value="<?php echo date('d')."/".date('M')."/".date('Y')." ".date('H')."h".date('i'); ?>" >

              
                
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-info">Valider</button>
                </div>
                <!-- /.card-footer -->
              </form>
</div>






<?php $content = ob_get_clean();
$menu = "";
?>
<?php require('template.php'); ?>

<?php }else{header("Location:login.php");} ?>