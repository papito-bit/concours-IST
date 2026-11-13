<?php
// Connexion à la base de données.
// utf8mb4 : même jeu de caractères que les tables (l'ancien "utf8" refusait certains caractères).
// En production : créer un utilisateur MySQL dédié avec un mot de passe, au lieu de "root" sans mot de passe.
try {
    $bdd = new PDO('mysql:host=localhost;dbname=concours_db;charset=utf8mb4', 'root', '');
} catch (Exception $e) {
    // Le détail technique va dans le journal du serveur, pas à l'écran (il révèle l'hôte et l'utilisateur).
    error_log('Connexion BD impossible : ' . $e->getMessage());
    http_response_code(500);
    die('Service momentanément indisponible. Veuillez contacter l\'administrateur.');
}
