<?php
include("session_check.php");
require('accessDB.php');


$message = '';
$search_query = '';

if (isset($_GET['submit'])) {
    $search_query = $_GET['search'];
}

$sql = "SELECT e.ID_EQUIPEMENTS, e.LIBELLE_EQUIPEMENTS, e.NAME_EQUIPEMENT, e.created_at, e.temp_cpu, e.utilisation_cpu, e.status_s
        FROM equipements e
        INNER JOIN (
            SELECT NAME_EQUIPEMENT, MAX(created_at) as MaxDate
            FROM equipements
            GROUP BY NAME_EQUIPEMENT
        ) as latest
        ON e.NAME_EQUIPEMENT = latest.NAME_EQUIPEMENT AND e.created_at = latest.MaxDate";

if (!empty($search_query)) {
    $sql .= " WHERE e.NAME_EQUIPEMENT LIKE ?";
}

$stmt = $conn->prepare($sql);
if (!empty($search_query)) {
    $search_param = "%$search_query%";
    $stmt->bind_param("s", $search_param);
}
$stmt->execute();
$result = $stmt->get_result();

$alarms = [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervision Inter-ville</title>
    <link rel="icon" href="./img/logo.png">
    <link rel="stylesheet" href="./style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        .navbar-nav {
            flex-grow: 1;
        }
        .form-deconnexion {
            margin-left: 20px;
        }
        .table th, .table td {
            text-align: center;
        }
        .btn-alarm {
            animation: blink 1s step-start infinite;
        }
        @keyframes blink {
            50% {
                background-color: #dc3545;
            }
        }
    </style>
</head>
<body>
<?php require('header.php');?>
<main class="table-container">
    <div>
        <form method="GET" action="">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Rechercher par nom d'équipement" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-secondary" type="submit" name="submit">Rechercher</button>
            </div>
        </form>
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>Type d'équipements</th>
                    <th>Nom d'équipements</th>
                    <th>Alarmes</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $highlight = (!empty($search_query) && stripos($row['NAME_EQUIPEMENT'], $search_query) !== false) ? 'table-warning' : '';
            $has_temp_alarm = $row['temp_cpu'] > 65;
            $has_cpu_alarm = $row['utilisation_cpu'] > 70;
            $has_status_alarm = $row['status_s'] == 0;
            $alarm_class = ($has_temp_alarm || $has_cpu_alarm || $has_status_alarm) ? 'btn-alarm' : '';
            $alarm_text = '';

            if ($has_temp_alarm) {
                $alarm_text .= 'Température CPU élevée ';
            }
            if ($has_cpu_alarm) {
                $alarm_text .= 'Utilisation CPU élevée ';
            }
            if ($has_status_alarm) {
                $alarm_text .= 'Statut de l\'équipement ';
            }
            if (empty($alarm_text)) {
                $alarm_text = 'Pas d\'alarme';
            } else {
                $alarms[] = [
                    'name' => $row['NAME_EQUIPEMENT'],
                    'cause' => trim($alarm_text)
                ];
            }

            echo "<tr class='$highlight'>";
            echo "<td>" . htmlspecialchars($row['LIBELLE_EQUIPEMENTS']) . "</td>";
            echo "<td>" . htmlspecialchars($row['NAME_EQUIPEMENT']) . "</td>";
            echo "<td>";
            echo "<form method='post' action='alarm.php' style='display:inline-block;'>";
            echo "<input type='hidden' name='ID_EQUIPEMENTS' value='" . htmlspecialchars($row['ID_EQUIPEMENTS']) . "'>";
            echo "<button type='submit' class='btn btn-light $alarm_class'>$alarm_text</button>";
            echo "</form>";
            echo "</td>";
            echo "<td>";
            echo "<form method='post' action='details.php' style='display:inline-block;'>";
            echo "<input type='hidden' name='ID_EQUIPEMENTS' value='" . htmlspecialchars($row['ID_EQUIPEMENTS']) . "'>";
            echo "<button type='submit' class='btn btn-light'>Détails</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Aucun résultat trouvé.</td></tr>";
    }
    $stmt->close();
    $conn->close();
    ?>
            </tbody>
        </table>
    </div>
</main>
<!-- Modal -->
<div class="modal fade" id="alarmModal" tabindex="-1" aria-labelledby="alarmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alarmModalLabel">Alarmes Actives</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                if (!empty($alarms)) {
                    foreach ($alarms as $alarm) {
                        echo "<p><strong>" . htmlspecialchars($alarm['name']) . ":</strong> " . htmlspecialchars($alarm['cause']) . "</p>";
                    }
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Recharger la page toutes les 30 secondes
    setInterval(function(){
        window.location.reload();
    }, 30000);

    // Afficher le pop-up s'il y a des alarmes
    <?php if (!empty($alarms)) { ?>
        var modal = new bootstrap.Modal(document.getElementById('alarmModal'));
        modal.show();
    <?php } ?>
</script>
<?php require('footer.php');?>
</body>
</html>
