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
            <input type="text" name="telephone" class="form-control" placeholder="N° téléphone du candidat" required>
        </div>
    </div>

    <!-- Champs cachés -->

    <hr>
    <div class="carre row text-center">
        <h4>INFORMATION BACC</h4>
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
            <label>Série Bacc :</label>
            <select class="form-control" name="serie_bacc" required>
                <option></option>
                <?php if ($_GET['ecole'] == 'EGI' || $_GET['ecole'] == 'EGCGN') { ?>
                    <option value="A2">A2</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="S">S</option>
                    <option value="OSE">OSE</option>
                    <option value="Technique Industriel">Tech. Industriel</option>
                    <option value="Technique Génie Civil">Tech. Génie Civil</option>
                <?php } elseif ($_GET['ecole'] == 'EGMCS') { ?>
                    <option value="A1">A1</option>
                    <option value="A2">A2</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="L">L</option>
                    <option value="ES">ES</option>
                    <option value="OSE">OSE</option>
                    <option value="S">S</option>
                    <option value="Technique Tertiaire">Tech. Tertiaire</option>
                    <option value="G1">G1</option>
                    <option value="G2">G2</option>
                    <option value="CG">CG</option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-3">
            <label>Année Bacc :</label>
            <input type="number" name="annee_bacc" class="form-control" min="2018" required>
        </div>

        <div class="col-md-3">
            <label>Mention Bacc :</label>
            <select class="form-control" name="mention_bacc" required>
                <option></option>
                <option value="Passable">Passable</option>
                <option value="Assez Bien">Assez Bien</option>
                <option value="Bien">Bien</option>
                <option value="Très Bien">Très Bien</option>
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
                $reponse1 = $bdd->prepare('SELECT * FROM parcours WHERE ecole_abrevie=? AND grade_abrevie=? GROUP BY parcours_abrevie ORDER BY ecole_abrevie, id_parcours');
                $reponse1->execute([$_GET['ecole'], 'DTS']);
                while ($donnees1 = $reponse1->fetch()) {
                    echo '<option value="' . htmlspecialchars($donnees1['parcours_abrevie']) . '">' . htmlspecialchars($donnees1['parcours_abrevie']) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="col-md-4">
            <label>Parcours 2e choix :</label>
            <select class="form-control" name="parcours2" required>
                <option></option>
                <?php
                $reponse2 = $bdd->prepare('SELECT * FROM parcours WHERE ecole_abrevie=? AND grade_abrevie=? GROUP BY parcours_abrevie ORDER BY ecole_abrevie, id_parcours');
                $reponse2->execute([$_GET['ecole'], 'DTS']);
                while ($donnees2 = $reponse2->fetch()) {
                    echo '<option value="' . htmlspecialchars($donnees2['parcours_abrevie']) . '">' . htmlspecialchars($donnees2['parcours_abrevie']) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="col-md-4">
            <label>Centre d'examen :</label>
            <select class="form-control" name="centre" required>
                <option></option>
                <?php
                // Tous les centres, peu importe id_annee
                $centres = $bdd->query("SELECT * FROM centre ORDER BY nom_centre ASC")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($centres as $c) {
                    echo '<option value="' . htmlspecialchars($c['nom_centre']) . '">' . htmlspecialchars($c['nom_centre']) . '</option>';
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
            <label>Date d'arrivée :</label>
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

        <?php $ecole = $_GET['ecole']; ?>
        <input type="hidden" name="ecole" value="<?= htmlspecialchars($ecole, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="niveau" value="DTS">

        <div class="col-md-4">
            <input type="submit" class="form-control btn btn-primary mt-4" value="Enregistrer">
        </div>
    </div>
</form>
<script src="../../assets/js/validation_saisie.js"></script>

<?php
$content = ob_get_clean();

if ($_GET['ecole'] == 'EGI') {
    $menu = "Nouveau candidat DTS / EGI";
    $menu_dts_egi = "nav-link active";
    $menu_dts_egcgn = $menu_dts_egmcs = "nav-link";
    $menu_saisi = "nav-item menu-open";
} elseif ($_GET['ecole'] == 'EGCGN') {
    $menu = "Nouveau candidat DTS / EGCGN";
    $menu_dts_egcgn = "nav-link active";
    $menu_dts_egi = $menu_dts_egmcs = "nav-link";
    $menu_saisi = "nav-item menu-open";
} else {
    $menu = "Nouveau candidat DTS / EGMCS";
    $menu_dts_egmcs = "nav-link active";
    $menu_dts_egcgn = $menu_dts_egi = "nav-link";
    $menu_saisi = "nav-item menu-open";
}

require __DIR__ . '/../../includes/template.php';
} else {
    header("Location:../../login.php");
    exit;
}
?>
