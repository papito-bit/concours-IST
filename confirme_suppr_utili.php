<?php
require_once __DIR__ . '/auth.php';
exiger_role(['superadmin', 'admin']);
require 'connect.php';

$id = filter_input(INPUT_GET, 'valeur_suppr', FILTER_VALIDATE_INT);
if (!$id || $id < 1 || $id === (int) ($_SESSION['id_utilisateur'] ?? 0)) {
    header('Location: utilisateur.php');
    exit;
}

$actorIsSuperadmin = strtolower((string) ($_SESSION['groupe'] ?? '')) === 'superadmin';

$targetStmt = $bdd->prepare('SELECT nom, prenom, id_role FROM utilisateur WHERE id_utilisateur = ?');
$targetStmt->execute([$id]);
$target = $targetStmt->fetch(PDO::FETCH_ASSOC);
if (!$target) {
    http_response_code(404);
    exit('Utilisateur introuvable.');
}

if (!$actorIsSuperadmin && (int) $target['id_role'] === 1) {
    http_response_code(403);
    exit('Accès refusé.');
}

ob_start();
?>
<div class="row">
    <div class="col-md-6">
        <a class="btn btn-primary" href="utilisateur.php">NON</a>
    </div>
    <div class="col-md-6">
        <form method="post" action="supprime_utilisateur.php">
            <?= csrf_field() ?>
            <input type="hidden" name="id_suppr" value="<?= (int) $id ?>">
            <button type="submit" class="btn btn-danger">OUI</button>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
$menu = 'Voulez-vous supprimer ' . $target['nom'] . ' ' . $target['prenom'];
$menu_utili = '';
require 'template.php';
