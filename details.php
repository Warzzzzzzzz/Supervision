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
            <p></p>
            <form class="form-deconnexion" method="post" action="dashboard.php">
                <button type="submit" class="btn btn-secondary">Retour</button>
            </form>
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
