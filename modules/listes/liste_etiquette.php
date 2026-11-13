<?php
ob_start();
session_start();

// --- Vérification de session ---
if(!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['email_concours'])){
    header("Location:../../login.php");
    exit;
}

require __DIR__ . '/../../config/connect.php';

// --- Niveaux disponibles ---
$niveaux = ['DTS', 'DTSS', 'INGENIORAT'];

// --- Récupération de tous les centres ---
$centres = $bdd->query("SELECT * FROM centre ORDER BY nom_centre")->fetchAll(PDO::FETCH_ASSOC);

// --- Centre actif (par défaut le premier centre) ---
$centreActif = isset($_GET['centre']) ? $_GET['centre'] : ($centres[0]['id_centre'] ?? null);

// --- Niveau actif (par défaut : DTS) ---
$niveauActif = isset($_GET['niveau']) ? $_GET['niveau'] : 'DTS';

// --- Nom du centre actif ---
$nomCentreReq = $bdd->prepare("SELECT nom_centre FROM centre WHERE id_centre=?");
$nomCentreReq->execute([$centreActif]);
$nomCentre = $nomCentreReq->fetchColumn();

// --- Récupération des salles du centre actif ---
$salles = $bdd->prepare("SELECT * FROM salle WHERE id_centre=? ORDER BY nom_salle");
$salles->execute([$centreActif]);
$salles = $salles->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Étiquettes — <?php echo htmlspecialchars($niveauActif); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f8f9fa;
    font-family: Arial, sans-serif;
}

/* Style des étiquettes */
.etiquette {
    border: 1px solid #000;
    border-radius: 5px;
    width: 8.5cm;
    height: 3.5cm;
    margin: 5px;
    padding: 5px 10px;
    display: inline-block;
    font-size: 13px;
    line-height: 1.2em;
    background: #fff;
}

.etiquette strong { font-size: 14px; }
.etiquette .nom {
    font-weight: bold;
    font-size: 15px;
    text-transform: uppercase;
}
.etiquette .ligne { margin-top: 3px; }

.page-break { page-break-before: always; }

@media print {
    #btnPrint, .nav-tabs, .card-header, .navbar, .main-sidebar {
        display: none !important;
    }
    body {
        background: white;
        margin: 0;
    }
    .etiquette {
        page-break-inside: avoid;
    }
}
</style>
</head>

<body>

<div class="container mt-4">

<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">Étiquettes des candidats</h3>
    <button id="btnPrint" class="btn btn-sm btn-secondary" onclick="window.print();">
      <i class="fas fa-print"></i> Imprimer
    </button>
  </div>

  <div class="card-body p-3">

    <!-- Onglets des centres -->
    <ul class="nav nav-tabs" id="centreTabs" role="tablist">
      <?php foreach($centres as $centre): ?>
        <li class="nav-item" role="presentation">
          <a class="nav-link <?php echo ($centre['id_centre'] == $centreActif) ? 'active' : ''; ?>"
             href="liste_etiquette.php?centre=<?php echo $centre['id_centre']; ?>&niveau=<?php echo $niveauActif; ?>">
             <?php echo htmlspecialchars($centre['nom_centre']); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Onglets des niveaux -->
    <ul class="nav nav-tabs mt-3" id="niveauTabs" role="tablist">
      <?php foreach($niveaux as $n): ?>
        <li class="nav-item" role="presentation">
          <a class="nav-link <?php echo ($niveauActif == $n) ? 'active' : ''; ?>"
             href="liste_etiquette.php?centre=<?php echo $centreActif; ?>&niveau=<?php echo $n; ?>">
             <?php echo $n; ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Contenu principal -->
    <div class="mt-4">
      <?php
      $nb_salles = count($salles);
      $i_salle = 0;
      foreach($salles as $salle):
        $i_salle++;

        $req = $bdd->prepare("SELECT * FROM candidats WHERE id_annee=? AND id_salle=? AND niveau=? ORDER BY nom, prenom");
        $req->execute([$_SESSION["id_annee_selected"], $salle['id_salle'], $niveauActif]);
        $candidats = $req->fetchAll(PDO::FETCH_ASSOC);
      ?>

      <h5 class="mt-4 mb-2 text-center">
        Centre : <strong><?php echo htmlspecialchars($nomCentre); ?></strong> |
        Niveau : <strong><?php echo htmlspecialchars($niveauActif); ?></strong> |
        Salle : <strong><?php echo htmlspecialchars($salle['nom_salle']); ?></strong>
      </h5>

      <?php if(empty($candidats)): ?>
        <p class="text-center text-danger">Aucun candidat dans cette salle.</p>
      <?php else: ?>
        <div class="d-flex flex-wrap justify-content-start">
          <?php foreach($candidats as $cand): ?>
            <div class="etiquette">
              <div class="nom"><?php echo htmlspecialchars($cand['nom'] . ' ' . $cand['prenom']); ?></div>
              <div class="ligne">Parcours : <strong><?php echo htmlspecialchars($cand['parcours1']); ?></strong></div>
              <div class="ligne">Centre : <?php echo htmlspecialchars($nomCentre); ?></div>
              <div class="ligne">Salle : <?php echo htmlspecialchars($salle['nom_salle']); ?></div>
              <div class="ligne">Niveau : <?php echo htmlspecialchars($niveauActif); ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($i_salle < $nb_salles): ?>
        <div class="page-break"></div>
      <?php endif; ?>

      <?php endforeach; ?>
    </div>

  </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$content = ob_get_clean();
$menu = "Étiquettes des candidats";
$menu_etiquette = "nav-item menu-open";
$liste_etiquette = "nav-link active";
require __DIR__ . '/../../includes/template.php';
?>
