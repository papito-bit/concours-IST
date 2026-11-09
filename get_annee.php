<?php
require_once('connect.php');

// Récupération de l'année active
$reqAnnee = $bdd->query('SELECT id_annee, libelle, active FROM annees ORDER BY active DESC');
$annees = $reqAnnee->fetchAll();

$_SESSION['annees'] = $annees;
foreach ($annees as $annee) {
    if ($annee['active'] == 1) {
        $_SESSION['annee_selected'] = $annee['libelle'];
        $_SESSION['id_annee_selected'] = $annee['id_annee']; 
    }
}

// Si aucune année n'est active, on prend la première de la liste
if (!isset($_SESSION['annee_selected']) && !empty($annees)) {
    $_SESSION['annee_selected'] = $annees[0]['libelle'];
    $_SESSION['id_annee_selected'] = $annees[0]['id_annee'];
}
?>