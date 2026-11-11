<?php
// auth.php : à inclure en haut de chaque page réservée aux utilisateurs connectés.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_utilisateur']) || empty($_SESSION['email_concours'])) {
    header('Location: login.php');
    exit;
}

// Protection XSS
if (!function_exists('h')) {
    function h($valeur)
    {
        return htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8');
    }
}

// Réserve une page à certains rôles : exiger_role(['superadmin', 'admin']);
if (!function_exists('exiger_role')) {
    function exiger_role(array $roles)
    {
        $role = strtolower($_SESSION['groupe'] ?? '');
        if (!in_array($role, $roles, true)) {
            http_response_code(403);
            exit('Accès refusé.');
        }
    }
}
