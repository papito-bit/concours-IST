<?php
require_once __DIR__ . '/../../includes/auth.php';
exiger_role(['superadmin', 'admin']);
require __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../../includes/journal_helper.php';

$nom    = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$email  = trim($_POST['email'] ?? '');
$pseudo = trim($_POST['pseudo'] ?? '');
$mdp    = $_POST['mdp'] ?? '';
$role   = $_POST['id_role'] ?? '';   // libellé du rôle choisi dans le formulaire

// Un admin ne peut pas créer un superadmin
if ($role === 'superadmin' && strtolower($_SESSION['groupe']) !== 'superadmin') {
    exit('Accès refusé.');
}
if ($pseudo === '' || $mdp === '') {
    exit("Erreur : le pseudo et le mot de passe sont obligatoires. <a href='ajout_utili.php'>Retour</a>");
}

$req = $bdd->prepare('INSERT INTO utilisateur (nom, prenom, email, pseudo, mdp, id_role)
                      VALUES (?, ?, ?, ?, ?, (SELECT id_role FROM roles WHERE libelle = ?))');
try {
    $ok = $req->execute([$nom, $prenom, $email, $pseudo, password_hash($mdp, PASSWORD_DEFAULT), $role]);
} catch (PDOException $e) {
    $ok = false;
}

if ($ok) {
    journaliser('utilisateur_ajout', 'Compte ' . $pseudo . ' (rôle ' . $role . ')', 'utilisateur', (int) $bdd->lastInsertId());
    notifier('Nouvel utilisateur : ' . $pseudo . ' (' . $role . ')', 'journal.php?action=utilisateur_ajout');
    header('Location: utilisateur.php');
    exit;
}
echo "Erreur d'enregistrement : pseudo déjà utilisé ou rôle invalide. <a href='ajout_utili.php'>Retour</a>";
