<?php
require('connect.php');
$delete=$_GET['id_suppr'];
$suppr=$bdd->prepare('DELETE FROM `utilisateur` WHERE id_utilisateur=?');
$suppr->execute(array($delete));
header("Location:utilisateur.php");

?>