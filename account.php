<?php

include("session_check.php");

if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}

require('accessDB.php');

$id_user = $_SESSION['id_user']; 

$sql = "SELECT * FROM users WHERE id_users = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $nom_users = $_POST['nom_users'];
    $prenom_user = $_POST['prenom_user'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $current_password = $_POST['current_password'];

    // Vérifiez le mot de passe actuel
    if (password_verify($current_password, $user['password'])) {
        $new_password_hashed = password_hash($password, PASSWORD_BCRYPT);

        $sql_update = "UPDATE users SET nom_users = ?, prenom_user = ?, username = ?, email = ?, password = ? WHERE id_users = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssssi", $nom_users, $prenom_user, $username, $email, $new_password_hashed, $id_user);
        
        if ($stmt_update->execute()) {
            $message = "Les informations ont été mises à jour avec succès.";
            $_SESSION['nom_users'] = $nom_users;
            $_SESSION['prenom_user'] = $prenom_user;
        } else {
            $message = "Une erreur est survenue lors de la mise à jour des informations.";
        }
    } else {
        $message = "Le mot de passe actuel est incorrect.";
    }
}

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
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 75vh; 
        }

        .logo {
            max-width: 100%;
            max-height: 100%; 
        }
        .navbar-nav {
            flex-grow: 1;
        }
        .form-deconnexion {
            margin-left: 20px; 
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
    <?php require('header.php');?>
    <main>
    <?php if (isset($message)): ?>
                <div class="alert alert-info">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
            <h2>Modifier mon compte</h2>
        </div>
        <div class="d-flex justify-content-center align-items-center vh-40">
            <form method="post">
                <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                    <label for="nom_users" class="user">Nom</label>
                    <input type="text" class="form-control" id="nom_users" name="nom_users" value="<?php echo htmlspecialchars($user['nom_users']); ?>" required>
                </div>
                <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                    <label for="prenom_user" class="user">Prénom</label>
                    <input type="text" class="form-control" id="prenom_user" name="prenom_user" value="<?php echo htmlspecialchars($user['prenom_user']); ?>" required>
                </div>
                <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                    <label for="username" class="user">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                    <label for="email" class="user">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                    <label for="current_password" class="user">Mot de Passe Actuel</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
                    <label for="password" class="user">Nouveau Mot de Passe</label>
                    <input type="password" class="form-control" id="password" name="password"required>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Mettre à jour</button>
            </form>
        </div>
    </main>
    <?php require('footer.php');?>
</body>
</html>
