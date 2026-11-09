<?php
session_start();
if (!isset($_SESSION['id_utilisateur']) && !isset($_SESSION['email_concours'])) {
    header("Location:login.php?erreur=erreur");
    exit();
}
require('connect.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Candidats (Édition directe)</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f6f9;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        h2 {
            text-align: center;
            margin-bottom: 15px;
            color: #343a40;
            font-weight: bold;
        }
        .table thead {
            background-color: #343a40;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .table tbody tr:hover {
            background-color: #e9ecef;
        }
        td[contenteditable="true"] {
            background-color: #fff3cd;
            cursor: text;
        }
        td[contenteditable="true"]:focus {
            background-color: #ffeeba;
            outline: 2px solid #ffc107;
        }
        .save-status {
            font-size: 0.8rem;
            color: green;
            display: none;
        }
        .table-responsive {
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .search-box {
            width: 320px;
            margin: 10px auto;
            position: relative;
        }
        .search-box input {
            border-radius: 20px;
            padding-left: 35px;
        }
        .search-box i {
            position: absolute;
            top: 9px;
            left: 12px;
            color: gray;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <h2>📋 Liste des Candidats (édition directe)</h2>

    <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" class="form-control" placeholder="🔍 Rechercher un candidat...">
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover align-middle" id="candidatsTable">
            <thead>
                <tr>
                    <th>Pos.</th>
                    <th>Numéro inscription</th>
                    <th contenteditable="false">Nom</th>
                    <th contenteditable="false">Prénom</th>
                    <th>Date naissance</th>
                    <th>Lieu naissance</th>
                    <th>Sexe</th>
                    <th>Adresse</th>
                    <th>Téléphone</th>
                    <th>Type candidat</th>
                    <th>Série Bacc</th>
                    <th>Année Bacc</th>
                    <th>Centre concours</th>
                    <th>Parcours 1</th>
                    <th>Parcours 2</th>
                    <th>Saisi par</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            $utilisateur = $_SESSION['nom'] . " " . $_SESSION['prenom'];

            if ($_SESSION['groupe'] == "admin" || $_SESSION['groupe'] == "superadmin") {
                $query = $bdd->prepare("
                    SELECT can.*, cen.nom_centre AS centre
                    FROM candidats can 
                    JOIN centre cen ON can.id_centre = cen.id_centre 
                    WHERE can.id_annee = ?
                    ORDER BY can.id_candidat
                ");
                $query->execute([$_SESSION["id_annee_selected"]]);
            } else {
                $query = $bdd->prepare("
                    SELECT can.*, cen.nom_centre AS centre
                    FROM candidats can 
                    JOIN centre cen ON can.id_centre = cen.id_centre 
                    WHERE can.obs != 'bz' AND can.saisi_par = ?
                    ORDER BY can.id_candidat
                ");
                $query->execute([$utilisateur]);
            }

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $numInscription = sprintf("%'03d", $row['numero']) . "24" . $row['parcours1'];
                $pos = sprintf("%'03d", $i);

                echo "<tr data-id='{$row['id_candidat']}'>
                        <td>{$pos}</td>
                        <td>{$numInscription}</td>
                        <td contenteditable='true' data-field='nom'>" . htmlspecialchars($row['nom']) . "</td>
                        <td contenteditable='true' data-field='prenom'>" . htmlspecialchars($row['prenom']) . "</td>
                        <td contenteditable='true' data-field='date_naissance'>" . htmlspecialchars($row['date_naissance']) . "</td>
                        <td contenteditable='true' data-field='lieu_naissance'>" . htmlspecialchars($row['lieu_naissance']) . "</td>
                        <td contenteditable='true' data-field='sexe'>{$row['sexe']}</td>
                        <td contenteditable='true' data-field='adresse'>" . htmlspecialchars($row['adresse']) . "</td>
                        <td contenteditable='true' data-field='telephone'>" . htmlspecialchars($row['telephone']) . "</td>
                        <td contenteditable='true' data-field='type_candidat'>" . htmlspecialchars($row['type_candidat']) . "</td>
                        <td contenteditable='true' data-field='serie_bacc'>{$row['serie_bacc']}</td>
                        <td contenteditable='true' data-field='annee_bacc'>{$row['annee_bacc']}</td>
                        <td contenteditable='true' data-field='centre'>{$row['centre']}</td>
                        <td contenteditable='true' data-field='parcours1'>{$row['parcours1']}</td>
                        <td contenteditable='true' data-field='parcours2'>{$row['parcours2']}</td>
                        <td>{$row['saisi_par']} <span class='save-status'>✅</span></td>
                      </tr>";
                $i++;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap & AJAX -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// 🔍 Recherche dynamique
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    document.querySelectorAll("#candidatsTable tbody tr").forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});

// ✏️ Édition directe
document.querySelectorAll("td[contenteditable='true']").forEach(cell => {
    cell.addEventListener("blur", function() {
        const id = this.closest("tr").dataset.id;
        const field = this.dataset.field;
        const value = this.innerText.trim();

        fetch("update_candidat_ajax.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id=${id}&field=${field}&value=${encodeURIComponent(value)}`
        })
        .then(res => res.text())
        .then(data => {
            let status = this.closest("tr").querySelector(".save-status");
            status.style.display = "inline";
            setTimeout(() => status.style.display = "none", 1000);
        })
        .catch(err => console.error("Erreur:", err));
    });
});
</script>

</body>
</html>
