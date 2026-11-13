<?php
ob_start();

session_start();
if (isset($_SESSION['id_utilisateur']) && isset($_SESSION['email_concours'])) {

    include __DIR__ . '/../../config/connect.php';
?>

<form method="POST" action="enregistre_candidat.php">
    <hr>
    <div class="carre row text-center">
        <h4>INFORMATION DU CANDIDAT</h4>
    </div>

    <div class="row">
        <div class="col-md-4">
            <label>Nom :</label>
            <input type="text" name="nom" class="form-control" placeholder="Nom du candidat" required>
        </div>
        <div class="col-md-4">
            <label>Prénoms :</label>
            <input type="text" name="prenom" class="form-control" placeholder="Prénoms du candidat">
        </div>
        <div class="col-md-4">
            <label>Sexe :</label>
            <select name="sexe" class="form-control" required>
                <option></option>
                <option value="M">M</option>
                <option value="F">F</option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <label>Date de naissance :</label>
            <input type="date" name="date_naissance" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label>Lieu de naissance :</label>
            <input type="text" name="lieu_naissance" class="form-control" placeholder="Lieu de naissance" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <label>Adresse :</label>
            <input type="text" name="adresse" class="form-control" placeholder="Adresse du candidat" required>
        </div>
        <div class="col-md-6">
            <label>Téléphone :</label>
            <input type="text" name="telephone" class="form-control" placeholder="Téléphone" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <label>N° CIN :</label>
            <input type="text" name="N_CIN" class="form-control" placeholder="Numéro CIN du candidat" required>
        </div>
        <div class="col-md-6">
            <label>Date de CIN :</label>
            <input type="date" name="Date_CIN" class="form-control" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <label>Lieu CIN :</label>
            <input type="text" name="Lieu_CIN" class="form-control" placeholder="Lieu de délivrance du CIN" required>
        </div>
        <div class="col-md-6">
            <label>Adresse e-mail :</label>
            <input type="email" name="adresse_email" class="form-control" placeholder="Adresse email du candidat" required>
        </div>
    </div>

    <hr>
    <div class="carre row text-center">
        <h4>INFORMATION DIPLÔME</h4>
    </div>

    <div class="row">
        <div class="col-md-3">
            <label>Candidat :</label>
            <select class="form-control" name="type_candidat" required>
                <option></option>
                <option value="Scolaire">Scolaire</option>
                <option value="Entreprise">Entreprise</option>
                <option value="Etranger">Étranger</option>
            </select>
        </div>

        <div class="col-md-3">
            <label>Dernier diplôme obtenu :</label>
            <select class="form-control" name="serie_bacc" required>
                <option></option>
                <option value="DTS">DTS</option>
                <option value="DTSS">DTSS</option>
                <option value="Bacc+2">Bacc+2</option>
                <option value="Bacc+3">Bacc+3</option>
                <option value="Licence">Licence</option>
            </select>
        </div>

        <div class="col-md-3">
            <label>Année d’obtention :</label>
            <input type="number" name="annee_bacc" class="form-control" min="2000" placeholder="Année" required>
        </div>

        <div class="col-md-3">
            <label>Mention :</label>
            <select class="form-control" name="mention_bacc" required>
                <option></option>
                <option value="Passable">Passable</option>
                <option value="Assez Bien">Assez Bien</option>
                <option value="Bien">Bien</option>
                <option value="Très Bien">Très Bien</option>
                <option value="En cours">En cours</option>
            </select>
        </div>
    </div>

    <hr>
    <div class="carre row text-center">
        <h4>INFORMATION MENTION ET PARCOURS</h4>
    </div>

    <div class="row">
        <div class="col-md-4">
            <label>Parcours 1er choix :</label>
            <select class="form-control" name="parcours1" required>
                <option></option>
                <?php
                $req1 = $bdd->prepare("SELECT * FROM parcours WHERE ecole_abrevie=? AND grade_abrevie=? GROUP BY parcours_abrevie ORDER BY id_parcours");
                $req1->execute([$_GET['ecole'], 'ING']);
                while ($row = $req1->fetch()) {
                    echo '<option value="' . htmlspecialchars($row['parcours_abrevie']) . '">' . htmlspecialchars($row['parcours_abrevie']) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="col-md-4">
            <label>Parcours 2e choix :</label>
            <select class="form-control" name="parcours2">
                <option></option>
                <?php
                $req2 = $bdd->prepare("SELECT * FROM parcours WHERE ecole_abrevie=? AND grade_abrevie=? GROUP BY parcours_abrevie ORDER BY id_parcours");
                $req2->execute([$_GET['ecole'], 'ING']);
                while ($row2 = $req2->fetch()) {
                    echo '<option value="' . htmlspecialchars($row2['parcours_abrevie']) . '">' . htmlspecialchars($row2['parcours_abrevie']) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="col-md-4">
            <label>Centre d'examen :</label>
            <select class="form-control" name="centre" required>
                <option></option>
                <?php
                // Tous les centres sans filtre d’année
                $centres = $bdd->query("SELECT * FROM centre ORDER BY nom_centre ASC")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($centres as $c) {
                    echo '<option value="' . htmlspecialchars($c['nom_centre']) . '">' . htmlspecialchars($c['nom_centre']) . '</option>';
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <label>Année académique :</label>
            <select class="form-control" name="id_annee" required>
                <option></option>
                <?php
                // Liste dynamique des années (table annees)
                $annees = $bdd->query("SELECT * FROM annees ORDER BY id_annee DESC")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($annees as $a) {
                    echo '<option value="' . htmlspecialchars($a['id_annee']) . '">' . htmlspecialchars($a['annee']) . '</option>';
                }
                ?>
            </select>
        </div>
    </div>

    <hr>
    <div class="carre row text-center">
        <h4>INFORMATION DOSSIER</h4>
    </div>

    <div class="row">
        <div class="col-md-4">
            <label>N° Arrivée :</label>
            <input type="text" name="num_arrivee" class="form-control" placeholder="N° Arrivée" required>
        </div>
        <div class="col-md-4">
            <label>Date d’arrivée :</label>
            <input type="date" name="date_arrivee" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label>Mode de paiement :</label>
            <select class="form-control" name="mode_paiement" required>
                <option></option>
                <option value="Versement bancaire">Versement bancaire</option>
                <option value="Versement caisse">Versement caisse</option>
                <option value="Virement bancaire">Virement bancaire</option>
                <option value="Chèque">Chèque</option>
                <option value="Autres">Autres</option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <label>Dossier complet :</label>
            <select class="form-control" name="dossier_complet" required>
                <option></option>
                <option value="OUI">OUI</option>
                <option value="NON">NON</option>
            </select>
        </div>
        <div class="col-md-4">
            <label>Observation :</label>
            <input type="text" name="obs" class="form-control" placeholder="Dossier manquant">
        </div>

        <input type="hidden" name="ecole" value="<?= htmlspecialchars($_GET['ecole'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="niveau" value="INGENIORAT">

        <div class="col-md-4">
            <input type="submit" class="form-control btn btn-primary mt-4" value="Enregistrer">
        </div>
    </div>
</form>
<script src="../../assets/js/validation_saisie.js"></script>

<?php
$content = ob_get_clean();

if ($_GET['ecole'] == 'EGI') {
    $menu = "Nouveau candidat Ingénieur / EGI";
    $menu_ing_egi = "nav-link active";
    $menu_ing_egcgn = $menu_ing_egmcs = "nav-link";
    $menu_saisi_ing = "nav-item menu-open";
} elseif ($_GET['ecole'] == 'EGCGN') {
    $menu = "Nouveau candidat Ingénieur / EGCGN";
    $menu_ing_egcgn = "nav-link active";
    $menu_ing_egi = $menu_ing_egmcs = "nav-link";
    $menu_saisi_ing = "nav-item menu-open";
} else {
    $menu = "Nouveau candidat Ingénieur / EGMCS";
    $menu_ing_egmcs = "nav-link active";
    $menu_ing_egcgn = $menu_ing_egi = "nav-link";
    $menu_saisi_ing = "nav-item menu-open";
}

require __DIR__ . '/../../includes/template.php';
} else {
    header("Location:../../login.php");
    exit;
}
?>
