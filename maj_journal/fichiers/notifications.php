<?php
ob_start();
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/journal_helper.php';

$moi = (int) $_SESSION['id_utilisateur'];
$erreur = '';
$liste = [];

try {
    // Ouvrir une notification : on la marque lue puis on va vers son lien
    if (isset($_GET['ouvrir'])) {
        $id = (int) $_GET['ouvrir'];
        $st = $bdd->prepare('SELECT lien FROM notifications WHERE id = ? AND id_utilisateur = ?');
        $st->execute([$id, $moi]);
        $lien = $st->fetchColumn();
        $bdd->prepare('UPDATE notifications SET lu = 1 WHERE id = ? AND id_utilisateur = ?')->execute([$id, $moi]);
        // Lien interne uniquement (pas de redirection vers un autre site)
        if ($lien && preg_match('/^[A-Za-z0-9_\-]+\.php(\?[A-Za-z0-9_=&%\.\-]*)?$/', $lien)) {
            header('Location: ' . $lien);
        } else {
            header('Location: notifications.php');
        }
        exit;
    }

    if (isset($_POST['tout_lire'])) {
        $bdd->prepare('UPDATE notifications SET lu = 1 WHERE id_utilisateur = ? AND lu = 0')->execute([$moi]);
        header('Location: notifications.php');
        exit;
    }

    $st = $bdd->prepare('SELECT * FROM notifications WHERE id_utilisateur = ? ORDER BY id DESC LIMIT 100');
    $st->execute([$moi]);
    $liste = $st->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $erreur = "La table des notifications est introuvable. Lance INSTALLER.bat (ou exécute base.sql dans phpMyAdmin).";
}
?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Mes notifications</h3>
    <div class="card-tools">
      <form method="post" class="d-inline">
        <button type="submit" name="tout_lire" value="1" class="btn btn-sm btn-primary">
          <i class="fas fa-check-double"></i> Tout marquer comme lu
        </button>
      </form>
    </div>
  </div>
  <div class="card-body p-0">
    <?php if ($erreur) { ?>
      <div class="alert alert-warning m-3"><?= h($erreur) ?></div>
    <?php } elseif (!$liste) { ?>
      <p class="text-muted m-3">Aucune notification pour le moment.</p>
    <?php } else { ?>
      <ul class="list-group list-group-flush">
        <?php foreach ($liste as $n) { ?>
          <li class="list-group-item d-flex justify-content-between align-items-center <?= $n['lu'] ? '' : 'font-weight-bold' ?>">
            <a href="notifications.php?ouvrir=<?= (int) $n['id'] ?>" class="text-dark">
              <i class="fas fa-circle mr-2 <?= $n['lu'] ? 'text-muted' : 'text-primary' ?>" style="font-size:.6rem"></i>
              <?= h($n['message']) ?>
            </a>
            <small class="text-muted"><?= h(date('d/m/Y H:i', strtotime($n['created_at']))) ?></small>
          </li>
        <?php } ?>
      </ul>
    <?php } ?>
  </div>
</div>
<?php
$content = ob_get_clean();
$menu = "Notifications";
require 'template.php';
