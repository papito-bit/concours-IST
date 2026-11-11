<?php
session_start();
require 'connect.php';
require_once __DIR__ . '/journal_helper.php';

$pseudo = trim($_POST['email_concours'] ?? '');
$mdp    = $_POST['mdp_concours'] ?? '';

$req = $bdd->prepare('
    SELECT u.*, r.libelle AS role_libelle
    FROM utilisateur u
    JOIN roles r ON u.id_role = r.id_role
    WHERE u.pseudo = ?
');
$req->execute([$pseudo]);
$resultat = $req->fetch();

$connecte = false;
if ($resultat) {
    $stocke = $resultat['mdp'];
    $info   = password_get_info($stocke);

    if (!empty($info['algo'])) {
        // Mot de passe déjà haché
        $connecte = password_verify($mdp, $stocke);
    } elseif (hash_equals($stocke, $mdp)) {
        // Ancien mot de passe en clair : accepté une dernière fois, puis haché
        $connecte = true;
        $maj = $bdd->prepare('UPDATE utilisateur SET mdp = ? WHERE id_utilisateur = ?');
        $maj->execute([password_hash($mdp, PASSWORD_DEFAULT), $resultat['id_utilisateur']]);
    }
}

if (!$connecte) {
    journaliser('connexion_echec', 'pseudo : ' . $pseudo, null, null, $pseudo !== '' ? $pseudo : 'inconnu');
    // Alerte aux administrateurs : 3 échecs en 10 minutes pour le même pseudo
    try {
        $c = $bdd->prepare("SELECT COUNT(*) FROM journal_activite
                            WHERE action = 'connexion_echec' AND details = ?
                            AND created_at > (NOW() - INTERVAL 10 MINUTE)");
        $c->execute(['pseudo : ' . $pseudo]);
        if ((int) $c->fetchColumn() === 3) {
            notifier("Alerte : 3 échecs de connexion pour '" . $pseudo . "' en 10 minutes", 'journal.php?action=connexion_echec');
        }
    } catch (Throwable $e) {
        // on ignore
    }
    header('Location: login.php?erreur=erreur');
    exit;
}

session_regenerate_id(true);
$_SESSION['id_utilisateur'] = $resultat['id_utilisateur'];
$_SESSION['email_concours'] = $resultat['pseudo'];
$_SESSION['nom']            = $resultat['nom'];
$_SESSION['prenom']         = $resultat['prenom'];
$_SESSION['groupe']         = $resultat['role_libelle'];
$_SESSION['ecole']          = $resultat['ecole'];
$_SESSION['niveau']         = $resultat['niveau'];

journaliser('connexion');

header('Location: index.php');
exit;
