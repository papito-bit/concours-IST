<?php
ob_start();
session_start();

if (isset($_SESSION['id_utilisateur']) && isset($_SESSION['email_concours'])) {

    require('connect.php');

    // ✅ On récupère tous les candidats du niveau DTS (toutes écoles confondues)
    $requette = $bdd->prepare("
        SELECT can.*, cen.nom_centre AS centre, par.ecole_abrevie
        FROM candidats can
        JOIN centre cen ON can.id_centre = cen.id_centre
        JOIN parcours par ON can.parcours1 = par.parcours_abrevie
        WHERE can.id_annee = :annee
          AND can.niveau = 'DTS'
        ORDER BY can.id_candidat
    ");
    $requette->execute([':annee' => $_SESSION["id_annee_selected"]]);
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Convocations DTS – Tous parcours
      <a href="imprime_tout_convocation.php" class="btn btn-outline-primary btn-sm float-right">
        <i class="fas fa-print"></i> Imprimer tout
      </a>
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
          <th class="text-center">École</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i = 1;
        while ($donnees = $requette->fetch()) { 
        ?>
          <tr>
            <td><?= $i++; ?></td>
            <td><?= htmlspecialchars($donnees["nom"]); ?></td>
            <td><?= htmlspecialchars($donnees["prenom"]); ?></td>
            <td><?= htmlspecialchars($donnees["centre"]); ?></td>
            <td class="text-center"><?= htmlspecialchars($donnees["parcours1"]); ?></td>
            <td class="text-center"><?= htmlspecialchars($donnees["ecole_abrevie"]); ?></td>
            <td class="project-actions text-center">
              <a class="btn btn-<?= ($donnees["convoc"] == 1) ? "success" : "primary"; ?> btn-sm"
                 href="imprime_convocation.php?impr=<?= $donnees["id_candidat"]; ?>">
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
$menu_convoc = "nav-item menu-open";
$convoc_dts_egi = "nav-link active"; // tu peux renommer en $convoc_dts si tu veux
require('template.php'); 

} else {
    header("Location:login.php");
    exit();
}
?>
