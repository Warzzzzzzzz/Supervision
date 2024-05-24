<?php
include("login.php"); 

$message = '';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $nom_users = $_POST['nom_users'];
        $prenom_user = $_POST['prenom_user'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $type_users = $_POST['type_users'];

        
        $sql = "INSERT INTO users (nom_users, prenom_user, username, password, TYPE_USERS) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssss", $nom_users, $prenom_user, $username, $password, $type_users);

            if ($stmt->execute()) {
                $message = 'Compte créé avec succès';
            } else {
                $message = 'Erreur lors de la création du compte: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = 'Erreur de préparation de la requête: ' . $conn->error;
        }
    }
}

$sql = "SELECT id_users, nom_users, prenom_user, TYPE_USERS, username FROM users";
$result = $conn->query($sql);
$conn->close();
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
        </div>
    </header>
    <main>
    <?php if ($message): ?>
        <div class="alert alert-info" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="logged.php">Accueil Connexion</a></li>
            <li class="breadcrumb-item active" aria-current="page">Gestion utilisateurs</li>
        </ol>
    </nav>
    <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    Créer un utilisateur
                </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <h2>Créer un compte</h2>
                    <div class="d-flex justify-content-center align-items-center vh-40">
                        <form method="post">
                            <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                                <label for="nom_users" class="user">Nom</label>
                                <input type="text" class="form-control" id="nom_users" name="nom_users" aria-describedby="nom_users" required>
                            </div>
                            <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                                <label for="prenom_user" class="user">Prénom</label>
                                <input type="text" class="form-control" id="prenom_user" name="prenom_user" aria-describedby="prenom_user" required>
                            </div>
                            <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                                <label for="username" class="user">Nom d'utilisateur</label>
                                <input type="text" class="form-control" id="username" name="username" aria-describedby="username" required>
                            </div>
                            <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                                <label for="password" class="user">Mot de Passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                                <label for="type_users" class="user">Type d'utilisateur</label>
                                <select class="form-control" id="type_users" name="type_users" required>
                                    <option value="A">Administrateur</option>
                                    <option value="T">Technicien</option>
                                </select>
                            </div>
                            <button name="submit" type="submit" class="btn btn-danger">Créer le compte</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                    Liste des utilisateurs
                </button>
            </h2>
            <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                <table class="table table-dark table-hover">
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Type d'utilisateurs</th>
                            <th>Nom d'utilisateurs</th>
                            <th>Suppression d'utilisateurs</th>
                        </tr>
                        <?php
                       
                        if ($result->num_rows > 0) {
                            
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['nom_users'] . "</td>";
                                echo "<td>" . $row['prenom_user'] . "</td>";
                                echo "<td>" . $row['TYPE_USERS'] . "</td>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>";
                                echo "<form method='post' action='supprimerutilisateur.php' style='display:inline-block;'>";
                                echo "<input type='hidden' name='id_users' value='" . $row['id_users'] . "'>";
                                echo "<button type='submit' class='btn btn-danger'>Supprimer</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Aucun utilisateur trouvé.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </main>
    <footer>
        <p>Projet Supervision Inter-Ville réalisé par Nicolas LEGAL et Cyril RESCUER |2022-2024|</p>
    </footer>
</body>
</html>
