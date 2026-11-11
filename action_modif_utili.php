<?php
require_once __DIR__ . '/auth.php';
exiger_role(['superadmin', 'admin']);
require_once __DIR__ . '/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Méthode non autorisée.');
}
csrf_require();

$id = filter_var($_POST['id_util'] ?? null, FILTER_VALIDATE_INT);
$nom = trim((string) ($_POST['nom'] ?? ''));
$prenom = trim((string) ($_POST['prenom'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$pseudo = trim((string) ($_POST['pseudo'] ?? ''));
$roleId = filter_var($_POST['id_role'] ?? null, FILTER_VALIDATE_INT);
$nouveauMdp = (string) ($_POST['mdp'] ?? '');

if (!$id || !$roleId || $nom === '' || $prenom === '' || $pseudo === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    exit('Données invalides.');
}

$actorIsSuperadmin = strtolower((string) $_SESSION['groupe']) === 'superadmin';
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
if (!$actorIsSuperadmin && ((int) $targetRole === 1 || $roleId === 1)) {
    http_response_code(403);
    exit('Un administrateur ne peut pas modifier ou promouvoir un superadministrateur.');
}
if ($nouveauMdp !== '' && strlen($nouveauMdp) < 10) {
    http_response_code(422);
    exit('Le nouveau mot de passe doit contenir au moins 10 caractères.');
}

$sql = 'UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, pseudo = ?, id_role = ?';
$params = [$nom, $prenom, $email, $pseudo, $roleId];
if ($nouveauMdp !== '') {
    $sql .= ', mdp = ?';
    $params[] = password_hash($nouveauMdp, PASSWORD_DEFAULT);
}
$sql .= ' WHERE id_utilisateur = ?';
$params[] = $id;
$update = $bdd->prepare($sql);
$update->execute($params);
header('Location: utilisateur.php');
exit;
