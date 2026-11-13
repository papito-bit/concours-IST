<?php
// API : modification d'un champ d'un candidat (appelée par la page "Candidats").
// Réponse : "ok:<valeur enregistrée>" si succès, sinon un message d'erreur avec le code HTTP adapté.
session_start();

function reponse($code, $texte)
{
    http_response_code($code);
    exit($texte);
}

if (empty($_SESSION['id_utilisateur'])) {
    reponse(401, 'Session expirée : reconnectez-vous.');
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    reponse(405, 'Méthode non autorisée.');
}
$groupe = strtolower($_SESSION['groupe'] ?? '');
$privilegies = ['superadmin', 'admin', 'controleur', 'controlleur'];
if (!in_array($groupe, array_merge($privilegies, ['operateur']), true)) {
    reponse(403, 'Droits insuffisants.');
}

require __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../../includes/journal_helper.php';
require_once __DIR__ . '/../../includes/validation_candidat.php';

$id    = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$field = trim($_POST['field'] ?? '');
$value = trim((string) ($_POST['value'] ?? ''));
if (!$id || $field === '') {
    reponse(400, 'Paramètres invalides.');
}

// Le candidat doit exister dans l'année sélectionnée ; un opérateur ne modifie que ses propres saisies
$st = $bdd->prepare('SELECT * FROM candidats WHERE id_candidat = ? AND id_annee = ?');
$st->execute([$id, $_SESSION['id_annee_selected'] ?? 0]);
$cand = $st->fetch(PDO::FETCH_ASSOC);
if (!$cand) {
    reponse(404, "Candidat introuvable pour l'année sélectionnée.");
}
if (!in_array($groupe, $privilegies, true) && (int) $cand['saisi_par'] !== (int) $_SESSION['id_utilisateur']) {
    reponse(403, 'Vous ne pouvez modifier que les candidats que vous avez saisis.');
}

// Mêmes règles que pour un nouveau candidat (nom en majuscules, téléphone, dates, etc.)
list($colonne, $nouveau, $erreur) = vc_valider_champ($bdd, $field, $value, $cand);
if ($erreur !== null) {
    $code = (strpos($erreur, 'notes') !== false) ? 409 : 422;
    reponse($code, $erreur);
}

$ancien = $cand[$colonne] ?? null;
$maj = $bdd->prepare("UPDATE candidats SET $colonne = ?, modifie_par = ?, date_modification = NOW() WHERE id_candidat = ?");
$maj->execute([$nouveau, $_SESSION['id_utilisateur'], $id]);

journaliser('candidat_modif', "Candidat n°$id, champ $field : '" . $ancien . "' -> '" . $nouveau . "'", 'candidat', $id);

// La valeur affichée est la valeur normalisée (ex. nom en majuscules)
echo 'ok:' . ($field === 'centre' ? $value : (string) $nouveau);
