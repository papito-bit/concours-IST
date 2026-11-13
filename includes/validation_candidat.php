<?php
// validation_candidat.php : règles de saisie d'un candidat (source unique, côté serveur).
// Utilisé par ../modules/candidats/enregistre_candidat.php (nouveau candidat) et ../modules/candidats/update_candidat_ajax.php (modification d'une cellule).
// Le même jeu de règles existe côté navigateur dans js/validation_saisie.js (confort de saisie) ;
// seul le serveur fait foi.

const VC_LETTRES = '/^[\p{L}]+(?:[\p{L}\s\'’\-]*[\p{L}])?$/u';

$VC_LISTES = [
    'sexe'          => ['M', 'F'],
    'type_candidat' => ['Scolaire', 'Entreprise', 'Etranger'],
    'mode_paiement' => ['Versement bancaire', 'Versement caisse', 'Virement bancaire', 'Chèque', 'Autres'],
    'ecole'         => ['EGI', 'EGMCS', 'EGCGN'],
    'niveau'        => ['DTS', 'DTSS', 'INGENIORAT'],
    'serie_DTS'     => ['A1', 'A2', 'C', 'D', 'L', 'ES', 'OSE', 'S', 'Technique Industriel', 'Technique Génie Civil', 'Technique Tertiaire', 'G1', 'G2', 'CG'],
    'serie_DTSS'    => ['DTS', 'Bacc+2'],
    'serie_INGENIORAT' => ['DTS', 'DTSS', 'Bacc+2', 'Bacc+3', 'Licence'],
    'mention_DTS'   => ['Passable', 'Assez Bien', 'Bien', 'Très Bien'],
    'mention_DTSS'  => ['Passable', 'Assez Bien', 'Bien', 'Très Bien', 'En cours'],
    'mention_INGENIORAT' => ['Passable', 'Assez Bien', 'Bien', 'Très Bien', 'En cours'],
];
// Niveau du formulaire -> grade_abrevie de la table parcours
$VC_GRADE = ['DTS' => 'DTS', 'DTSS' => 'DTSS', 'INGENIORAT' => 'ING'];

function vc_espaces($v)
{
    return trim(preg_replace('/\s+/u', ' ', (string) $v));
}

/** Nom : lettres, espaces, tirets, apostrophes ; toujours en MAJUSCULES. */
function vc_nom($v, &$err)
{
    $v = vc_espaces($v);
    if ($v === '') { $err = 'Le nom est obligatoire.'; return null; }
    if (!preg_match(VC_LETTRES, $v) || mb_strlen($v) < 2 || mb_strlen($v) > 100) {
        $err = 'Le nom ne doit contenir que des lettres (2 à 100 caractères).';
        return null;
    }
    return mb_strtoupper($v, 'UTF-8');
}

