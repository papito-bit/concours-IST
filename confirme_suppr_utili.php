<?php
require_once __DIR__ . '/auth.php';
exiger_role(['superadmin', 'admin']);
require_once __DIR__ . '/connect.php';
$id = filter_input(INPUT_GET, 'valeur_suppr', FILTER_VALIDATE_INT);
if (!$id || $id === (int) $_SESSION['id_utilisateur']) {
    http_response_code(400);
    exit('Identifiant invalide.');
}
$stmt = $bdd->prepare('SELECT nom, prenom, id_role FROM utilisateur WHERE id_utilisateur = ?');
$stmt->execute([$id]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$target) {
    http_response_code(404);
    exit('Utilisateur introuvable.');
}
if ((int) $target['id_role'] === 1 && strtolower((string) $_SESSION['groupe']) !== 'superadmin') {
    http_response_code(403);
    exit('Accès refusé.');
}
ob_start();
?>
<form action="supprime_utilisateur.php" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="id_suppr" value="<?= (int) $id ?>">
  <p>Confirmer la suppression de <?= h($target['nom'] . ' ' . $target['prenom']) ?> ?</p>
  <a class="btn btn-secondary" href="utilisateur.php">Annuler</a>
  <button class="btn btn-danger" type="submit">Supprimer définitivement</button>
</form>
<?php
$content = ob_get_clean();
$menu = 'Confirmation de suppression';
require __DIR__ . '/template.php';
