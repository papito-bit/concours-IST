<?php
ob_start();

session_start();
require_once __DIR__ . '/../../includes/auth.php';
exiger_role(['superadmin', 'admin']);
if(isset($_SESSION['id_utilisateur']) AND isset($_SESSION['email_concours'])){


require __DIR__ . '/../../config/connect.php';
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
<?php require __DIR__ . '/../../includes/template.php'; ?>

<?php }else{header("Location:../../login.php");} ?>