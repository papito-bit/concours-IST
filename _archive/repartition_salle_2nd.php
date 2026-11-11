<?php
session_start();
require "connect.php";

// Récupérer toutes les salles
$salles = $bdd->query("SELECT id_salle FROM salle ORDER BY id_salle")->fetchAll(PDO::FETCH_COLUMN);

if (empty($salles)) {
    die("⚠ Aucune salle n’est définie dans la base.");
}

// Récupérer tous les candidats sans salle (id_salle IS NULL) pour l'année active
$candidats = $bdd->prepare("SELECT id_candidat FROM candidats WHERE id_annee=? AND id_salle IS NULL ORDER BY id_candidat");
$candidats->execute([$_SESSION["id_annee_selected"]]);
$candidats = $candidats->fetchAll(PDO::FETCH_COLUMN);

if (empty($candidats)) {
    echo "<script>alert('Tous les candidats ont déjà une salle attribuée !'); window.location.href='liste_presence.php';</script>";
    exit;
}

$tailleLot = 30; // nombre de candidats par salle
$indexSalle = 0;
$compteur = 0;

$update = $bdd->prepare("UPDATE candidats SET id_salle=? WHERE id_candidat=?");

foreach ($candidats as $idCandidat) {
    $update->execute([$salles[$indexSalle], $idCandidat]);
    $compteur++;

    // Passer à la salle suivante dès que la salle atteint $tailleLot
    if ($compteur >= $tailleLot) {
        $indexSalle = ($indexSalle + 1) % count($salles);
        $compteur = 0;
    }
}

echo "<script>alert('✅ Répartition des candidats effectuée avec succès !'); window.location.href='liste_presence.php';</script>";
