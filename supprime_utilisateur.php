<?php
require_once __DIR__ . '/auth.php';
exiger_role(['superadmin', 'admin']);
require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/journal_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Méthode non autorisée.');
}

csrf_require();

$id = filter_input(INPUT_POST, 'id_suppr', FILTER_VALIDATE_INT);
if (!$id || $id < 1 || $id === (int) ($_SESSION['id_utilisateur'] ?? 0)) {
    http_response_code(400);
    exit('Identifiant invalide ou suppression de son propre compte interdite.');
}

$actorIsSuperadmin = strtolower((string) ($_SESSION['groupe'] ?? '')) === 'superadmin';

$targetStmt = $bdd->prepare('SELECT pseudo, id_role FROM utilisateur WHERE id_utilisateur = ?');
$targetStmt->execute([$id]);
$user = $targetStmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    http_response_code(404);
    exit('Utilisateur introuvable.');
}

if (!$actorIsSuperadmin && (int) $user['id_role'] === 1) {
    http_response_code(403);
    exit('Seul le superadmin peut supprimer un superadmin.');
}

$delete = $bdd->prepare('DELETE FROM utilisateur WHERE id_utilisateur = ?');
$delete->execute([$id]);

if ($delete->rowCount() > 0) {
    journaliser('utilisateur_suppr', 'Compte ' . $user['pseudo'], 'utilisateur', $id);
    notifier('Utilisateur supprimé : ' . $user['pseudo'], 'journal.php?action=utilisateur_suppr');
}

header('Location: utilisateur.php');
exit;
