<?php
session_start();
if (!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['email_concours'])) {
    header("Location: ../../login.php");
    exit;
}

require __DIR__ . '/../../config/connect.php';

// --- Vérification du niveau ---
if (!isset($_GET['niveau']) || empty($_GET['niveau'])) {
    die("Erreur : niveau non spécifié.");
}
$niveau = strtoupper(trim($_GET['niveau']));
$allowed = ['DTS', 'DTSS', 'INGENIORAT'];
if (!in_array($niveau, $allowed, true)) {
    die("Erreur : niveau invalide.");
}

// --- Détermination de la page de redirection ---
$redirect_map = [
    'DTS' => 'liste_presence.php',
    'DTSS' => 'liste_presence_dtss.php',
    'INGENIORAT' => 'liste_presence_dtss.php#ingeniorat'
];
$redirect_url = $redirect_map[$niveau];

// --- Vérification de l'année active ---
if (!isset($_SESSION['id_annee_selected']) || empty($_SESSION['id_annee_selected'])) {
    die("Erreur : année non sélectionnée.");
}

try {
    // --- Récupération des centres ---
    $centres = $bdd->query("SELECT id_centre, nom_centre FROM centre ORDER BY id_centre")->fetchAll(PDO::FETCH_ASSOC);
    if (empty($centres)) {
        throw new Exception("Aucun centre trouvé dans la base de données !");
    }

    $bdd->beginTransaction();
    $nbTotalAffectations = 0;

    // --- Répartition pour chaque centre ---
    foreach ($centres as $centre) {
        $idCentre = $centre['id_centre'];

        // Récupérer les salles du centre
        $stmt = $bdd->prepare("SELECT id_salle, capacite FROM salle WHERE id_centre = ? ORDER BY id_salle ASC");
        $stmt->execute([$idCentre]);
        $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($salles)) continue;

        // Vérifier s'il existe une nouvelle salle (capacite NULL ou 0)
        $stmt = $bdd->prepare("SELECT id_salle FROM salle WHERE id_centre = ? AND (capacite IS NULL OR capacite = 0) ORDER BY id_salle DESC LIMIT 1");
        $stmt->execute([$idCentre]);
        $salleSpeciale = $stmt->fetchColumn();

        // Récupérer les candidats sans salle
        $stmt = $bdd->prepare("
            SELECT id_candidat FROM candidats
            WHERE id_annee = ? AND niveau = ? AND id_centre = ?
              AND (id_salle IS NULL OR id_salle = 0)
            ORDER BY id_candidat
        ");
        $stmt->execute([$_SESSION['id_annee_selected'], $niveau, $idCentre]);
        $candidatsSansSalle = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (empty($candidatsSansSalle)) continue;

        $update = $bdd->prepare("UPDATE candidats SET id_salle = ? WHERE id_candidat = ?");

        if ($salleSpeciale) {
            // Cas spécial : Nouvelle salle détectée → envoyer uniquement là-bas
            foreach ($candidatsSansSalle as $idCand) {
                $update->execute([$salleSpeciale, $idCand]);
                $nbTotalAffectations++;
            }
        } else {
            // Cas normal : round-robin sur toutes les salles
            $stmt = $bdd->prepare("
                SELECT id_salle FROM candidats
                WHERE id_annee = ? AND niveau = ? AND id_centre = ?
                  AND id_salle IS NOT NULL AND id_salle <> 0
                ORDER BY id_candidat DESC LIMIT 1
            ");
            $stmt->execute([$_SESSION['id_annee_selected'], $niveau, $idCentre]);
            $lastSalle = $stmt->fetchColumn();

            $sallesIDs = array_column($salles, 'id_salle');
            $indexSalle = 0;
            if ($lastSalle && in_array($lastSalle, $sallesIDs)) {
                $pos = array_search($lastSalle, $sallesIDs);
                $indexSalle = ($pos === false) ? 0 : (($pos + 1) % count($salles));
            }

            foreach ($candidatsSansSalle as $idCand) {
                $currentSalle = $salles[$indexSalle]['id_salle'];
                $update->execute([$currentSalle, $idCand]);
                $indexSalle = ($indexSalle + 1) % count($salles);
                $nbTotalAffectations++;
            }
        }
    }

    // --- Validation ou rollback ---
    if ($nbTotalAffectations === 0) {
        $bdd->rollBack();
        echo "<script>alert('Tous les candidats du niveau $niveau ont déjà une salle attribuée.'); 
              window.location.href='" . htmlspecialchars($redirect_url, ENT_QUOTES) . "';</script>";
        exit;
    }

    $bdd->commit();
    echo "<script>alert('Répartition terminée avec succès pour $niveau : $nbTotalAffectations candidat(s) affecté(s).'); 
          window.location.href='" . htmlspecialchars($redirect_url, ENT_QUOTES) . "';</script>";
    exit;

} catch (Exception $e) {
    if ($bdd->inTransaction()) $bdd->rollBack();
    $err = addslashes($e->getMessage());
    echo "<script>alert('Erreur : $err'); window.location.href='" . htmlspecialchars($redirect_url, ENT_QUOTES) . "';</script>";
    exit;
}
?>
