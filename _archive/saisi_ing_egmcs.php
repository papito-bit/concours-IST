<?php
ob_start();


session_start();
if(isset($_SESSION['id_utilisateur']) AND isset($_SESSION['email_concours'])){



?>

<form class="" method="POST" action="enregistre_candidat.php">
    <hr>
    <div class="carre row">
        <div class="col-md-3"></div>
        <h4>INFORMATION DU CANDIDAT</h4>
        <div class="col-md-3"></div>

    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" name="nom" class="form-control" id="exampleInputEmail1" placeholder="Nom du candidat" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="prenom">Prénoms :</label>
                <input type="text"  name="prenom" class="form-control" id="exampleInputEmail1" placeholder="Prénoms du candidat">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="sexe">Sexe :</label>
                <select  name="sexe" class="form-control" required>
                    <option></option>
                    <option value="M">M</option>
                    <option value="F">F</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="date_naissance">Date de naissance :</label>
                <input type="text"  name="date_naissance" class="form-control" id="exampleInputEmail1" placeholder="JJ/MM/AAAA" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="lieu_naissance">Lieu de naissance :</label>
                <input type="text"  name="lieu_naissance" class="form-control" id="exampleInputEmail1" placeholder="Lieu de naissance" required>
            </div>
        </div>
    </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="numero_cin">N° CIN :</label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="N° CIN">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="date_de_CIN">Date de CIN :</label>
                <input type="date"  name="Date de CIN" class="form-control" id="exampleInputEmail1" placeholder="JJ/MM/AAAA" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="lieu_cin">Lieu CIN :</label>
                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Lieu de délivrance du CIN">
            </div>
        </div>
    </div>
    <div class="col-md-6">
            <div class="form-group">
                <label for="Adresse email">Adresse e-mail :</label>
                <input type="text"  name="adresse email" class="form-control" id="exampleInputEmail1" placeholder="Adresse email du candidat" required>
            </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="adresse">Adresse :</label>
                <input type="text"   name="adresse" class="form-control" id="exampleInputEmail1" placeholder="Adresse du candidat" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="telephone">Téléphone :</label>
                <input type="text"  name="telephone" class="form-control" id="exampleInputEmail1" placeholder="N° téléphone du candidat" required>
            </div>
        </div>

    </div>
    <hr>
    <div class="carre row">
        <div class="col-md-3"></div>
        <h4>INFORMATION DIPLOME</h4>
        <div class="col-md-3"></div>
    </div>


    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="type_candidat">Candidat :</label>
                <select class="form-control" name="type_candidat" required>
                    <option></option>
                    <option value="Scolaire">Scolaire</option>
                    <option value="Entreprise">Entreprise</option>
                    <option value="Etranger">Etranger</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="serie_bacc">Dernier diplôme obtenu :</label>
                <select class="form-control" name="serie_bacc" required>
                    <option></option>
                    <option value="DTS">DTS</option>
                    <option value="DTSS">DTSS</option>
                    <option value="Bacc+2">Bacc+2</option>
                    <option value="Bacc+3">Bacc+3</option>
                    <option value="Licence">Licence</option>
                </select>

            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="annee_bacc">Année d'obtention :</label>
                <input type="number" name="annee_bacc" class="form-control" id="exampleInputEmail1" placeholder="Année" min="2010" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="mention_bacc">Mention :</label>
                <select class="form-control" name="mention_bacc" required>
                    <option></option>
                    <option value="Passable">Passable</option>
                    <option value="Assez Bien">Assez Bien</option>
                    <option value="Bien">Bien</option>
                    <option value="Tès Bien">Tès Bien</option>
                </select>
            </div>
        </div>

    </div>


    <hr>
    <div class="carre row">
        <div class="col-md-3"></div>
        <h4>INFORMATION MENTION ET PARCOURS</h4>
        <div class="col-md-3"></div>
    </div>

    <div class="row">
    <div class="col-md-3">
            <div class="form-group">
                <label for="mention">Mention :</label>
                <select class="form-control" name="mention" required>
                    <option value="Management">Management</option>
                </select>
            </div>
        </div>

    

        <div class="col-md-3">
            <div class="form-group">
                <label for="parcours1">Parcours 1er choix :</label>
                <select class="form-control" name="parcours1" required>           
                    <option value="MEO">MEO</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="parcours2">Parcours 2e choix :</label>
                <select class="form-control" name="parcours2">
                    <div class="form-group">
                <option></option> 
                    <option value="MEO">MEO</option>
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="parcours3">Parcours 3e choix :</label>
                <select class="form-control" name="parcours3">
                    <option></option>            
                    <option value="MEO">MEO</option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-3">
            <div class="form-group">
                <label for="centre">Centre d'examen :</label>
                <select class="form-control" name="centre" required>
                    <option></option>
                    <option value="Antsiranana">Antsiranana</option>
                    <option value="Ambanja">Ambanja</option>
                    <option value="Sambava">Sambava</option>
                    <option value="Mahajanga">Mahajanga</option>
                    <option value="Antananarivo">Antananarivo</option>
                    <option value="Toamasina">Toamasina</option>
                    <option value="Fianarantsoa">Fianarantsoa</option>
                    <option value="Ambositra">Ambositra</option>
                    <option value="Manakara">Manakara</option>
                    <option value="Antsirabe">Antsirabe</option>
                    <option value="Toliara">Toliara</option>
                </select>
            </div>
        </div>

    <hr>
    <div class="carre row">
        <div class="col-md-3"></div>
        <h4>INFORMATION DOSSIER</h4>
        <div class="col-md-3"></div>
    </div>


    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="num_arrivee">N° Arrivée :</label>
                <input type="text" name="num_arrivee" class="form-control" id="exampleInputEmail1" placeholder="N° Arrivée" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="date_arrivee">Date d'arrivé :</label>
                <input type="text" name="date_arrivee" class="form-control" id="exampleInputEmail1" placeholder="Date d'arrivé" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="mode_paiement">Mode de paiement :</label>
                <select class="form-control" name="mode_paiement" required>
                    <option></option>
                    <option value="Espece">Espèce</option>
                    <option value="Versement bancaire">Versement bancaire</option>
                    <option value="Virement bancaire">Virement bancaire</option>
                    <option value="Cheque">Chèque</option>
                    <option value="PaositraMoney">PaositraMoney</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="dossier_complet">Dossier complet :</label>
                <select class="form-control" name="dossier_complet" required>
                <option></option>
                    <option value="OUI">OUI</option>
                    <option value="NON">NON</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="obs">Observation :</label>
                <input type="text" name="obs" class="form-control" id="exampleInputEmail1" placeholder="Dossier manquant">
            </div>
        </div>
        <input type="hidden" name="ecole" value="EGMCS">
        <input type="hidden" name="niveau" value="INGENIORAT">
        <input type="hidden" name="saisi_par" value="<?php echo $_SESSION['nom']." ".$_SESSION['prenom']; ?>">
        <div class="col-md-4">
            <input type="submit" class="form-control btn btn-primary mt-4" value="Enregistrer">
        </div>
    </div>





</form>


<?php $content = ob_get_clean();
$menu = "Nouveau candidat INGENIORAT / EGMCS";
$menu_ing_egi="nav-link";
$menu_ing_egmcs="nav-link active";
$menu_saisi_ing="nav-item menu-open";
?>
<?php require('template.php'); ?>


<?php }else{header("Location:login.php");} ?>

