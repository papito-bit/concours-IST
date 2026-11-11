<?php
ob_start();

session_start();
if($_SESSION['groupe']!="admin"){header("Location:login.php");}

require('connect.php');
//include 'compl/compte.php';
$requette=$bdd->query('SELECT * FROM utilisateur ORDER BY id_utilisateur');
?>


<?php if ($_GET['msg']==0) {
  echo "<h4>Il n'y a pas de candidat dans ce parcours.</h4>";
}

 if ($_GET['msg']>0) { ?>

<h4>Veuillez d'abord saisir toutes les notes des candidats.</h4>
<h4>Il reste <?php echo $_GET['msg']; ?> candidat(e)(s)</h4>
<?php
}
?>

<?php if ($_GET['msg']<0) {
  echo "<h4>Verrouiller d'abord les notes.</h4>";
}?>



<?php $content = ob_get_clean();
$menu=""; 
$menu_delib=""; 
?>
<?php require('template.php'); ?>