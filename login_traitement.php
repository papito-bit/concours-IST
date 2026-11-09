<?php
// Hachage du mot de passe
//$pass_hache = sha1($_POST['pass']);
// Vérification des identifiants
require 'connect.php';
$req = $bdd->prepare('
        SELECT u.*, r.libelle as role_libelle 
        FROM utilisateur u 
        JOIN roles r ON u.id_role = r.id_role 
        WHERE u.pseudo = ?
        AND u.mdp = ?
        ');
$req->execute([$_POST['email_concours'], $_POST['mdp_concours']]);
// $req->execute([$_POST['email_concours'], sha1($_POST['mdp_concours'])]);
$resultat = $req->fetch();
if (!$resultat)
{
header("Location:login.php?erreur=erreur");
}
else
{
session_start();
// Enregistrement des données en session
$_SESSION['id_utilisateur'] = $resultat['id_utilisateur'];
$_SESSION['email_concours'] = $resultat['pseudo'];
$_SESSION['nom'] = $resultat['nom'];
$_SESSION['prenom'] = $resultat['prenom'];
$_SESSION['groupe'] = $resultat['role_libelle']; // On utilise le libellé du rôle
$_SESSION['ecole'] = $resultat['ecole'];
$_SESSION['niveau'] = $resultat['niveau'];

//echo 'Vous êtes connecté !';
header("Location:index.php");
}