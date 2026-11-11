<?php
// À ouvrir UNE SEULE FOIS (connecté en superadmin), puis SUPPRIMER ce fichier.
require_once __DIR__ . '/auth.php';
exiger_role(['superadmin']);
require 'connect.php';

$n = 0;
foreach ($bdd->query('SELECT id_utilisateur, mdp FROM utilisateur')->fetchAll() as $u) {
    if (empty(password_get_info($u['mdp'])['algo'])) {
        $maj = $bdd->prepare('UPDATE utilisateur SET mdp = ? WHERE id_utilisateur = ?');
        $maj->execute([password_hash($u['mdp'], PASSWORD_DEFAULT), $u['id_utilisateur']]);
        $n++;
    }
}
echo "$n mot(s) de passe haché(s). Supprime maintenant ce fichier.";
