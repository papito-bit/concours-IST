<?php
ob_start();

session_start();
if(isset($_SESSION['id_utilisateur']) AND isset($_SESSION['email_concours'])){

    require __DIR__ . '/../../config/connect.php';

    // On récupère uniquement les candidats niveau DTSS
    $requette=$bdd->query('SELECT can.*, cen.nom_centre as centre 
        FROM candidats can 
        JOIN centre cen ON can.id_centre = cen.id_centre 
        WHERE can.id_annee = '.$_SESSION["id_annee_selected"].'
          AND can.niveau = "DTSS"
        ORDER BY can.id_candidat');
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Convocations DTSS 
      <a href="imprime_tout_convocation.php"> &nbsp;&nbsp;&nbsp; Imprimer tout</a>
    </h3>
  </div>
  <div class="card-body p-0">
    <table class="table table-striped projects">
      <thead>
        <tr>
          <th>Pos</th>
          <th>Nom</th>
          <th>Prénoms</th>
          <th>Centre</th>
          <th class="text-center">Parcours</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php while ($donnees=$requette->fetch()) { ?>
          <tr>
            <td><?php echo $donnees["id_candidat"] ?></td>
            <td><?php echo $donnees["nom"] ?></td>
            <td><?php echo $donnees["prenom"] ?></td>
            <td><?php echo $donnees["centre"] ?></td>
            <td class="project-state"><?php echo $donnees["parcours1"] ?></td>
            <td class="project-actions text-right">
              <!-- Bleu si non imprimé, vert si déjà imprimé -->
              <a class="btn btn-<?php echo ($donnees["convoc"] == 1) ? "success" : "primary"; ?> btn-sm" 
                 href="imprime_convocation.php?impr=<?php echo $donnees["id_candidat"] ?>">
                <i class="fas fa-print"></i> Imprimer
              </a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<?php 
$content = ob_get_clean();
$menu = "";
$menu_convoc="nav-item menu-open";
$convoc_dtss="nav-link active";
require __DIR__ . '/../../includes/template.php'; 
} else {
  header("Location:../../login.php");
} 
?>
