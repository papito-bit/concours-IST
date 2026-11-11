<?php
ob_start();
session_start();

if (!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['email_concours'])) {
    header("Location: login.php");
    exit;
}

require('connect.php');

// Vérification de l’ID à modifier
if (!isset($_POST["id_change"])) {
    header("Location: index.php");
    exit;
}

$id_change = intval($_POST["id_change"]);

// Récupération du candidat à modifier
$stmt = $bdd->prepare("
    SELECT can.*, cen.nom_centre AS centre 
    FROM candidats can 
    JOIN centre cen ON can.id_centre = cen.id_centre 
    WHERE can.id_candidat = ?
");
$stmt->execute([$id_change]);
$champ = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$champ) {
    die("<div class='alert alert-danger m-3'>Candidat introuvable.</div>");
}
?>

<div class="container mt-4">
    <div class="card shadow-sm border-info">
        <div class="card-header bg-info text-white">
            <h3 class="card-title mb-0">✏️ Modification du candidat</h3>
        </div>

        <form class="form-horizontal p-3" action="action_modif.php" method="POST">
            <div class="card-body">

                <!-- Informations personnelles -->
                <h5 class="mb-3 text-primary">Informations personnelles</h5>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($champ['nom']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénoms</label>
                        <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($champ['prenom']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="sexe" class="form-label">Sexe</label>
                        <select class="form-control" name="sexe">
                            <option value="<?= $champ['sexe']; ?>"><?= $champ['sexe']; ?></option>
                            <option value="M">M</option>
                            <option value="F">F</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="date_naissance" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" name="date_naissance" value="<?= $champ['date_naissance']; ?>">
                    </div>
                    <div class="col-md-5">
                        <label for="lieu_naissance" class="form-label">Lieu de naissance</label>
                        <input type="text" class="form-control" name="lieu_naissance" value="<?= htmlspecialchars($champ['lieu_naissance']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" name="adresse" value="<?= htmlspecialchars($champ['adresse']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" name="telephone" value="<?= htmlspecialchars($champ['telephone']); ?>">
                    </div>
                </div>

                <!-- Informations académiques -->
                <h5 class="mt-4 mb-3 text-primary">Informations académiques</h5>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="type_candidat" class="form-label">Type de candidat</label>
                        <select class="form-control" name="type_candidat">
                            <option value="<?= $champ['type_candidat']; ?>"><?= $champ['type_candidat']; ?></option>
                            <option value="Scolaire">Scolaire</option>
                            <option value="Entreprise">Entreprise</option>
                            <option value="Etranger">Étranger</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="serie_bacc" class="form-label">Série Bacc</label>
                        <select class="form-control" name="serie_bacc">
                            <option value="<?= $champ['serie_bacc']; ?>"><?= $champ['serie_bacc']; ?></option>
                            <?php
                            $series = ['A1', 'A2', 'C', 'D', 'L', 'OSE', 'S', 'G1', 'G2', 'FTG', 'Technique Tertiaire', 'Technique Industriel', 'Technique Génie Civil'];
                            foreach ($series as $s) {
                                echo "<option value='$s'>$s</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="mention_bacc" class="form-label">Mention Bacc</label>
                        <select class="form-control" name="mention_bacc">
                            <option value="<?= $champ['mention_bacc']; ?>"><?= $champ['mention_bacc']; ?></option>
                            <option value="Passable">Passable</option>
                            <option value="Assez Bien">Assez Bien</option>
                            <option value="Bien">Bien</option>
                            <option value="Très Bien">Très Bien</option>
                            <option value="En cours">En cours</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="annee_bacc" class="form-label">Année Bacc</label>
                        <input type="number" min="2010" class="form-control" name="annee_bacc" value="<?= htmlspecialchars($champ['annee_bacc']); ?>">
                    </div>
                    <div class="col-md-8">
                        <label for="centre" class="form-label">Centre de concours</label>
                        <select class="form-control" name="centre" required>
                            <option value="<?= $champ['centre']; ?>"><?= $champ['centre']; ?></option>
                            <?php
                            $reponse_centre = $bdd->query('SELECT nom_centre FROM centre WHERE etat_centre="OUI" ORDER BY nom_centre');
                            while ($donnees_centre = $reponse_centre->fetch()) {
                                echo "<option value='{$donnees_centre['nom_centre']}'>{$donnees_centre['nom_centre']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Champs cachés -->
                <input type="hidden" name="id_modif" value="<?= $id_change; ?>">
                <input type="hidden" name="modifie_par" value="<?= $_SESSION['nom'] . ' ' . $_SESSION['prenom']; ?>">
                <input type="hidden" name="date_modification" value="<?= date('d/m/Y H:i'); ?>">

            </div>

            <!-- Boutons -->
            <div class="card-footer d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary">⬅ Annuler</a>
                <button type="submit" class="btn btn-info">💾 Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
$menu = "";
require('template.php');
?>
