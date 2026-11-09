
<?php
session_start();

if (isset($_POST['acces_admin'])) {$acces=sha1($_POST['acces_admin']); } else {header("Location:acces_salle.php");}

if ($acces=="0446bc82059259101b160bb51baeb963affe00e1") {$_SESSION['info_etude']="OUI";} else {header("Location:acces_salle_2nd_cycle.php");}

if ($_SESSION['info_etude']=="OUI") {} else {header("Location:index.php");}


if (isset($_SESSION['id_utilisateur']) and isset($_SESSION['email_concours'])) {


  require 'connect.php';
?>

 
                <?php
                $nbdts = $bdd->prepare('SELECT COUNT(*) AS nombre_dts FROM candidats WHERE niveau!="DTS" AND centre=?');
                $nbdts->execute(array('Antsiranana'));
                $nombre_dts = $nbdts->fetch();
                $total_student = $nombre_dts["nombre_dts"];
                $salles = [];
                $total_capacite2_sale = 0;
                $requette = $bdd->query('SELECT * FROM salle WHERE etat2="OUI"');
                $i = 0;
                while ($donne = $requette->fetch()) {
                  $i = $i + 1;
                  $donne['disponible'] = $donne['capacite2'];
                  $salles[] = $donne;
                  $total_capacite2_sale += $donne['capacite2'];
                  if ($total_student <= $total_capacite2_sale) {

                    break;
                  }
                }

                $candidats = [];
                $requette = $bdd->query('SELECT * FROM `candidats` WHERE `niveau` != "DTS" AND centre="Antsiranana" ORDER BY parcours1 DESC ');
                $p = "";
                $i = -1;
                
                while ($donne = $requette->fetch()) {
                  if ($donne['parcours1'] != $p) {
                    $p = $donne['parcours1'];
                    $i++;
                  }
                  
                  $candidats[$i][] = $donne;
                }
                
                $total = 0;
                //echo '<pre>' . var_export($salles, true) . '</pre>';
                $c = 0;
                for ($i = 0; $i < count($candidats); $i++) {
                  $y = 0;
                  for ($y = 0; $y < count($candidats[$i]); $y++) {
                    if ($c >= count($salles)) {
                      $c = 0;
                    }
                    if ($salles[$c]['disponible'] > 0) {
                      echo "salle " . $salles[$c]['id_salle'] . " =>" . $candidats[$i][$y]['parcours1'] . "<br/>";
                      $req = $bdd->prepare('UPDATE `candidats` SET `salle`=? WHERE `id_candidat`=?');
                      $req_post = $req->execute(array(
                        $salles[$c]['id_salle'],
                        $candidats[$i][$y]['id_candidat']
                      ));
                     $total+=1;
                      $salles[$c]['disponible'] = $salles[$c]['disponible'] - 1;
                    }else{
                      $y=$y-1;
                    }
                    $c++;
                    
                  }
                }
                // echo '<pre>' . var_export($salles, true) . '</pre>';
                echo $total;
                ?>
<?php } else {
  header("Location:login.php");
} ?>