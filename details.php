<?php
include("login.php");

include("session_check.php");

$message = '';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

if (isset($_POST['ID_EQUIPEMENTS'])) {
    $id_equipement = $_POST['ID_EQUIPEMENTS'];

    // Requête pour obtenir les détails de l'équipement
    $sql = $conn->prepare("SELECT M.nom_mairie, SA.libelle_salle, S.libelle_services, E.NAME_EQUIPEMENT,
                                  E.temps_uptime, E.latence, E.debit_rx, E.debit_tx, E.temp_cpu, E.utilisation_cpu, E.address_ip
                           FROM equipements E 
                           INNER JOIN services S ON E.ID_EQUIPEMENTS = S.ID_SERVICES 
                           INNER JOIN mairie M ON M.ID_MAIRIE = S.ID_MAIRIE 
                           INNER JOIN salles SA ON SA.ID_SALLES = S.ID_SALLES 
                           WHERE E.ID_EQUIPEMENTS = ?");
    $sql->bind_param("i", $id_equipement);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nom_equipement = $row['NAME_EQUIPEMENT'];
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
                    margin-left: auto; 
                }
                .form-retour {
                    margin-left: left;
                }
                .navbar-brand {
                    position: absolute;
                    left: 50%;
                    transform: translateX(-50%);
                }
                .table th, .table td {
            text-align: center;
        }
            </style>
        </head>
        <body>
        <div>
            <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"><?php echo htmlspecialchars($nom_equipement); ?></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <form class="form-retour" method="post" action="dashboard.php">
                            <button type="submit" class="btn btn-secondary">Retour</button>
                        </form>
                        <form class="form-deconnexion" method="post" action="logout.php">
                            <button type="submit" class="btn btn-danger">Se Déconnecter</button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>
        <main>
            <div class="table-container">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Mairie</th>
                            <th>Salle</th>
                            <th>Service</th>
                            <th>Temps Uptime</th>
                            <th>Latence</th>
                            <th>Débit RX</th>
                            <th>Débit TX</th>
                            <th>Temp CPU</th>
                            <th>Utilisation CPU</th>
                            <th>Address IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Affichage de la première ligne déjà récupérée
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nom_mairie']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['libelle_salle']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['libelle_services']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['temps_uptime']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['latence']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['debit_rx']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['debit_tx']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['temp_cpu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['utilisation_cpu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['address_ip']) . "</td>";
                        echo "</tr>";

                        // Boucle pour afficher les autres résultats, le cas échéant
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nom_mairie']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['libelle_salle']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['libelle_services']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['temps_uptime']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['latence']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['debit_rx']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['debit_tx']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['temp_cpu']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['utilisation_cpu']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['address_ip']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
        <footer>
        <p>Projet Supervision Inter-Ville réalisé par Nicolas LEGAL et Cyril MAGUIRE |2022-2024|</p>
    </footer>
        </body>
        </html>
        <?php
    } else {
        echo "Aucun résultat trouvé pour l'ID équipement spécifié.";
    }
    $sql->close();
    $conn->close();
} else {
    echo "Aucun équipement sélectionné.";
}
?>
