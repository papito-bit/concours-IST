<?php
// journal_helper.php : journal d'activité et notifications.
// Ces fonctions ne doivent jamais bloquer l'application : toute erreur est ignorée.
require_once __DIR__ . '/connect.php';

/**
 * Enregistre une action dans le journal.
 * Exemple : journaliser('candidat_ajout', 'RAKOTO Jean', 'candidat', 12);
 */
function journaliser($action, $details = '', $entite = null, $id_entite = null, $nom = null)
{
    global $bdd;
    try {
        if ($nom === null) {
            $nom = trim(($_SESSION['nom'] ?? '') . ' ' . ($_SESSION['prenom'] ?? ''));
            if ($nom === '') {
                $nom = $_SESSION['email_concours'] ?? 'inconnu';
            }
        }
        $st = $bdd->prepare(
            'INSERT INTO journal_activite (id_utilisateur, nom_utilisateur, action, entite, id_entite, details, ip)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $st->execute([
            $_SESSION['id_utilisateur'] ?? null,
            $nom,
            $action,
            $entite,
            $id_entite,
            $details,
            $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    } catch (Throwable $e) {
        // on ignore
    }
}

/**
 * Envoie une notification à tous les utilisateurs ayant l'un des rôles donnés.
 */
function notifier($message, $lien = null, array $roles = ['superadmin', 'admin'])
{
    global $bdd;
    try {
        $in = implode(',', array_fill(0, count($roles), '?'));
        $us = $bdd->prepare(
            "SELECT u.id_utilisateur FROM utilisateur u
             JOIN roles r ON r.id_role = u.id_role
             WHERE r.libelle IN ($in)"
        );
        $us->execute($roles);
        $ins = $bdd->prepare('INSERT INTO notifications (id_utilisateur, message, lien) VALUES (?, ?, ?)');
        foreach ($us->fetchAll(PDO::FETCH_COLUMN) as $id) {
            $ins->execute([$id, mb_substr($message, 0, 255), $lien]);
        }
    } catch (Throwable $e) {
        // on ignore
    }
}

function notifications_non_lues($id_utilisateur)
{
    global $bdd;
    try {
        $st = $bdd->prepare('SELECT COUNT(*) FROM notifications WHERE id_utilisateur = ? AND lu = 0');
        $st->execute([$id_utilisateur]);
        return (int) $st->fetchColumn();
    } catch (Throwable $e) {
        return 0;
    }
}

function notifications_recentes($id_utilisateur, $limite = 5)
{
    global $bdd;
    try {
        $limite = (int) $limite;
        $st = $bdd->prepare("SELECT * FROM notifications WHERE id_utilisateur = ? ORDER BY id DESC LIMIT $limite");
        $st->execute([$id_utilisateur]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        return [];
    }
}
