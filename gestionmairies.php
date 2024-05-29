<?php
include("login.php"); 

include("session_check.php");

$message = '';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $NOM_MAIRIE = $_POST['NOM_MAIRIE'];
        $ADRESSE_MAIRIE = $_POST['ADRESSE_MAIRIE'];
        $CP_MAIRIE = $_POST['CP_MAIRIE'];
        
        $sql = "INSERT INTO mairie (NOM_MAIRIE, ADRESSE_MAIRIE, CP_MAIRIE) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $NOM_MAIRIE, $ADRESSE_MAIRIE, $CP_MAIRIE);

            if ($stmt->execute()) {
                $message = 'Mairie créée avec succès';
            } else {
                $message = 'Erreur lors de la création de la mairie: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = 'Erreur de préparation de la requête: ' . $conn->error;
        }
    }
}

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
    <main>
        <?php if ($message): ?>
                            <div class="alert alert-info" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                        
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        Créer une mairie
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                    <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                        <h2>Créer une mairie</h2>
                    </div>
                        <div class="d-flex justify-content-center align-items-center vh-40">
                            <form method="POST">
                                <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                                    <label for="NOM_MAIRIE" class="user">Nom</label>
                                    <input type="text" class="form-control" id="NOM_MAIRIE" name="NOM_MAIRIE" aria-describedby="NOM_MAIRIE" required>
                                </div>
                                <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                                    <label for="ADRESSE_MAIRIE" class="user">Adresse</label>
                                    <input type="text" class="form-control" id="ADRESSE_MAIRIE" name="ADRESSE_MAIRIE" aria-describedby="ADRESSE_MAIRIE" required>
                                </div>
                                <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                                    <label for="CP_MAIRIE" class="user">Code Postale</label>
                                    <input type="text" class="form-control" id="CP_MAIRIE" name="CP_MAIRIE" aria-describedby="CP_MAIRIE" required>
                                </div>
                                <button name="submit" type="submit" class="btn btn-success">Créer la mairie</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p></p>
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>Nom des Mairies</th>
                    <th>Adresses des Mairies</th>
                    <th>Code Postale des Mairies</th>
                    <th>Supprimer une Mairie</th>
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
                        echo "<td>";
                        echo "<form method='post' action='supprimermairie.php' style='display:inline-block;'>";
                        echo "<input type='hidden' name='ID_MAIRIE' value='" . $row['ID_MAIRIE'] . "'>";
                        echo "<button type='submit' class='btn btn-danger'>Supprimer</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Aucune Mairie trouvée.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>Projet Supervision Inter-Ville réalisé par Nicolas LEGAL et Cyril MAGUIRE |2022-2024|</p>
    </footer>
</body>
</html>