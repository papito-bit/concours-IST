<?php
session_start();
if (empty($_SESSION['id_utilisateur'])) {
    http_response_code(401);
    exit('Non autorisé.');
}
require('connect.php');
require_once __DIR__ . '/journal_helper.php';

if (!isset($_POST['id'], $_POST['field'], $_POST['value'])) {
    exit("Erreur de paramètre.");
}

$id = (int) $_POST['id'];
$field = trim($_POST['field']);
$value = trim($_POST['value']);

// Liste blanche des champs modifiables
$allowed_fields = [
    'nom', 'prenom', 'date_naissance', 'lieu_naissance', 'sexe', 
    'adresse', 'telephone', 'type_candidat', 'serie_bacc', 
    'annee_bacc', 'centre', 'parcours1', 'parcours2'
];

if (!in_array($field, $allowed_fields)) {
    exit("Champ non autorisé.");
}

// Ancienne valeur (pour le journal) : le champ est dans la liste blanche ci-dessus
$ancien = null;
if ($field !== 'centre') {
    $o = $bdd->prepare("SELECT $field FROM candidats WHERE id_candidat = ?");
    $o->execute([$id]);
    $ancien = $o->fetchColumn();
}

// Si le champ modifié est 'centre', on met à jour via jointure avec table centre
if ($field === 'centre') {
    $stmt = $bdd->prepare("SELECT id_centre FROM centre WHERE nom_centre = ?");
    $stmt->execute([$value]);
    $id_centre = $stmt->fetchColumn();
    if (!$id_centre) exit("Centre invalide");
    $stmt = $bdd->prepare("UPDATE candidats SET id_centre = ? WHERE id_candidat = ?");
    $stmt->execute([$id_centre, $id]);
} else {
    $stmt = $bdd->prepare("UPDATE candidats SET $field = ? WHERE id_candidat = ?");
    $stmt->execute([$value, $id]);
}

journaliser('candidat_modif', "Candidat n°$id, champ $field : '" . $ancien . "' -> '" . $value . "'", 'candidat', $id);

echo "ok";
