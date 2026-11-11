<?php
ob_start();

session_start();
if($_SESSION['groupe']!="admin" && $_SESSION['groupe']!="superadmin"){header("Location:login.php");}

require('connect.php');
//include 'compl/compte.php';
$requette=$bdd->query('SELECT * FROM utilisateur ORDER BY id_utilisateur');

?>






<table class="table table-bordered text-center">
  <tbody>
                    <td>
                      <a href="liste_delib_parcours.php?id=DTS"><button type="button" class="btn btn-block bg-gradient-<?php if($_GET['id']=="DTS"){echo "primary";} else{echo "secondary";} ?> btn-lg">DTS</button></a>
                    </td>
                    <td>
                      <a href="liste_delib_parcours.php?id=DTSS"><button type="button" class="btn btn-block bg-gradient-<?php if($_GET['id']=="DTSS"){echo "primary";} else{echo "secondary";} ?> btn-lg">DTSS</button></a>
                    </td>
                    <td>
                      <a href="liste_delib_parcours.php?id=ING"><button type="button" class="btn btn-block bg-gradient-<?php if($_GET['id']=="ING"){echo "primary";} else{echo "secondary";} ?> btn-lg">INGENIORAT</button></a>
                    </td>
  </tbody>
</table>


<?php
if (isset($_GET['id'])) {
  $niveau=$_GET['id'];
?>


     <div class="card">
        <div class="card-body p-0">
          <table class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="">
                          Parcours
                      </th>
                      <th style="">
                          Effectif
                      </th>
                      <th style="">
                          Statut
                      </th>
                      <th style="">
                          
                      </th>

                  </tr>
              </thead>
              <tbody>
                <?php

                $requette=$bdd->prepare('SELECT * FROM parcours WHERE grade_abrevie=? ORDER BY parcours_abrevie');
                $requette->execute(array($niveau));
                $i=0;
                while ($donnees=$requette->fetch()) {
                $i=$i+1;
                ?>
                                <tr>
                      <td>
                        <?php echo $donnees["parcours_abrevie"]; ?>
                      </td>
                      <td>
                        <?php

$nb=$bdd->prepare('SELECT COUNT(*) AS nbr FROM candidats WHERE parcours1=?');
$nb->execute(array($donnees["parcours_abrevie"]));
$nbetu=$nb->fetch();
echo $nbetu['nbr'];


$nbsaisi=$bdd->prepare('SELECT COUNT(*) AS nbparcours FROM notes WHERE parcours_notes=?');
$nbsaisi->execute(array($donnees["parcours_abrevie"]));
$nbsaisinotes=$nbsaisi->fetch();
                         ?>
                      </td>
                      <td>
                        <?php echo $nbsaisinotes['nbparcours']."/".$nbetu['nbr'];; ?>
                      </td>
                      <td>
<?php
                              if ($donnees['verrou_notes']=="OUI")
                    {
          
                      echo '<div class="fas fa-lock"></div>';
                    }
?>

                    </td>
                      <td class="project-actions text-right">
                          <a class="btn btn-primary btn-sm" href="delib_parcours.php?id=<?php echo $donnees["parcours_abrevie"]; ?>">
                              <i class="fas fa-folder">
                              </i>
                              Saisi de notes
                          </a>
                      </td>
                      <td class="project-actions text-right">
                          <a class="btn btn-primary btn-sm" href="delib_etat.php?id=<?php echo $donnees["parcours_abrevie"]; ?>">
                              <i class="fas fa-folder">
                              </i>
                              Déliberation
                          </a>
                      </td>
                      <td class="project-actions text-right">
                          <a class="btn btn-primary btn-sm" href="delib_resultat.php?id=<?php echo $donnees["parcours_abrevie"]; ?>">
                              <i class="fas fa-folder">
                              </i>
                              Résultat
                          </a>
                      </td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>




<?php
}
?>





<?php $content = ob_get_clean();
$menu="Déliberation ".$_GET['id']; 
$menu_delib=""; 
?>
<?php require('template.php'); ?>








