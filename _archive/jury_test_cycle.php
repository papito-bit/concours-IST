<?php
session_start();
if (isset($_SESSION['id_utilisateur']) and isset($_SESSION['email_concours'])) {


  require 'connect.php';
?>

 
                <?php
                $nbdts = $bdd->prepare('SELECT COUNT(*) AS nombre_dts FROM candidats WHERE niveau!="DTS" AND centre=?');
                $nbdts->execute(array('Antsiranana'));
                $nombre_dts = $nbdts->fetch();
                $total_student = $nombre_dts["nombre_dts"];
                $jury = [];
                $total_capacite_sale = 0;
                $requette = $bdd->query('SELECT * FROM jury WHERE etat="OUI"');
                $i = 0;
                while ($donne = $requette->fetch()) {
                  $i = $i + 1;
                  $donne['disponible'] = $donne['capacite'];
                  $jury[] = $donne;
                  $total_capacite_sale += $donne['capacite'];
                  if ($total_student <= $total_capacite_sale) {

                    break;
                  }
                }

                $candidats = [];
                $requette = $bdd->query('SELECT * FROM `candidats` WHERE `niveau` != "DTS" AND centre="Antsiranana" ORDER BY `candidats`.`parcours1` ASC ');
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
                //echo '<pre>' . var_export($jury, true) . '</pre>';
                $c = 0;
                for ($i = 0; $i < count($candidats); $i++) {
                  $y = 0;
                  for ($y = 0; $y < count($candidats[$i]); $y++) {
                    if ($c >= count($jury)) {
                      $c = 0;
                    }
                    if ($jury[$c]['disponible'] > 0) {
                      echo "jury " . $jury[$c]['id_jury'] . " =>" . $candidats[$i][$y]['parcours1'] . "<br/>";
                      $req = $bdd->prepare('UPDATE `candidats` SET `jury`=? WHERE `id_candidat`=?');
                      $req_post = $req->execute(array(
                        $jury[$c]['id_jury'],
                        $candidats[$i][$y]['id_candidat']
                      ));
                     $total+=1;
                      $jury[$c]['disponible'] = $jury[$c]['disponible'] - 1;
                    }else{
                      $y=$y-1;
                    }
                    $c++;
                    
                  }
                }
                // echo '<pre>' . var_export($jury, true) . '</pre>';
                echo $total;
                ?>
<?php } else {
  header("Location:login.php");
} ?>