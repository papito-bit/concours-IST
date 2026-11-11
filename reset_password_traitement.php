<?php
require_once __DIR__ . '/auth.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Méthode non autorisée.');
}
csrf_require();
require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/journal_helper.php';

$email = trim((string) ($_POST['email'] ?? ''));
$generic = ['success' => true, 'message' => 'Si cette adresse est associée à un compte, un lien de réinitialisation sera envoyé.'];
header('Content-Type: application/json; charset=utf-8');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(200);
    echo json_encode($generic);
    exit;
}
$baseUrl = rtrim((string) getenv('APP_BASE_URL'), '/');
if ($baseUrl === '' || !preg_match('~^https://[a-z0-9.-]+(?::[0-9]+)?(?:/[^\s]*)?$~i', $baseUrl)) {
    error_log('Réinitialisation: APP_BASE_URL manquant ou invalide.');
    echo json_encode($generic);
    exit;
}

try {
    $stmt = $bdd->prepare('SELECT id_utilisateur, email FROM utilisateur WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiry = date('Y-m-d H:i:s', time() + 3600);
        $save = $bdd->prepare('UPDATE utilisateur SET reset_token = ?, reset_expires = ? WHERE id_utilisateur = ?');
        $save->execute([$tokenHash, $expiry, (int) $user['id_utilisateur']]);
        $link = $baseUrl . '/nouveau_mot_de_passe.php?token=' . rawurlencode($token);
        $subject = 'Réinitialisation du mot de passe - Concours IST-D';
        $body = "Bonjour,\n\nUtilisez ce lien dans l’heure pour choisir un nouveau mot de passe :\n$link\n\nSi vous n’êtes pas à l’origine de cette demande, ignorez ce message.\n";
        $from = getenv('APP_RESET_FROM') ?: 'concours@istd.mg';
        $headers = 'From: ' . str_replace(["\r", "\n"], '', $from) . "\r\nContent-Type: text/plain; charset=UTF-8\r\n";
        if (!mail($user['email'], $subject, $body, $headers)) {
            error_log('Échec de livraison du courriel de réinitialisation pour utilisateur ' . (int) $user['id_utilisateur']);
        }
    }
} catch (Throwable $e) {
    error_log('Demande de réinitialisation échouée: ' . $e->getMessage());
}
echo json_encode($generic);
