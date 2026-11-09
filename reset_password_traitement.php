<?php
session_start();
require_once '../../scripts/config.inc.php'; // fichier avec connexion PDO ou MySQLi

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
    $email = trim($_POST['email']);

    // Vérifier si l'email existe dans la BDD
    $stmt = $pdo->prepare("SELECT id, email FROM concours_utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Génération d'un token sécurisé
        $token = bin2hex(random_bytes(32));
        $expiration = date('Y-m-d H:i:s', time() + 3600); // 1 heure de validité

        // Sauvegarde du token dans la BDD
        $stmt = $pdo->prepare("UPDATE concours_utilisateurs SET reset_token = ?, reset_expires = ? WHERE id = ?");
        $stmt->execute([$token, $expiration, $user['id']]);

        // Lien de réinitialisation
        $resetLink = "https://ton-domaine.com/nouveau_mot_de_passe.php?token=" . $token;

        // Envoi email
        $sujet = "Réinitialisation de votre mot de passe - Concours IST-D";
        $message = "
        Bonjour,

        Vous avez demandé à réinitialiser votre mot de passe pour la plateforme Concours IST-D.
        Cliquez sur le lien ci-dessous pour créer un nouveau mot de passe (valide 1 heure) :

        $resetLink

        Si vous n'avez pas fait cette demande, ignorez cet email.
        
        -- 
        Service Concours IST-D
        ";

        $headers = "From: concours@istd.mg\r\n";
        $headers .= "Reply-To: concours@istd.mg\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($email, $sujet, $message, $headers)) {
            header("Location: mot_de_passe_oublie.php?message=" . urlencode("Un email de réinitialisation a été envoyé."));
            exit();
        } else {
            header("Location: mot_de_passe_oublie.php?message=" . urlencode("Erreur lors de l'envoi de l'email."));
            exit();
        }
    } else {
        // Email non trouvé
        header("Location: mot_de_passe_oublie.php?message=" . urlencode("Aucun compte trouvé avec cet email."));
        exit();
    }
} else {
    header("Location: mot_de_passe_oublie.php");
    exit();
}
