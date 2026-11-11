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

$nom = trim((string) ($_POST['nom'] ?? ''));
$prenom = trim((string) ($_POST['prenom'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$pseudo = trim((string) ($_POST['pseudo'] ?? ''));
$mdp = (string) ($_POST['mdp'] ?? '');
$role = strtolower(trim((string) ($_POST['id_role'] ?? '')));
$rolesAutorises = ['visiteur', 'operateur', 'controleur', 'admin', 'superadmin'];

if ($nom === '' || $prenom === '' || $pseudo === '' || strlen($mdp) < 10 || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($role, $rolesAutorises, true)) {
    http_response_code(422);
    exit('Données invalides. Le mot de passe doit contenir au moins 10 caractères.');
}
if ($role === 'superadmin' && strtolower((string) $_SESSION['groupe']) !== 'superadmin') {
    http_response_code(403);
    exit('Seul un superadministrateur peut créer un superadministrateur.');
}

$roleStmt = $bdd->prepare('SELECT id_role, libelle FROM roles WHERE LOWER(libelle) = ? LIMIT 1');
$roleStmt->execute([$role]);
$roleRow = $roleStmt->fetch(PDO::FETCH_ASSOC);
if (!$roleRow) {
    http_response_code(422);
    exit('Rôle invalide.');
}

try {
    $stmt = $bdd->prepare('INSERT INTO utilisateur (nom, prenom, email, pseudo, mdp, id_role) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$nom, $prenom, $email, $pseudo, password_hash($mdp, PASSWORD_DEFAULT), (int) $roleRow['id_role']]);
    $id = (int) $bdd->lastInsertId();
    journaliser('utilisateur_ajout', 'Compte ' . $pseudo . ' (rôle ' . $roleRow['libelle'] . ')', 'utilisateur', $id);
    header('Location: utilisateur.php');
    exit;
} catch (PDOException $e) {
    error_log('Création utilisateur échouée: ' . $e->getMessage());
    http_response_code(409);
    exit('Création impossible : vérifiez que le pseudo et l’adresse courriel ne sont pas déjà utilisés.');
}
