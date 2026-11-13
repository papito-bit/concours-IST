<?php
// Cycle sélectionné automatiquement dans imprime_convocation.php
$cycle = $_SESSION["cycle_candidat"];

// Connexion déjà déclarée dans la page parente
// Donc pas besoin de require __DIR__ . '/../../config/connect.php';

$id_annee = $_SESSION["id_annee_selected"];

// Récupération des dates pour le cycle et l'année choisie
$stmt = $bdd->prepare("SELECT Premier_Date, Deuxiem_Date 
                       FROM date_concours 
                       WHERE Cycle = ? AND id_annee = ?
                       ORDER BY id DESC LIMIT 1");
$stmt->execute([$cycle, $id_annee]);
$dateConcours = $stmt->fetch(PDO::FETCH_ASSOC);

// Valeurs par défaut si aucune date enregistrée
if (!$dateConcours) {
    $dateConcours = [
        'Premier_Date' => "Date non définie",
        'Deuxiem_Date' => "Date non définie"
    ];
}
?>



<table border="1" cellspacing="0" cellpadding="5">
  <tr style="font-weight: bold; background:lightgrey;">
    <td>Date</td>
    <td>Heures</td>
    <td>Epreuves</td>
    <td>Durée</td>
    <th>Coeff.</th>
  </tr>
  <tr>
    <td><?php echo $dateConcours['Premier_Date']; ?></td>
    <td>08h00 - 09h30</td>
    <td>Français</td>
    <td>1h30</td>
    <td>1</td>
  </tr>
  <tr>
    <td><?php echo $dateConcours['Premier_Date']; ?></td>
    <td>09h45 - 11h45</td>
    <td>Mathématiques</td>
    <td>2h00</td>
    <td>2</td>
  </tr>
  <tr>
    <td><?php echo $dateConcours['Premier_Date']; ?></td>
    <td>14h30 - 16h30</td>
    <td>Dessin-Technologie</td>
    <td>2h00</td>
    <td>2</td>
  </tr>
  <tr>
    <td><?php echo $dateConcours['Deuxiem_Date']; ?></td>
    <td>08h00 - 09h30</td>
    <td>Physiques</td>
    <td>1h30</td>
    <td>2</td>
  </tr>
  <tr>
    <td><?php echo $dateConcours['Deuxiem_Date']; ?></td>
    <td>09h45 - 10h45</td>
    <td>Test Psychotechnique</td>
    <td>1h00</td>
    <td>1</td>
  </tr>
</table>
