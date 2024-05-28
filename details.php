<?php 
include("login.php"); 

$message = '';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

$search_query = "";

if (isset($_GET['submit'])) {
    $search_query = $_GET['search'];
}

if (isset($_POST['nom_equipement'])) {
    $nom_equipement = $_POST['nom_equipement'];
    $sql = "SELECT M.nom_mairie, SA.libelle_salle, S.libelle_services, E.NAME_EQUIPEMENT 
        FROM equipements E 
        INNER JOIN services S ON E.ID_EQUIPEMENTS = S.ID_SERVICES 
        INNER JOIN mairie M ON M.ID_MAIRIE = S.ID_MAIRIE 
        INNER JOIN salles SA ON SA.ID_SALLES = S.ID_SALLES 
        WHERE E.LIBELLE_EQUIPEMENTS = 'Routeur'";

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
        .form-retour{
            margin-left: left;
        }
    </style>
</head>
<body>
<header>
<div>
            <nav class="navbar navbar-expand-lg  bg-body-tertiary"data-bs-theme="dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"></a>
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
    </header>
    <main>
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>Mairie</th>
                    <th>Salle</th>
                    <th>Service</th>
                </tr>
            </thead>
            <tbody>
                <?php                           
                if ($result->num_rows > 0) {                       
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['nom_mairie'] . "</td>";
                        echo "<td>" . $row['libelle_salle'] . "</td>";
                        echo "<td>" . $row['libelle_services'] . "</td>";
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
<?php
} else {
    echo "Aucun équipement sélectionné.";
}
?>