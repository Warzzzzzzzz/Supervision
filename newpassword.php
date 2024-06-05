<?php
require('accessDB.php');
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si le champ password existe dans la requête
    if (isset($_POST['password'])) {
        // Récupérer le token à partir de l'URL
        $token = $_GET['token'] ?? '';
        
        // Récupérer le nouveau mot de passe depuis le formulaire
        $password = $_POST['password'];
        
        // Recherchez l'utilisateur correspondant au token dans la base de données
        $sql = "SELECT * FROM users WHERE token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Vérifier s'il y a un utilisateur correspondant
        if ($result->num_rows === 1) {
            // Mettre à jour le mot de passe de l'utilisateur
            $user = $result->fetch_assoc();
            $userId = $user['id_users']; // Remplacez 'id' par le nom de votre colonne d'identifiant utilisateur
            $password = password_hash($password, PASSWORD_DEFAULT); // Hachez le nouveau mot de passe
            
            $updateSql = "UPDATE users SET password = ? WHERE id_users = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("si", $password, $userId);
            $updateStmt->execute();
            
            // Rediriger l'utilisateur vers une page de confirmation ou une autre page appropriée
            header("Location: login.php");
            exit();
        } else {
            // Gérer le cas où aucun utilisateur correspondant n'est trouvé
            echo "Utilisateur non trouvé.";
        }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
        .password-wrapper {
            position: relative;
        }
        .password-wrapper .toggle-password {
            position: absolute;
            top: 75%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
    </head>
<body>
    <main class="d-flex justify-content-center align-items-center vh-100">
        <form method="post" class="shadow p-4 bg-body-tertiary rounded text-center">
            <div class="mb-4">
                <img src="./img/logo.png" alt="Logo" style="width: 50px; height: 50px;">
                <h2 class="d-inline-block align-middle ms-2">Supervision Inter-ville</h2>
            </div>
            <div class="mb-3">
            <div class="mb-3 password-wrapper">
        <label for="password" class="form-label">Nouveau mot de passe :</label>
        <input type="password" class="form-control" id="password" name="password" aria-describedby="password" required>
        <i class="fas fa-eye toggle-password" id="togglePassword"></i>
    </div>

    <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        var passwordInput = document.getElementById('password');
        var toggleIcon = this;
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    });
    </script>

            <div class="d-grid">
                <button name="submit" type="submit" class="btn btn-success">Modifié</button>
            </div>
        </form>
    </main>
    <?php require('footer.php');?>
</body>
</html>
