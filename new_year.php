<?php
// Connexion à la base de données
require_once('connect.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'année scolaire depuis le formulaire
    $annee = trim($_POST['annee'] ?? '');

    if (empty($annee)) {
        session_start();
        $_SESSION['error'] = "Aucune année reçue.";
        header('Location: index.php');
        exit();
    }

    try {
        // Vérifier si l'année existe déjà dans la base
        $sql_check = "SELECT COUNT(*) FROM annees WHERE libelle = :annee";
        $stmt_check = $bdd->prepare($sql_check);
        $stmt_check->execute(['annee' => $annee]);
        $exists = $stmt_check->fetchColumn();

        if ($exists > 0) {
            session_start();
            $_SESSION['error'] = "L'année scolaire $annee existe déjà.";
            header('Location: index.php');
            exit();
        }

        // Désactiver toutes les années existantes
        $sql_update = "UPDATE annees SET active = 0";
        $bdd->query($sql_update);

        // Ajouter la nouvelle année comme active
        $sql_insert = "INSERT INTO annees (libelle, active) VALUES (:annee, 1)";
        $stmt = $bdd->prepare($sql_insert);
        $stmt->execute(['annee' => $annee]);

        // Message de succès
        session_start();
        $_SESSION['message'] = "Nouvelle année scolaire $annee ajoutée avec succès.";
        header('Location: index.php');
        exit();

    } catch (PDOException $e) {
        session_start();
        $_SESSION['error'] = "Erreur lors de l'ajout de l'année : " . $e->getMessage();
        header('Location: index.php');
        exit();
    }
} else {
    // Si accès direct au fichier sans formulaire
    header('Location: index.php');
    exit();
}
?>
