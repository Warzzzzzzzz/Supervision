<?php
session_start();
$message = '';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

include("login.php");

$search_query = "";

if (isset($_GET['submit'])) {
    $search_query = $_GET['search'];
}

$sql = "SELECT e.ID_EQUIPEMENTS, e.LIBELLE_EQUIPEMENTS, e.NAME_EQUIPEMENT, e.created_at
        FROM equipements e
        INNER JOIN (
            SELECT NAME_EQUIPEMENT, MAX(created_at) as MaxDate
            FROM equipements
            GROUP BY NAME_EQUIPEMENT
        ) as latest
        ON e.NAME_EQUIPEMENT = latest.NAME_EQUIPEMENT AND e.created_at = latest.MaxDate";

if (!empty($search_query)) {
    $sql .= " WHERE e.NAME_EQUIPEMENT LIKE '%$search_query%'";
}

$result = $conn->query($sql);
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
        .table th, .table td {
            text-align: center;
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
                                <a class="nav-link" href="presentation.html">Présentation</a>
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
                        </ul>
                        <form class="form-deconnexion" method="post" action="logout.php">
                            <button type="submit" class="btn btn-danger">Se Déconnecter</button>
                        </form>
                        <form class="form-account" method="post" action="account.php">
                            
                            <button type="submit" class="btn btn-light"><?php
                            if (isset($_SESSION['nom_users']) && isset($_SESSION['prenom_user'])) {
                                echo "" . htmlspecialchars($_SESSION['prenom_user']) . " " . htmlspecialchars($_SESSION['nom_users']);
                            }
                            ?></button>
                            </form>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    
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
                        <th>ID équipements</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $highlight = (!empty($search_query) && stripos($row['NAME_EQUIPEMENT'], $search_query) !== false) ? 'table-warning' : '';

                            echo "<tr class='$highlight'>";
                            echo "<td>" . htmlspecialchars($row['LIBELLE_EQUIPEMENTS']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['NAME_EQUIPEMENT']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ID_EQUIPEMENTS']) . "</td>";
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
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer>
        <p>Projet Supervision Inter-Ville réalisé par Nicolas LEGAL et Cyril RESCUER |2022-2024|</p>
    </footer>
</body>
</html>
