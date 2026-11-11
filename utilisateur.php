<?php
require_once __DIR__ . '/auth.php';
exiger_role(['superadmin', 'admin']);
require_once __DIR__ . '/connect.php';
$rows = $bdd->query('SELECT u.id_utilisateur, u.nom, u.prenom, u.email, u.pseudo, u.id_role, r.libelle AS role_libelle FROM utilisateur u LEFT JOIN roles r ON r.id_role = u.id_role ORDER BY u.id_utilisateur')->fetchAll(PDO::FETCH_ASSOC);
ob_start();
?>
<div class="card"><div class="card-header"><h3 class="card-title">Utilisateurs <a href="ajout_utili.php" class="btn btn-sm btn-primary">Ajouter</a></h3></div>
<div class="card-body table-responsive"><table class="table table-striped"><thead><tr><th>Nom</th><th>Prénom</th><th>Courriel</th><th>Pseudo</th><th>Rôle</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($rows as $user): ?>
<tr><td><?= h($user['nom']) ?></td><td><?= h($user['prenom']) ?></td><td><?= h($user['email']) ?></td><td><?= h($user['pseudo']) ?></td><td><?= h($user['role_libelle'] ?? 'Inconnu') ?></td><td>
<?php if ((int) $user['id_role'] !== 1 || strtolower((string) $_SESSION['groupe']) === 'superadmin'): ?>
<a class="btn btn-sm btn-info" href="modif_utilisateur.php?id_utili=<?= (int) $user['id_utilisateur'] ?>">Modifier</a>
<?php endif; ?>
<?php if ((int) $user['id_utilisateur'] !== (int) $_SESSION['id_utilisateur'] && ((int) $user['id_role'] !== 1 || strtolower((string) $_SESSION['groupe']) === 'superadmin')): ?>
<a class="btn btn-sm btn-danger" href="confirme_suppr_utili.php?valeur_suppr=<?= (int) $user['id_utilisateur'] ?>">Supprimer</a>
<?php endif; ?></td></tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php
$content = ob_get_clean();
$menu = 'Utilisateurs';
require __DIR__ . '/template.php';
