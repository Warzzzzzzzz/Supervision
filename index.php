<?php
include("login.php");
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $sql = "SELECT ID_USERS, type_users, username, password, nom_users, prenom_user FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['id_user'] = $user['ID_USERS']; 
                $_SESSION['username'] = $user['username'];
                $_SESSION['nom_users'] = $user['nom_users'];
                $_SESSION['prenom_user'] = $user['prenom_user'];
                echo "Connexion réussie !";
                header("Location: connexion.php");
                exit();
            } else {
                $message = 'Mot de passe incorrect';
            }
        } else {
            $message = 'Nom utilisateur incorrect';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervision inter-ville</title>
    <link rel="icon" href="./img/logo.png">
    <link rel="stylesheet" href="./style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <main class="d-flex justify-content-center align-items-center vh-100">
        <form method="post" class="shadow p-4 bg-body-tertiary rounded text-center">
            <div class="mb-4">
                <img src="./img/logo.png" alt="Logo" style="width: 50px; height: 50px;">
                <h2 class="d-inline-block align-middle ms-2">Supervision Inter-ville</h2>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" aria-describedby="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de Passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <?php if (!empty($message)): ?>
                <div class="mb-3 alert alert-danger">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <div class="d-grid">
                <button name="submit" type="submit" class="btn btn-success">Se connecter</button>
            </div>
        </form>
    </main>
    <footer>
        <p>Projet Supervision Inter-Ville réalisé par Nicolas LEGAL et Cyril MAGUIRE |2022-2024|</p>
    </footer>
</body>
</html>
