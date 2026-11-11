<?php
require_once __DIR__ . '/auth.php';
exiger_role(['superadmin', 'admin']);
require_once __DIR__ . '/connect.php';
$id = filter_input(INPUT_GET, 'id_utili', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: utilisateur.php');
    exit;
}
$stmt = $bdd->prepare('SELECT id_utilisateur, nom, prenom, email, pseudo, id_role FROM utilisateur WHERE id_utilisateur = ?');
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    http_response_code(404);
    exit('Utilisateur introuvable.');
}
if ((int) $user['id_role'] === 1 && strtolower((string) $_SESSION['groupe']) !== 'superadmin') {
    http_response_code(403);
    exit('Accès refusé.');
}
$roles = $bdd->query('SELECT id_role, libelle FROM roles ORDER BY id_role')->fetchAll(PDO::FETCH_ASSOC);
ob_start();
?>
<div class="card card-primary"><div class="card-header"><h3 class="card-title">Modifier l’utilisateur</h3></div>
<form action="action_modif_utili.php" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="id_util" value="<?= (int) $user['id_utilisateur'] ?>">
  <div class="card-body">
    <div class="form-group"><label>Nom</label><input class="form-control" name="nom" value="<?= h($user['nom']) ?>" required></div>
    <div class="form-group"><label>Prénom</label><input class="form-control" name="prenom" value="<?= h($user['prenom']) ?>" required></div>
    <div class="form-group"><label>Courriel</label><input class="form-control" type="email" name="email" value="<?= h($user['email']) ?>" required></div>
    <div class="form-group"><label>Pseudo</label><input class="form-control" name="pseudo" value="<?= h($user['pseudo']) ?>" required></div>
    <div class="form-group"><label>Nouveau mot de passe <small>(laisser vide pour ne pas le modifier; 10 caractères minimum)</small></label><input class="form-control" type="password" name="mdp" minlength="10" autocomplete="new-password"></div>
    <div class="form-group"><label>Rôle</label><select class="form-control" name="id_role" required>
      <?php foreach ($roles as $role): if (!$actorIsSuperadmin && (int) $role['id_role'] === 1) continue; ?>
        <option value="<?= (int) $role['id_role'] ?>" <?= (int) $role['id_role'] === (int) $user['id_role'] ? 'selected' : '' ?>><?= h($role['libelle']) ?></option>
      <?php endforeach; ?>
    </select></div>
  </div>
  <div class="card-footer"><button class="btn btn-primary" type="submit">Enregistrer</button><a class="btn btn-secondary" href="utilisateur.php">Annuler</a></div>
</form></div>
<?php
$content = ob_get_clean();
$menu = 'Modification utilisateur';
require __DIR__ . '/template.php';
