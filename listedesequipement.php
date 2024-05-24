<?php
$message = '';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}


include("login.php"); 

$sql = "SELECT LIBELLE_EQUIPEMENTS, NAME_EQUIPEMENT, STATUS_EQUIPEMENTS FROM equipements";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervision Inter-ville</title>
    <link rel="icon" href="logo.png">
    <link rel="stylesheet" href="./style/stylelistedesequipements.css">
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
            <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <span class="badge text-bg-danger">ADMIN</span>
                        <ul class="navbar-nav">
                      <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="dashboard.php">DashBoard</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="latence.php">Latence</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="logs.php">Logs</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="listedesequipement.php">Listes des équipements</a>
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
        </div>
    </header>
    <main>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="logged.php">Accueil Connexion</a></li>
                <li class="breadcrumb-item active" aria-current="page">Liste des équipements</li>
            </ol>
        </nav>
                <table class="table-secondary">
                        <thead>
                            <tr>
                                <th>Type d'équipements</th>
                                <th>Nom d'équipements</th>
                                <th>Status d'équipements</th>
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
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>Aucun équipement trouvé.</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p>Projet Supervision Inter-Ville réalisé par Nicolas LEGAL et Cyril RESCUER |2022-2024|</p>
    </footer>
</body>
</html>
