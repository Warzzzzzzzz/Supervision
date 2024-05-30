<?php
include("session_check.php");
include("login.php");

$sql = "SELECT NAME_EQUIPEMENT, temp_cpu 
        FROM equipements 
        WHERE temp_cpu > 20"; // Condition pour déclencher une alarme

$alarm_query = "SELECT ID_EQUIPEMENTS, NAME_EQUIPEMENT, 'Température CPU > 20°C' as cause FROM equipements WHERE temp_cpu > 20";
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
    <header>
        <div>
            <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="connexion.php">Accueil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="presentation.php">Présentation</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="dashboard.php">DashBoard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="gestionmairies.php">Gestion Mairies</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="gestionutilisateurs.php">Gestion utilisateurs</a>
                            </li>
                            <form class="form-alarmes" method="post" action="alarm.php">
                            <button type="submit" class="btn btn-light <?php echo $alarm_count > 0 ? 'btn-alarm' : ''; ?>">Alarmes</button>
                        </form>
                        </ul>
                        <form class="form-account" method="post" action="account.php">
                            <button type="submit" class="btn btn-light">
                                <?php
                                if (isset($_SESSION['nom_users']) && isset($_SESSION['prenom_user'])) {
                                    echo "" . htmlspecialchars($_SESSION['prenom_user']) . " " . htmlspecialchars($_SESSION['nom_users']);
                                }
                                ?>
                            </button>
                        </form>
                        <form class="form-deconnexion" method="post" action="logout.php">
                            <button type="submit" class="btn btn-danger">Se Déconnecter</button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    
    <main class="table-container">
        <div>
            <h1>Alarmes active</h1>
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
                            echo "<td>Température CPU supérieure à 20°C</td>";
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
    // Recharger la page toutes les 5 secondes
    setInterval(function(){
        window.location.reload();
    }, 10000);
</script>
    <footer>
        <p>Projet Supervision Inter-Ville réalisé par Nicolas LEGAL et Cyril MAGUIRE |2022-2024|</p>
    </footer>
</body>
</html>
