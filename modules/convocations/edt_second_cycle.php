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


<table>
  <tr style="font-weight: bold;background:lightgrey;">
  <td>Epreuves</td>
  <td>Date</td>
  <td>Heures</td>
    </tr>
    <tr>
    <td>Ecrite</td>
    <td><?php echo $dateConcours['Premier_Date']; ?></td>
    <td>14h00 à 15h30</td>
    </tr>
    <tr>
    <td>Orale</td>
    <td><?php echo $dateConcours['Premier_Date']."<br/>".$dateConcours['Deuxiem_Date']; ?></td>
    <td>à partir de 15h30<br/>à partir de 08h00</td>
    </tr>
</table>