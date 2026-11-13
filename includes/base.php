<?php
// base.php : chemins de l'application, indépendants du dossier d'installation.
// ROOT_DIR  : dossier du projet sur le disque.
// BASE_URL  : adresse du projet dans le navigateur (ex. /concours ou /concours_v2), sans "/" final.
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', str_replace('\\', '/', dirname(__DIR__)));
}
if (!defined('BASE_URL')) {
    $script  = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $fichier = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? '');
    // Chemin de la page courante par rapport au projet, ex. /modules/candidats/liste_saisi.php
    $relatif = (stripos($fichier, ROOT_DIR) === 0) ? substr($fichier, strlen(ROOT_DIR)) : '';
    if ($relatif !== '' && strlen($script) >= strlen($relatif) && strcasecmp(substr($script, -strlen($relatif)), $relatif) === 0) {
        define('BASE_URL', substr($script, 0, strlen($script) - strlen($relatif)));
    } else {
        // Repli : on remonte jusqu'au dossier "modules" s'il existe, sinon au dossier du script
        $pos = strpos($script, '/modules/');
        define('BASE_URL', $pos !== false ? substr($script, 0, $pos) : rtrim(dirname($script), '/'));
    }
}

// Emplacement de chaque page (nom du fichier => chemin depuis la racine du projet)
const PAGES = [
    'action_ajout_utili.php' => 'modules/utilisateurs/action_ajout_utili.php',
    'action_modif_utili.php' => 'modules/utilisateurs/action_modif_utili.php',
    'affiche_jury.php' => 'modules/jurys/affiche_jury.php',
    'ajout_utili.php' => 'modules/utilisateurs/ajout_utili.php',
    'auth.php' => 'includes/auth.php',
    'confirme_suppr_utili.php' => 'modules/utilisateurs/confirme_suppr_utili.php',
    'connect.php' => 'config/connect.php',
    'convoc_dts_egi.php' => 'modules/convocations/convoc_dts_egi.php',
    'convoc_dtss.php' => 'modules/convocations/convoc_dtss.php',
    'convoc_ingeniorat.php' => 'modules/convocations/convoc_ingeniorat.php',
    'convocation.php' => 'modules/convocations/convocation.php',
    'convocation_a_imprimer.php' => 'modules/convocations/convocation_a_imprimer.php',
    'date_concours.php' => 'modules/administration/date_concours.php',
    'deconnect.php' => 'deconnect.php',
    'delib.php' => 'modules/deliberation/delib.php',
    'edt_agro.php' => 'modules/convocations/edt_agro.php',
    'edt_egmcs.php' => 'modules/convocations/edt_egmcs.php',
    'edt_gc_gn.php' => 'modules/convocations/edt_gc_gn.php',
    'edt_me_tc_mines.php' => 'modules/convocations/edt_me_tc_mines.php',
    'edt_meb.php' => 'modules/convocations/edt_meb.php',
    'edt_second_cycle.php' => 'modules/convocations/edt_second_cycle.php',
    'effectif_salle.php' => 'modules/listes/effectif_salle.php',
    'enregistre_candidat.php' => 'modules/candidats/enregistre_candidat.php',
    'get_annee.php' => 'includes/get_annee.php',
    'imprime_convocation.php' => 'modules/convocations/imprime_convocation.php',
    'imprime_tout_convocation.php' => 'modules/convocations/imprime_tout_convocation.php',
    'index.php' => 'index.php',
    'journal.php' => 'modules/administration/journal.php',
    'journal_helper.php' => 'includes/journal_helper.php',
    'liste_etiquette.php' => 'modules/listes/liste_etiquette.php',
    'liste_modif_salle.php' => 'modules/listes/liste_modif_salle.php',
    'liste_niveau.php' => 'modules/listes/liste_niveau.php',
    'liste_presence.php' => 'modules/listes/liste_presence.php',
    'liste_presence_dtss.php' => 'modules/listes/liste_presence_dtss.php',
    'liste_saisi.php' => 'modules/candidats/liste_saisi.php',
    'login.php' => 'login.php',
    'login_traitement.php' => 'modules/auth/login_traitement.php',
    'modif_utilisateur.php' => 'modules/utilisateurs/modif_utilisateur.php',
    'mot_de_passe_oublie.php' => 'modules/auth/mot_de_passe_oublie.php',
    'new_year.php' => 'modules/administration/new_year.php',
    'notifications.php' => 'modules/administration/notifications.php',
    'repartir_salles.php' => 'modules/listes/repartir_salles.php',
    'saisi_dts.php' => 'modules/candidats/saisi_dts.php',
    'saisi_dtss.php' => 'modules/candidats/saisi_dtss.php',
    'saisi_ing.php' => 'modules/candidats/saisi_ing.php',
    'supprime_utilisateur.php' => 'modules/utilisateurs/supprime_utilisateur.php',
    'template.php' => 'includes/template.php',
    'update_candidat_ajax.php' => 'modules/candidats/update_candidat_ajax.php',
    'utilisateur.php' => 'modules/utilisateurs/utilisateur.php',
    'validation_candidat.php' => 'includes/validation_candidat.php',
];

/**
 * Transforme un lien enregistré sous la forme "nom_de_page.php?param=valeur" (notifications)
 * en adresse complète. Retourne null si le lien n'est pas une page connue de l'application.
 */
function lien_page($lien)
{
    if (!preg_match('/^([A-Za-z0-9_\-]+\.php)(\?[A-Za-z0-9_=&%.\-]*)?$/', (string) $lien, $m)) {
        return null;
    }
    if (!isset(PAGES[$m[1]])) {
        return null;
    }
    return BASE_URL . '/' . PAGES[$m[1]] . ($m[2] ?? '');
}
