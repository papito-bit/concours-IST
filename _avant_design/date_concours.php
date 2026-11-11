<?php
// date_concours.php
ob_start();
session_start();
if (!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['email_concours'])) {
    header("Location: login.php");
    exit;
}

require "connect.php";

// --- Année universitaire actuelle ---
$id_annee = $_SESSION['id_annee_selected'] ?? null;
$annee_univ = '';
if ($id_annee) {
    $stmt = $bdd->prepare("SELECT libelle FROM annees WHERE id_annee = ?");
    $stmt->execute([$id_annee]);
    $annee_univ = $stmt->fetchColumn() ?: '';
}

// --- AJOUT / MODIFICATION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_date'])) {
    $cycle = trim($_POST['cycle']);
    $premier = trim($_POST['premier_date']);
    $deuxieme = trim($_POST['deuxieme_date']);
    $session = trim($_POST['session']);
    $annee = date('Y');

    if (!empty($_POST['id'])) {
        // Modification
        $stmt = $bdd->prepare("UPDATE date_concours 
            SET Cycle = :c, Premier_Date = :p, Deuxiem_Date = :d, session = :s, 
                annee = :a, annee_univ = :u, id_annee = :ia
            WHERE ID = :id");
        $stmt->execute([
            ':c' => $cycle,
            ':p' => $premier,
            ':d' => $deuxieme,
            ':s' => $session,
            ':a' => $annee,
            ':u' => $annee_univ,
            ':ia' => $id_annee,
            ':id' => $_POST['id']
        ]);
        $msg = "Date du concours mise à jour avec succès.";
    } else {
        // Ajout
        $stmt = $bdd->prepare("INSERT INTO date_concours 
            (Cycle, Premier_Date, Deuxiem_Date, session, annee, annee_univ, id_annee)
            VALUES (:c, :p, :d, :s, :a, :u, :ia)");
        $stmt->execute([
            ':c' => $cycle,
            ':p' => $premier,
            ':d' => $deuxieme,
            ':s' => $session,
            ':a' => $annee,
            ':u' => $annee_univ,
            ':ia' => $id_annee
        ]);
        $msg = "Nouvelle date de concours ajoutée avec succès.";
    }
}

// --- SUPPRESSION ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $bdd->prepare("DELETE FROM date_concours WHERE ID = ? AND id_annee = ?");
    $stmt->execute([$id, $id_annee]);
    $msg = "Date de concours supprimée avec succès.";
}

// --- LECTURE ---
$stmt = $bdd->prepare("SELECT * FROM date_concours WHERE id_annee = ? ORDER BY ID ASC");
$stmt->execute([$id_annee]);
$dates = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
  <div class="card-header bg-primary text-white">
    <h3 class="card-title">Gestion des dates de concours</h3>
  </div>
  <div class="card-body">
    <?php if (!empty($msg)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="post" class="border p-3 mb-4 rounded bg-light">
      <input type="hidden" name="id" id="id_edit">

      <div class="form-row">
        <div class="form-group col-md-3">
          <label>Cycle :</label>
          <select name="cycle" id="cycle" class="form-control" required>
            <option value="">-- Sélectionner --</option>
            <option value="cycle1">Cycle 1</option>
            <option value="cycle2">Cycle 2</option>
          </select>
        </div>
        <div class="form-group col-md-3">
          <label>Première date :</label>
          <input type="date" name="premier_date" id="premier_date" class="form-control" required>
        </div>
        <div class="form-group col-md-3">
          <label>Deuxième date :</label>
          <input type="date" name="deuxieme_date" id="deuxieme_date" class="form-control">
        </div>
        <div class="form-group col-md-3">
          <label>Session :</label>
          <input type="text" name="session" id="session" class="form-control" placeholder="Ex: 18 et 19 septembre 2025" required>
        </div>
      </div>

      <button type="submit" name="save_date" class="btn btn-success">
        <i class="fas fa-save"></i> Enregistrer
      </button>
      <button type="reset" class="btn btn-secondary" onclick="clearForm()">Annuler</button>
    </form>

    <h5 class="mt-4 mb-3">Liste des dates de concours (Année <?= htmlspecialchars($annee_univ) ?>)</h5>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Cycle</th>
            <th>Première date</th>
            <th>Deuxième date</th>
            <th>Session</th>
            <th>Année universitaire</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($dates)): ?>
            <tr><td colspan="8" class="text-center">Aucune date enregistrée pour cette année.</td></tr>
          <?php else: foreach ($dates as $d): ?>
            <tr>
              <td><?= $d['ID'] ?></td>
              <td><?= htmlspecialchars($d['Cycle']) ?></td>
              <td><?= htmlspecialchars($d['Premier_Date']) ?></td>
              <td><?= htmlspecialchars($d['Deuxiem_Date']) ?></td>
              <td><?= htmlspecialchars($d['session']) ?></td>
              <td><?= htmlspecialchars($d['annee_univ']) ?></td>
              <td>
                <button class="btn btn-sm btn-warning" 
                        onclick='editDate(<?= json_encode($d) ?>)'>
                  <i class="fas fa-edit"></i>
                </button>
                <a href="?delete=<?= $d['ID'] ?>" class="btn btn-sm btn-danger" 
                   onclick="return confirm('Supprimer cette date ?')">
                  <i class="fas fa-trash"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function editDate(data) {
  document.getElementById('id_edit').value = data.ID;
  document.getElementById('cycle').value = data.Cycle;
  document.getElementById('premier_date').value = data.Premier_Date;
  document.getElementById('deuxieme_date').value = data.Deuxiem_Date;
  document.getElementById('session').value = data.session;
  window.scrollTo({ top: 0, behavior: 'smooth' });
}
function clearForm() {
  document.getElementById('id_edit').value = '';
}
</script>

<?php
$content = ob_get_clean();
$menu = "Dates du concours";
require "template.php";
?>
