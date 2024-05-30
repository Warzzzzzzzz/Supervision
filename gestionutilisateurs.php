<?php
include("login.php");

include("session_check.php");

$message = '';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {

        $nom_users = $_POST['nom_users'];
        $prenom_user = $_POST['prenom_user'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $type_users = $_POST['type_users'];


        $sql = "INSERT INTO users (nom_users, prenom_user, username, password, TYPE_USERS) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssss", $nom_users, $prenom_user, $username, $password, $type_users);

            if ($stmt->execute()) {
                $message = 'Utilisateur créé avec succès';
            } else {
                $message = 'Erreur lors de la création de l\'utilisateur: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = 'Erreur de préparation de la requête: ' . $conn->error;
        }
    } elseif (isset($_POST['update'])) {
        $id_users = $_POST['id_users'];
        $new_username = $_POST['new_username'];
        $new_type_users = $_POST['new_type_users'];

        $sql = "UPDATE users SET username = ?, TYPE_USERS = ? WHERE id_users = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssi", $new_username, $new_type_users, $id_users);

            if ($stmt->execute()) {
                $message = 'Utilisateur mis à jour avec succès';
            } else {
                $message = 'Erreur lors de la mise à jour de l\'utilisateur: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = 'Erreur de préparation de la requête: ' . $conn->error;
        }
    }
}

$sql = "SELECT u.id_users, u.nom_users, u.prenom_user, u.TYPE_USERS, u.username, t.LIBELLE_TYPE_USERS 
        FROM users u 
        INNER JOIN type_users t ON u.TYPE_USERS = t.type_users";
$result = $conn->query($sql);

$alarm_query = "SELECT ID_EQUIPEMENTS, NAME_EQUIPEMENT, 'Température CPU > 20°C' as cause FROM equipements WHERE temp_cpu > 20";
$alarm_result = $conn->query($alarm_query);
$alarm_count = $alarm_result->num_rows;

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
                        Créer un utilisateur
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                    <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                        <h2>Créer un compte</h2>
                    </div>
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
                                <button name="submit" type="submit" class="btn btn-success">Créer le compte</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p></p>
                <table class="table table-dark table-hover">
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Type d'utilisateurs</th>
                            <th>Nom d'utilisateurs</th>
                            <th>Suppression d'utilisateurs</th>
                            <th>Modification login</th>
                            <th>Modification Role</th>
                            <th>Modification</th>
                        </tr>
                        <?php
                       
                        if ($result->num_rows > 0) {
                            
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['nom_users'] . "</td>";
                                echo "<td>" . $row['prenom_user'] . "</td>";
                                echo "<td>";
                                    if ($row['TYPE_USERS'] == 'A') {
                                     echo "Administrateur";
                                    } elseif ($row['TYPE_USERS'] == 'T') {
                                        echo "Technicien";
                                    }
                                echo "</td>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>";
                                echo "<form method='post' action='supprimerutilisateurs.php' style='display:inline-block;'>";
                                echo "<input type='hidden' name='id_users' value='" . $row['id_users'] . "'>";
                                echo "<button type='submit' class='btn btn-danger'>Supprimer</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "<td>";
                                echo "<form method='post'>";
                                echo "<input type='hidden' name='id_users' value='" . $row['id_users'] . "'>";
                                echo "<input type='text' name='new_username' value='" . $row['username'] . "'>";
                                echo "</td>";
                                echo "<td>";
                                echo "<select name='new_type_users'>";
                                echo "<option value='A'" . ($row['TYPE_USERS'] == 'A' ? ' selected' : '') . ">Administrateur</option>";
                                echo "<option value='T'" . ($row['TYPE_USERS'] == 'T' ? ' selected' : '') . ">Technicien</option>";
                                echo "</select>";
                                echo "</td>";
                                echo "<td>";
                                echo "<button type='submit' name='update' class='btn btn-success'>Modifier</button>";
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
    </main>
    <script>
    // Recharger la page toutes les 5 secondes
    setInterval(function(){
        window.location.reload();
    }, 5000);
</script>
    <footer>
        <p>Projet Supervision Inter-Ville réalisé par Nicolas LEGAL et Cyril MAGUIRE |2022-2024|</p>
    </footer>
</body>
</html>