/** Prénoms : lettres uniquement, première lettre de chaque prénom en majuscule. */
function vc_prenom($v, &$err, $obligatoire = false)
{
    $v = vc_espaces($v);
    if ($v === '') {
        if ($obligatoire) { $err = 'Le prénom est obligatoire.'; }
        return null;
    }
    if (!preg_match(VC_LETTRES, $v) || mb_strlen($v) > 150) {
        $err = 'Le prénom ne doit contenir que des lettres.';
        return null;
    }
    return mb_convert_case(mb_strtolower($v, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
}

/** Téléphone malgache : 03x xx xxx xx (032, 033, 034, 037, 038) ou fixe 020 xx xxx xx. Stocké en 10 chiffres. */
function vc_telephone($v, &$err, $obligatoire = true)
{
    $brut = preg_replace('/[\s.\-()]/', '', trim((string) $v));
    if ($brut === '') {
        if ($obligatoire) { $err = 'Le numéro de téléphone est obligatoire.'; }
        return null;
    }
    $brut = preg_replace('/^(\+261|00261|261)/', '0', $brut);
    if (!preg_match('/^(03[23478]\d{7}|020\d{7})$/', $brut)) {
        $err = 'Numéro de téléphone invalide (exemple : 034 12 345 67).';
        return null;
    }
    return $brut;
}

/** N° CIN : 12 chiffres. Stocké sans espaces. */
function vc_cin($v, &$err, $obligatoire = false)
{
    $brut = preg_replace('/[\s.\-]/', '', trim((string) $v));
    if ($brut === '') {
        if ($obligatoire) { $err = 'Le numéro de CIN est obligatoire.'; }
        return null;
    }
    if (!preg_match('/^\d{12}$/', $brut)) {
        $err = 'Le numéro de CIN doit comporter exactement 12 chiffres.';
        return null;
    }
    return $brut;
}

/** Date au format AAAA-MM-JJ, comprise entre $min et $max (objets DateTime ou null). */
function vc_date($v, &$err, $libelle, ?DateTime $min = null, ?DateTime $max = null, $obligatoire = true)
{
    $v = trim((string) $v);
    if ($v === '') {
        if ($obligatoire) { $err = "$libelle : date obligatoire."; }
        return null;
    }
    $d = DateTime::createFromFormat('!Y-m-d', $v);
    if (!$d || $d->format('Y-m-d') !== $v) {
        $err = "$libelle : date invalide.";
        return null;
    }
    if (($min && $d < $min) || ($max && $d > $max)) {
        $err = "$libelle : date hors de la période autorisée"
             . ($min ? ' (à partir du ' . $min->format('d/m/Y') . ')' : '')
             . ($max ? ' (jusqu\'au ' . $max->format('d/m/Y') . ')' : '') . '.';
        return null;
    }
    return $v;
}

function vc_aujourdhui() { return new DateTime('today'); }
function vc_il_y_a($ans) { return (new DateTime('today'))->modify("-$ans years"); }
function vc_apres(DateTime $d, $ans) { return (clone $d)->modify("+$ans years"); }

function vc_email($v, &$err, $obligatoire = false)
{
    $v = strtolower(trim((string) $v));
    if ($v === '') {
        if ($obligatoire) { $err = "L'adresse e-mail est obligatoire."; }
        return null;
    }
    if (!filter_var($v, FILTER_VALIDATE_EMAIL) || strlen($v) > 200) {
        $err = "Adresse e-mail invalide.";
        return null;
    }
    return $v;
}

/** Lieu (naissance, délivrance du CIN) : lettres, chiffres et ponctuation courante ; première lettre en majuscule. */
function vc_lieu($v, &$err, $libelle, $obligatoire = true)
{
    $v = vc_espaces($v);
    if ($v === '') {
        if ($obligatoire) { $err = "$libelle est obligatoire."; }
        return null;
    }
    if (!preg_match('/^[\p{L}\p{N}][\p{L}\p{N}\s\'’\-\.,\/()]*$/u', $v) || mb_strlen($v) < 2 || mb_strlen($v) > 100) {
        $err = "$libelle est invalide (lettres et chiffres, 2 à 100 caractères).";
        return null;
    }
    return mb_convert_case(mb_strtolower($v, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
}

function vc_adresse($v, &$err, $obligatoire = true)
{
    $v = vc_espaces($v);
    if ($v === '') {
        if ($obligatoire) { $err = "L'adresse est obligatoire."; }
        return null;
    }
    if (mb_strlen($v) < 3 || mb_strlen($v) > 250 || preg_match('/[<>]/', $v)) {
        $err = "Adresse invalide (3 à 250 caractères, sans < ni >).";
        return null;
    }
    return $v;
}

function vc_annee_bacc($v, &$err, $obligatoire = true, $annee_naissance = null)
{
    $v = trim((string) $v);
    if ($v === '') {
        if ($obligatoire) { $err = "L'année d'obtention est obligatoire."; }
        return null;
    }
    $max = (int) date('Y');
    if (!ctype_digit($v) || (int) $v < 1990 || (int) $v > $max) {
        $err = "Année d'obtention invalide (entre 1990 et $max).";
        return null;
    }
    if ($annee_naissance && (int) $v < $annee_naissance + 14) {
        $err = "Année d'obtention incompatible avec la date de naissance.";
        return null;
    }
    return (int) $v;
}

function vc_num_arrivee($v, &$err)
{
    $v = trim((string) $v);
    if ($v === '') { $err = "Le numéro d'arrivée est obligatoire."; return null; }
    if (!preg_match('/^[A-Za-z0-9\/\-]{1,25}$/', $v)) {
        $err = "Numéro d'arrivée invalide (chiffres, lettres, / et - uniquement).";
        return null;
    }
    return $v;
}

function vc_choix($v, array $liste, &$err, $libelle, $obligatoire = true)
{
    $v = trim((string) $v);
    if ($v === '') {
        if ($obligatoire) { $err = "$libelle est obligatoire."; }
        return null;
    }
    if (!in_array($v, $liste, true)) { $err = "$libelle : valeur non autorisée."; return null; }
    return $v;
}

/**
 * Valide un nouveau candidat à partir de $_POST.
 * Retourne [$donnees, $erreurs] ; $erreurs est vide si tout est correct.
 */
function vc_valider_nouveau(PDO $bdd, array $p, $id_annee)
{
    global $VC_LISTES, $VC_GRADE;
    $e = [];
    $d = [];
    $err = null;
    $set = function ($champ, $valeur) use (&$e, &$d, &$err) {
        if ($err !== null) { $e[$champ] = $err; }
        $d[$champ] = $valeur;
        $err = null;
    };

    // Contexte : niveau et école (listes fermées)
    $niveau = trim($p['niveau'] ?? '');
    $ecole  = trim($p['ecole'] ?? '');
    $set('niveau', vc_choix($niveau, $VC_LISTES['niveau'], $err, 'Niveau'));
    $set('ecole', vc_choix($ecole, $VC_LISTES['ecole'], $err, 'École'));
    if (isset($e['niveau']) || isset($e['ecole'])) {
        return [$d, $e];
    }
    $hors_dts = ($niveau !== 'DTS');   // CIN, e-mail obligatoires pour DTSS et Ingéniorat

    $set('nom', vc_nom($p['nom'] ?? '', $err));
    $set('prenom', vc_prenom($p['prenom'] ?? '', $err));
    $set('sexe', vc_choix($p['sexe'] ?? '', $VC_LISTES['sexe'], $err, 'Sexe'));
    $set('date_naissance', vc_date($p['date_naissance'] ?? '', $err, 'Date de naissance', vc_il_y_a(60), vc_il_y_a(14)));
    $set('lieu_naissance', vc_lieu($p['lieu_naissance'] ?? '', $err, 'Le lieu de naissance'));
    $set('adresse', vc_adresse($p['adresse'] ?? '', $err));
    $set('telephone', vc_telephone($p['telephone'] ?? '', $err));

    $set('N_CIN', vc_cin($p['N_CIN'] ?? '', $err, $hors_dts));
    $naissance = !empty($d['date_naissance']) ? new DateTime($d['date_naissance']) : null;
    $set('Date_CIN', vc_date($p['Date_CIN'] ?? '', $err, 'Date de CIN',
        $naissance ? vc_apres($naissance, 12) : new DateTime('1950-01-01'), vc_aujourdhui(), $hors_dts));
    $set('Lieu_CIN', vc_lieu($p['Lieu_CIN'] ?? '', $err, 'Le lieu de délivrance du CIN', $hors_dts));
    $set('adresse_email', vc_email($p['adresse_email'] ?? '', $err, $hors_dts));
    // Le CIN, sa date et son lieu vont ensemble
    if (($d['N_CIN'] ?? null) && (!($d['Date_CIN'] ?? null) || !($d['Lieu_CIN'] ?? null)) && !isset($e['N_CIN'])) {
        $e['N_CIN'] = 'Renseignez aussi la date et le lieu de délivrance du CIN.';
    }

    $set('type_candidat', vc_choix($p['type_candidat'] ?? '', $VC_LISTES['type_candidat'], $err, 'Le type de candidat'));
    $set('serie_bacc', vc_choix($p['serie_bacc'] ?? '', $VC_LISTES['serie_' . $niveau], $err, 'Le diplôme'));
    $set('annee_bacc', vc_annee_bacc($p['annee_bacc'] ?? '', $err, true, $naissance ? (int) $naissance->format('Y') : null));
    $set('mention_bacc', vc_choix($p['mention_bacc'] ?? '', $VC_LISTES['mention_' . $niveau], $err, 'La mention'));

    // Parcours : doivent exister pour cette école et ce niveau
    $grade = $VC_GRADE[$niveau];
    $verifie_parcours = function ($code) use ($bdd, $ecole, $grade) {
        $s = $bdd->prepare('SELECT COUNT(*) FROM parcours WHERE parcours_abrevie = ? AND ecole_abrevie = ? AND grade_abrevie = ?');
        $s->execute([$code, $ecole, $grade]);
        return (int) $s->fetchColumn() > 0;
    };
    $p1 = trim($p['parcours1'] ?? '');
    if ($p1 === '') { $err = 'Le parcours du 1er choix est obligatoire.'; }
    elseif (!$verifie_parcours($p1)) { $err = 'Parcours du 1er choix inconnu pour cette école et ce niveau.'; }
    $set('parcours1', $err === null ? $p1 : null);
    $p2 = trim($p['parcours2'] ?? '');
    if ($p2 !== '' && !$verifie_parcours($p2)) { $err = 'Parcours du 2e choix inconnu pour cette école et ce niveau.'; }
    $set('parcours2', $err === null && $p2 !== '' ? $p2 : null);

    // Centre d'examen
    $c = $bdd->prepare('SELECT id_centre FROM centre WHERE nom_centre = ?');
    $c->execute([trim($p['centre'] ?? '')]);
    $id_centre = $c->fetchColumn();
    if (!$id_centre) { $err = "Centre d'examen invalide."; }
    $set('id_centre', $id_centre ?: null);

    // Dossier
    $set('num_arrivee', vc_num_arrivee($p['num_arrivee'] ?? '', $err));
    $set('date_arrivee', vc_date($p['date_arrivee'] ?? '', $err, "Date d'arrivée", vc_il_y_a(3), vc_aujourdhui()));
    $set('mode_paiement', vc_choix($p['mode_paiement'] ?? '', $VC_LISTES['mode_paiement'], $err, 'Le mode de paiement'));
    $dossier = trim($p['dossier_complet'] ?? '');
    if (!in_array($dossier, ['OUI', 'NON'], true)) { $err = 'Indiquez si le dossier est complet.'; }
    $set('dossier_complet', $dossier === 'OUI' ? 1 : 0);   // la colonne est un booléen (0/1)
    $obs = vc_espaces($p['obs'] ?? '');
    if (mb_strlen($obs) > 500) { $err = "L'observation est trop longue (500 caractères maximum)."; }
    $set('obs', $obs === '' ? null : strip_tags($obs));

    // Unicité (dans l'année) : même personne, même CIN
    if (!$e && $d['nom'] && $d['date_naissance']) {
        $s = $bdd->prepare('SELECT COUNT(*) FROM candidats WHERE id_annee = ? AND nom = ? AND COALESCE(prenom, "") = ? AND date_naissance = ?');
        $s->execute([$id_annee, $d['nom'], $d['prenom'] ?? '', $d['date_naissance']]);
        if ((int) $s->fetchColumn() > 0) { $e['nom'] = 'Doublon : ce candidat est déjà enregistré pour cette année.'; }
    }
    if (!$e && !empty($d['N_CIN'])) {
        $s = $bdd->prepare('SELECT nom, prenom FROM candidats WHERE id_annee = ? AND N_CIN = ? LIMIT 1');
        $s->execute([$id_annee, $d['N_CIN']]);
        if ($o = $s->fetch(PDO::FETCH_ASSOC)) { $e['N_CIN'] = 'Ce numéro de CIN est déjà utilisé par ' . $o['nom'] . ' ' . $o['prenom'] . '.'; }
    }
    return [$d, $e];
}

/**
 * Valide UN champ modifié (édition directe dans la liste).
 * Retourne [$colonne, $valeur_normalisee, $erreur]. $cand = ligne actuelle du candidat.
 */
function vc_valider_champ(PDO $bdd, $champ, $valeur, array $cand)
{
    global $VC_LISTES, $VC_GRADE;
    $err = null;
    $colonne = $champ;
    switch ($champ) {
        case 'nom':            $v = vc_nom($valeur, $err); break;
        case 'prenom':         $v = vc_prenom($valeur, $err); break;
        case 'sexe':           $v = vc_choix(strtoupper(trim($valeur)), $VC_LISTES['sexe'], $err, 'Le sexe'); break;
        case 'date_naissance':
            $v = vc_date($valeur, $err, 'Date de naissance', vc_il_y_a(60), vc_il_y_a(14));
            break;
        case 'lieu_naissance': $v = vc_lieu($valeur, $err, 'Le lieu de naissance', true); break;
        case 'adresse':        $v = vc_adresse($valeur, $err, true); break;
        case 'telephone':      $v = vc_telephone($valeur, $err, true); break;
        case 'type_candidat':  $v = vc_choix($valeur, $VC_LISTES['type_candidat'], $err, 'Le type de candidat', true); break;
        case 'serie_bacc':
            $v = vc_choix($valeur, $VC_LISTES['serie_' . ($cand['niveau'] ?? 'DTS')] ?? [], $err, 'Le diplôme', true);
            break;
        case 'annee_bacc':
            $nais = !empty($cand['date_naissance']) ? (int) substr($cand['date_naissance'], 0, 4) : null;
            $v = vc_annee_bacc($valeur, $err, true, $nais);
            break;
        case 'parcours1':
        case 'parcours2':
            $v = trim((string) $valeur);
            if ($v === '' && $champ === 'parcours2') { $v = null; break; }
            $s = $bdd->prepare('SELECT COUNT(*) FROM parcours WHERE parcours_abrevie = ? AND ecole_abrevie = ? AND grade_abrevie = ?');
            $s->execute([$v, $cand['ecole'], $VC_GRADE[$cand['niveau']] ?? '']);
            if (!(int) $s->fetchColumn()) { $err = 'Parcours inconnu pour cette école et ce niveau.'; $v = null; break; }
            if ($champ === 'parcours1' && $v !== $cand['parcours1']) {
                $n = $bdd->prepare('SELECT COUNT(*) FROM notes WHERE id_candidat = ?');
                $n->execute([$cand['id_candidat']]);
                if ((int) $n->fetchColumn() > 0) { $err = 'Ce candidat a déjà des notes : le parcours ne peut plus être modifié.'; $v = null; }
            }
            break;
        case 'centre':
            $c = $bdd->prepare('SELECT id_centre FROM centre WHERE nom_centre = ?');
            $c->execute([trim((string) $valeur)]);
            $v = $c->fetchColumn();
            if (!$v) { $err = 'Centre invalide.'; $v = null; }
            $colonne = 'id_centre';
            break;
        default:
            return [null, null, 'Champ non autorisé.'];
    }
    return [$colonne, $v, $err];
}
