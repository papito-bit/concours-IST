<?php
ob_start();

session_start();
if(isset($_SESSION['id_utilisateur']) AND isset($_SESSION['email_concours'])){


require('connect.php');
$valeur_suppr=$_GET['valeur_suppr'];
$champ1=$bdd->prepare('SELECT * FROM utilisateur WHERE id_utilisateur=?');
$champ1->execute(array($valeur_suppr));
$champ=$champ1->fetch();
?>

<div class="row">
	<div class="col-md-6">
<a class="btn btn-primary" href="utilisateur.php">NON</a>
	</div>
	<div class="col-md-6">
<a class="btn btn-danger" href="supprime_utilisateur.php?id_suppr=<?php echo $valeur_suppr; ?>">OUI</a>
</div>
</div>








<?php $content = ob_get_clean();
$menu="Voulez-vous supprimer ".$champ['nom']." ".$champ['prenom']."<p></p>";
$menu_utili=""; 
?>
<?php require('template.php'); ?>

<?php }else{header("Location:login.php");} ?>