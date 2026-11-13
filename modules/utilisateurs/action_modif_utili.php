<?php
// Modification d'un compte utilisateur (réservé aux administrateurs).
require_once __DIR__ . '/../../includes/auth.php';
exiger_role(['superadmin', 'admin']);
require __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../../includes/journal_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Méthode non autorisée.');
}

function erreur_modif($message)
{
    http_response_code(422);
    exit(htmlspecialchars($message) . " <a href='utilisateur.php'>Retour</a>");
}

$id      = filter_var($_POST['id_util'] ?? null, FILTER_VALIDATE_INT);
$nom     = trim($_POST['nom'] ?? '');
$prenom  = trim($_POST['prenom'] ?? '');
$email   = trim($_POST['email'] ?? '');
$pseudo  = trim($_POST['pseudo'] ?? '');
$mdp     = $_POST['mdp'] ?? '';
$id_role = filter_var($_POST['id_role'] ?? null, FILTER_VALIDATE_INT);

if (!$id || !$id_role) {
    erreur_modif('Paramètres invalides.');
}
if ($nom === '' || $pseudo === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    erreur_modif('Nom, pseudo et adresse e-mail valides sont obligatoires.');
}

// Compte ciblé et rôle demandé
$st = $bdd->prepare('SELECT u.id_utilisateur, u.id_role, r.libelle FROM utilisateur u JOIN roles r ON r.id_role = u.id_role WHERE u.id_utilisateur = ?');
$st->execute([$id]);
$cible = $st->fetch(PDO::FETCH_ASSOC);
if (!$cible) {
    erreur_modif('Utilisateur introuvable.');
}
$st = $bdd->prepare('SELECT libelle FROM roles WHERE id_role = ?');
$st->execute([$id_role]);
$role_demande = $st->fetchColumn();
if ($role_demande === false) {
    erreur_modif('Rôle invalide.');
}

$moi_superadmin = strtolower($_SESSION['groupe'] ?? '') === 'superadmin';
// Un admin ne touche ni aux comptes superadmin, ni à ce rôle
if (!$moi_superadmin && (strtolower($cible['libelle']) === 'superadmin' || strtolower($role_demande) === 'superadmin')) {
    http_response_code(403);
    exit('Accès refusé : seul un superadmin peut gérer ce rôle.');
}
// On ne change pas son propre rôle (évite de se retirer ses droits)
if ($id === (int) $_SESSION['id_utilisateur'] && $id_role !== (int) $cible['id_role']) {
    erreur_modif('Vous ne pouvez pas modifier votre propre rôle.');
}

try {
    if ($mdp !== '') {
        $req = $bdd->prepare('UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, pseudo = ?, mdp = ?, id_role = ? WHERE id_utilisateur = ?');
        $ok = $req->execute([$nom, $prenom, $email, $pseudo, password_hash($mdp, PASSWORD_DEFAULT), $id_role, $id]);
    } else {
        // Mot de passe laissé vide : on conserve l'actuel
        $req = $bdd->prepare('UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, pseudo = ?, id_role = ? WHERE id_utilisateur = ?');
        $ok = $req->execute([$nom, $prenom, $email, $pseudo, $id_role, $id]);
    }
} catch (PDOException $e) {
    erreur_modif('Modification impossible : pseudo ou e-mail déjà utilisé.');
}

if ($ok) {
    journaliser('utilisateur_modif', 'Compte ' . $pseudo . ' (rôle ' . $role_demande . ($mdp !== '' ? ', mot de passe changé' : '') . ')', 'utilisateur', $id);
    header('Location: utilisateur.php');
    exit;
}
erreur_modif('Erreur de modification.');
