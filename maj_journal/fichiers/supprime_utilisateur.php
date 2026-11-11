<?php
require_once __DIR__ . '/auth.php';
exiger_role(['superadmin', 'admin']);
require 'connect.php';
require_once __DIR__ . '/journal_helper.php';

$id = filter_input(INPUT_GET, 'id_suppr', FILTER_VALIDATE_INT);

// On ne peut pas supprimer son propre compte
if ($id && $id !== (int) $_SESSION['id_utilisateur']) {
    $s = $bdd->prepare('SELECT pseudo FROM utilisateur WHERE id_utilisateur = ?');
    $s->execute([$id]);
    $pseudo = $s->fetchColumn();

    $suppr = $bdd->prepare('DELETE FROM utilisateur WHERE id_utilisateur = ?');
    $suppr->execute([$id]);

    if ($suppr->rowCount() > 0) {
        journaliser('utilisateur_suppr', 'Compte ' . $pseudo, 'utilisateur', $id);
        notifier('Utilisateur supprimé : ' . $pseudo, 'journal.php?action=utilisateur_suppr');
    }
}

header('Location: utilisateur.php');
exit;
