<?php
$message = '';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

include("login.php"); 



$search_query = "";

if(isset($_GET['submit'])) {
  
    $search_query = $_GET['search'];
}
$sql = "SELECT LIBELLE_EQUIPEMENTS, NAME_EQUIPEMENT, STATUS_EQUIPEMENTS, taux_de_charge, temps_uptime, latence, debit_rx, debit_tx, adresse_ip, temp_cpu, created_at FROM equipements";

if(!empty($search_query)) {
    $sql .= " WHERE LIBELLE_EQUIPEMENTS LIKE '%$search_query%' OR NAME_EQUIPEMENT LIKE '%$search_query%'";
}

$result = $conn->query($sql);



$sql = "SELECT LIBELLE_EQUIPEMENTS, NAME_EQUIPEMENT, STATUS_EQUIPEMENTS, taux_de_charge, temps_uptime, latence, debit_rx, debit_tx, adresse_ip, temp_cpu, created_at FROM equipements";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervision Inter-ville</title>
    <link rel="icon" href="logo.png">
    <link rel="stylesheet" href="./style/stylegestionutilisateurs.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <div>
            <h1>Supervision Inter-Ville</h1> 
            <form class="form-deconnexion" method="post" action="logout.php">
                <button type="submit" class="btn btn-danger">Se Déconnecter</button>
            </form>
            <nav class="navbar navbar-expand-lg  bg-body-tertiary" data-bs-theme="dark">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <span class="badge text-bg-danger">ADMIN</span>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="dashboard.php">DashBoard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="gestionmairies.php">Gestion des Mairies</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="gestionutilisateurs.php">Gestion Utilisateurs</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <?php
            if (!isset($_SESSION['username'])) {
                header('Location: index.html');
                exit;
            }
            ?>
        </div>
    </header>
    <main>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="logged.php">Accueil Connexion</a></li>
                <li class="breadcrumb-item active" aria-current="page">DashBoard</li>
            </ol>
        </nav>
        <form method="GET" action="">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Rechercher par type ou nom d'équipement" name="search">
                <button class="btn btn-secondary" type="submit" name="submit">Rechercher</button>
            </div>
        </form>
         <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>Type d'équipements</th>
                    <th>Nom d'équipements</th>
                    <th>Status d'équipements</th>
                    <th>Taux de Charge</th>
                    <th>Temps Uptime</th>
                    <th>Latence</th>
                    <th>Débit RX</th>
                    <th>Débit TX</th>
                    <th>Addresses IP</th>
                    <th>Températures CPU</th>
                    <th>Dates et Heures</th>
                </tr>
            </thead>
            <tbody>
                <?php                           
                if ($result->num_rows > 0) {                       
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['LIBELLE_EQUIPEMENTS'] . "</td>";
                        echo "<td>" . $row['NAME_EQUIPEMENT'] . "</td>";
                        echo "<td>" . $row['STATUS_EQUIPEMENTS'] . "</td>";
                        echo "<td>" . $row['taux_de_charge'] . "</td>";
                        echo "<td>" . $row['temps_uptime'] . "</td>";
                        echo "<td>" . $row['latence'] . "</td>";
                        echo "<td>" . $row['debit_rx'] . "</td>";
                        echo "<td>" . $row['debit_tx'] . "</td>";
                        echo "<td>" . $row['adresse_ip'] . "</td>";
                        echo "<td>" . $row['temp_cpu'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>Aucun résultat trouvé.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
            
        </table>
    </main>
    <footer>
        <p>Projet Supervision Inter-Ville réalisé par Nicolas LEGAL et Cyril RESCUER |2022-2024|</p>
    </footer>
</body>
</html>
