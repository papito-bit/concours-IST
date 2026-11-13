<?php
// Enregistrement d'un nouveau candidat (appelé par saisi_dts.php, saisi_dtss.php et saisi_ing.php).
// Toutes les données sont contrôlées et normalisées par ../../includes/validation_candidat.php.
require_once __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../../includes/journal_helper.php';
require_once __DIR__ . '/../../includes/validation_candidat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Méthode non autorisée.');
}
exiger_role(['superadmin', 'admin', 'controleur', 'controlleur', 'operateur']);

function refuser($message)
{
    echo '<script>alert(' . json_encode($message, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) . '); window.history.back();</script>';
    exit;
}

$id_annee = (int) ($_SESSION['id_annee_selected'] ?? 0);
list($d, $erreurs) = vc_valider_nouveau($bdd, $_POST, $id_annee);
if ($erreurs) {
    refuser("Enregistrement refusé :\n- " . implode("\n- ", $erreurs));
}

try {
    // Transaction : le numéro d'inscription est réservé puis l'insertion est validée (ou tout est annulé)
    $bdd->beginTransaction();

    $st = $bdd->prepare('SELECT last_number FROM parcours WHERE parcours_abrevie = ? FOR UPDATE');
    $st->execute([$d['parcours1']]);
    $num = (int) $st->fetchColumn() + 1;
    $bdd->prepare('UPDATE parcours SET last_number = ? WHERE parcours_abrevie = ?')->execute([$num, $d['parcours1']]);

    $req = $bdd->prepare('INSERT INTO candidats (
        nom, prenom, sexe, date_naissance, lieu_naissance, adresse, telephone,
        N_CIN, Date_CIN, Lieu_CIN, adresse_email, type_candidat, serie_bacc, annee_bacc, mention_bacc,
        parcours1, parcours2, num_arrivee, date_arrivee, mode_paiement, dossier_complet, obs,
        ecole, niveau, numero, date_saisi, id_centre, saisi_par, id_annee)
        VALUES (?,?,?,?,?,?,?, ?,?,?,?,?,?,?,?, ?,?,?,?,?,?,?, ?,?,?, NOW(), ?,?,?)');
    $req->execute([
        $d['nom'], $d['prenom'], $d['sexe'], $d['date_naissance'], $d['lieu_naissance'], $d['adresse'], $d['telephone'],
        $d['N_CIN'], $d['Date_CIN'], $d['Lieu_CIN'], $d['adresse_email'], $d['type_candidat'], $d['serie_bacc'], $d['annee_bacc'], $d['mention_bacc'],
        $d['parcours1'], $d['parcours2'], $d['num_arrivee'], $d['date_arrivee'], $d['mode_paiement'], $d['dossier_complet'], $d['obs'],
        $d['ecole'], $d['niveau'], $num, $d['id_centre'], (int) $_SESSION['id_utilisateur'], $id_annee,
    ]);
    $nouvel_id = (int) $bdd->lastInsertId();
    $bdd->commit();
} catch (Throwable $e) {
    if ($bdd->inTransaction()) {
        $bdd->rollBack();
    }
    refuser("Enregistrement annulé : ce candidat existe peut-être déjà, ou une donnée est invalide.");
}

$identite = $d['nom'] . ' ' . $d['prenom'];
$_SESSION['message'] = $identite . ' a bien été enregistré(e).';
journaliser('candidat_ajout', $identite . ' (' . $d['niveau'] . ' - ' . $d['ecole'] . ')', 'candidat', $nouvel_id);
notifier('Nouveau candidat : ' . $identite . ' (' . $d['niveau'] . ' - ' . $d['ecole'] . ')', 'liste_saisi.php');

$pages = ['DTS' => 'saisi_dts.php', 'DTSS' => 'saisi_dtss.php', 'INGENIORAT' => 'saisi_ing.php'];
header('Location: ' . $pages[$d['niveau']] . '?ecole=' . urlencode($d['ecole']));
exit;
