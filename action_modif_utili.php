<?php
require_once __DIR__ . '/auth.php';
exiger_role(['superadmin', 'admin']);
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Méthode non autorisée.');
}

csrf_require();

$id = filter_input(INPUT_POST, 'id_util', FILTER_VALIDATE_INT);
$nom = trim((string) ($_POST['nom'] ?? ''));
$prenom = trim((string) ($_POST['prenom'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$pseudo = trim((string) ($_POST['pseudo'] ?? ''));
$mdp = (string) ($_POST['mdp'] ?? '');
$roleId = filter_input(INPUT_POST, 'id_role', FILTER_VALIDATE_INT);

if (!$id || $id < 1 || $nom === '' || $prenom === '' || $pseudo === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$roleId) {
    http_response_code(422);
    exit('Données invalides.');
}

if ($mdp !== '' && strlen($mdp) < 10) {
    http_response_code(422);
    exit('Le mot de passe doit contenir au moins 10 caractères.');
}

$actorIsSuperadmin = strtolower((string) ($_SESSION['groupe'] ?? '')) === 'superadmin';

$targetStmt = $bdd->prepare('SELECT id_role FROM utilisateur WHERE id_utilisateur = ?');
$targetStmt->execute([$id]);
$targetRole = $targetStmt->fetchColumn();
if ($targetRole === false) {
    http_response_code(404);
    exit('Utilisateur introuvable.');
}

$roleStmt = $bdd->prepare('SELECT id_role FROM roles WHERE id_role = ?');
$roleStmt->execute([$roleId]);
if ($roleStmt->fetchColumn() === false) {
    http_response_code(422);
    exit('Rôle invalide.');
}

if (!$actorIsSuperadmin && ((int) $targetRole === 1 || (int) $roleId === 1)) {
    http_response_code(403);
    exit('Seul le superadmin peut modifier ou promouvoir un superadmin.');
}

$sql = 'UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, pseudo = ?, id_role = ?';
$params = [$nom, $prenom, $email, $pseudo, $roleId];

if ($mdp !== '') {
    $sql .= ', mdp = ?';
    $params[] = password_hash($mdp, PASSWORD_DEFAULT);
}

$sql .= ' WHERE id_utilisateur = ?';
$params[] = $id;

$req = $bdd->prepare($sql);
$req->execute($params);

header('Location: utilisateur.php');
exit;
