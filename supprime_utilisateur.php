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
$id = filter_var($_POST['id_suppr'] ?? null, FILTER_VALIDATE_INT);
if (!$id || $id === (int) $_SESSION['id_utilisateur']) {
    http_response_code(422);
    exit('Identifiant invalide ou suppression de son propre compte interdite.');
}

$target = $bdd->prepare('SELECT u.pseudo, u.id_role FROM utilisateur u WHERE u.id_utilisateur = ?');
$target->execute([$id]);
$user = $target->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    http_response_code(404);
    exit('Utilisateur introuvable.');
}
if ((int) $user['id_role'] === 1 && strtolower((string) $_SESSION['groupe']) !== 'superadmin') {
    http_response_code(403);
    exit('Seul un superadministrateur peut supprimer un superadministrateur.');
}
$delete = $bdd->prepare('DELETE FROM utilisateur WHERE id_utilisateur = ?');
$delete->execute([$id]);
if ($delete->rowCount() > 0) {
    journaliser('utilisateur_suppr', 'Compte ' . $user['pseudo'], 'utilisateur', $id);
    notifier('Utilisateur supprimé : ' . $user['pseudo'], 'journal.php?action=utilisateur_suppr');
}
header('Location: utilisateur.php');
exit;
