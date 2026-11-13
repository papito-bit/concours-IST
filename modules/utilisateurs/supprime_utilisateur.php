<?php
require_once __DIR__ . '/../../includes/auth.php';
exiger_role(['superadmin', 'admin']);
require __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../../includes/journal_helper.php';

$id = filter_input(INPUT_GET, 'id_suppr', FILTER_VALIDATE_INT);

// On ne peut pas supprimer son propre compte
if ($id && $id !== (int) $_SESSION['id_utilisateur']) {
    $s = $bdd->prepare('SELECT u.pseudo, r.libelle FROM utilisateur u JOIN roles r ON r.id_role = u.id_role WHERE u.id_utilisateur = ?');
    $s->execute([$id]);
    $cible = $s->fetch(PDO::FETCH_ASSOC);

    if ($cible) {
        // Un admin ne supprime pas un superadmin
        if (strtolower($cible['libelle']) === 'superadmin' && strtolower($_SESSION['groupe']) !== 'superadmin') {
            http_response_code(403);
            exit('Accès refusé : seul un superadmin peut supprimer ce compte.');
        }
        try {
            $suppr = $bdd->prepare('DELETE FROM utilisateur WHERE id_utilisateur = ?');
            $suppr->execute([$id]);
        } catch (PDOException $e) {
            // Clé étrangère : le compte a saisi ou modifié des candidats
            http_response_code(409);
            exit("Suppression impossible : ce compte est lié à des candidats déjà saisis. <a href='utilisateur.php'>Retour</a>");
        }
        if ($suppr->rowCount() > 0) {
            journaliser('utilisateur_suppr', 'Compte ' . $cible['pseudo'], 'utilisateur', $id);
            notifier('Utilisateur supprimé : ' . $cible['pseudo'], 'journal.php?action=utilisateur_suppr');
        }
    }
}

header('Location: utilisateur.php');
exit;
