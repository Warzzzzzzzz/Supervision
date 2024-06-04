<?php
include("session_check.php");
require('accessDB.php');

$sql = "SELECT ID_MAIRIE, NOM_MAIRIE, ADRESSE_MAIRIE, CP_MAIRIE FROM mairie";
$result = $conn->query($sql);
$conn->close();
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
        .table-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 56px - 40px);
        }
        .navbar-nav {
            flex-grow: 1;
        }
        .form-deconnexion {
            margin-left: 20px; 
        }
        .table th, .table td {
            text-align: center;
        }
        main {
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
    <main>
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>Nom des Mairies</th>
                    <th>Adresses des Mairies</th>
                    <th>Code Postale des Mairies</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['NOM_MAIRIE'] . "</td>";
                        echo "<td>" . $row['ADRESSE_MAIRIE'] . "</td>";
                        echo "<td>" . $row['CP_MAIRIE'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Aucune Mairie trouvée.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
    <?php require('footer.php');?>
</body>
</html>