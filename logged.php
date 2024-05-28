<?php
$message = '';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

include("login.php");

$search_query = "";

if (isset($_GET['submit'])) {
    $search_query = $_GET['search'];
}


$sql = "SELECT e.LIBELLE_EQUIPEMENTS, e.NAME_EQUIPEMENT, e.created_at
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
    <link rel="icon" href="logo.png">
    <link rel="stylesheet" href="./style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <div>
            <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
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
<p></p>
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
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Vérifier si le NAME_EQUIPEMENT contient la chaîne de recherche pour ajouter la classe de surlignage
                        $highlight = (!empty($search_query) && stripos($row['NAME_EQUIPEMENT'], $search_query) !== false) ? 'table-warning' : '';

                        echo "<tr class='$highlight'>";
                        echo "<td>" . $row['LIBELLE_EQUIPEMENTS'] . "</td>";
                        echo "<td>" . $row['NAME_EQUIPEMENT'] . "</td>";
                        echo "<td>";
                        echo "<form method='post' action='details.php' style='display:inline-block;'>";
                        echo "<input type='hidden' name='nom_equipement' value='" . $row['NAME_EQUIPEMENT'] . "'>";
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
    </main>
    <footer>
        <p>Projet Supervision Inter-Ville réalisé par Nicolas LEGAL et Cyril RESCUER |2022-2024|</p>
    </footer>
</body>
</html>