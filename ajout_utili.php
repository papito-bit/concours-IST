<?php
require_once __DIR__ . '/auth.php';
exiger_role(['superadmin', 'admin']);
ob_start();
?>
<div class="card card-primary"><div class="card-header"><h3 class="card-title">Créer un utilisateur</h3></div>
<form action="action_ajout_utili.php" method="post">
  <?= csrf_field() ?>
  <div class="card-body">
    <div class="form-group"><label>Nom</label><input class="form-control" name="nom" required></div>
    <div class="form-group"><label>Prénom</label><input class="form-control" name="prenom" required></div>
    <div class="form-group"><label>Courriel</label><input class="form-control" type="email" name="email" required></div>
    <div class="form-group"><label>Pseudo</label><input class="form-control" name="pseudo" required></div>
    <div class="form-group"><label>Mot de passe (10 caractères minimum)</label><input class="form-control" type="password" name="mdp" minlength="10" autocomplete="new-password" required></div>
    <div class="form-group"><label>Rôle</label><select class="form-control" name="id_role" required>
      <option value="">Choisir</option><option value="visiteur">Visiteur</option><option value="operateur">Opérateur</option><option value="controleur">Contrôleur</option><option value="admin">Administrateur</option>
      <?php if (strtolower((string) $_SESSION['groupe']) === 'superadmin'): ?><option value="superadmin">Superadministrateur</option><?php endif; ?>
    </select></div>
  </div>
  <div class="card-footer"><button class="btn btn-primary" type="submit">Ajouter</button><a class="btn btn-secondary" href="utilisateur.php">Annuler</a></div>
</form></div>
<?php
$content = ob_get_clean();
$menu = 'Nouvel utilisateur';
require __DIR__ . '/template.php';
