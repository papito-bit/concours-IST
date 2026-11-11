<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if (empty($_SESSION['id_utilisateur']) || empty($_SESSION['email_concours'])) {
    header('Location: login.php');
    exit;
}

if (!function_exists('h')) {
    function h($valeur): string
    {
        return htmlspecialchars((string) $valeur, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['_csrf_token']) || !is_string($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . h(csrf_token()) . '">';
    }

    function csrf_require(): void
    {
        $sent = $_POST['_csrf_token'] ?? '';
        $known = $_SESSION['_csrf_token'] ?? '';

        if (!is_string($sent) || !is_string($known) || $known === '' || !hash_equals($known, $sent)) {
            http_response_code(403);
            exit('Jeton de sécurité invalide. Rechargez la page puis réessayez.');
        }
    }
}

if (!function_exists('exiger_role')) {
    function exiger_role(array $roles): void
    {
        $role = strtolower(trim((string) ($_SESSION['groupe'] ?? '')));
        $allowed = array_map(static fn($r) => strtolower(trim((string) $r)), $roles);

        if (!in_array($role, $allowed, true)) {
            http_response_code(403);
            exit('Accès refusé.');
        }
    }
}
