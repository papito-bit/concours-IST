<?php
session_start();
if (!empty($_SESSION['id_utilisateur'])) {
    require_once __DIR__ . '/journal_helper.php';
    journaliser('deconnexion');
}
session_destroy();
header("Location:login.php");
