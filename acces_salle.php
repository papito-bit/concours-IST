<?php
ob_start();

session_start();
if(isset($_SESSION['id_utilisateur']) AND isset($_SESSION['email_concours'])){
?>






<form method="POST" action="salle_execute.php">



<input class="form-control" type="password" name="acces_admin" placeholder="Mot de passe"  required>

<input class="form-control" type="submit" value="OK">

</form>







<?php $content = ob_get_clean();
$menu = "Voulez-vous vraiment exécuter la répartition par salle ?";
$menu_stat=""; 
?>
<?php require('template.php'); ?>

<?php }else{header("Location:login.php");} ?>