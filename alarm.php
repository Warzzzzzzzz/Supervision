<?php
include("session_check.php");
require('accessDB.php');

// Requête pour vérifier les équipements dont la température CPU > 65 ou l'utilisation CPU > 70%
$sql = "SELECT NAME_EQUIPEMENT, temp_cpu, utilisation_cpu 
        FROM equipements 
        WHERE temp_cpu > 65 OR utilisation_cpu > 70"; 

// Requête pour récupérer les alarmes avec les causes spécifiques
$alarm_query = "SELECT ID_EQUIPEMENTS, NAME_EQUIPEMENT, 
                CASE 
                    WHEN temp_cpu > 65 THEN 'Température CPU > 65°C' 
                    WHEN utilisation_cpu > 70 THEN 'Utilisation CPU > 70%' 
                END as cause 
                FROM equipements 
                WHERE temp_cpu > 65 OR utilisation_cpu > 70";

$alarm_result = $conn->query($alarm_query);
$alarm_count = $alarm_result->num_rows;

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alarme - Supervision Inter-ville</title>
    <link rel="icon" href="./img/logo.png">
    <link rel="stylesheet" href="./style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        .container {
            margin-top: 50px;
        }
        .navbar-nav {
            flex-grow: 1;
        }
        .form-deconnexion {
            margin-left: 20px; 
        }
        .form-retour {
            margin-left: left;
        }
        .table th, .table td {
            text-align: center;
        }
        .navbar-brand {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        .alarm {
            animation: blink 1s step-start infinite;
        }
        @keyframes blink {
            50% {
                background-color: red;
            }
        }
        h1 {
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
            <h1>Alarmes actives</h1>
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>Nom de l'équipement</th>
                        <th>Cause de l'alarme</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='alarm'>";
                            echo "<td>" . htmlspecialchars($row['NAME_EQUIPEMENT']) . "</td>";
                            if ($row['temp_cpu'] > 65) {
                                echo "<td>Température CPU importante</td>";
                            } elseif ($row['utilisation_cpu'] > 70) {
                                echo "<td>Utilisation CPU importante</td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>Aucune alarme active.</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
    // Recharger la page toutes les 20 secondes
    setInterval(function(){
        window.location.reload();
    }, 20000);
</script>
<?php require('footer.php');?>
</body>
</html>
