<?php
require('accessDB.php');
require ('./PHPMailer-Files/script.php');



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si l'adresse e-mail a été fournie
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        
        // Générer un token unique
        $token = bin2hex(random_bytes(32)); // Génère un token de 64 caractères hexadécimaux (32 bytes)
        
        // Assurez-vous d'avoir une connexion à la base de données correctement configurée ici
        // Remplacez 'votre_table' par le nom de votre table dans la base de données
        $sql = "UPDATE users SET token = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();
        
        // Préparez le lien avec le token inclus
        $resetLink = 'http://192.168.200.9/Supervision/newpassword.php?token=' . $token;
        
        // Envoyer l'e-mail avec le lien de réinitialisation
        $subject = 'Réinitialiser le mot de passe';
        $message = 'Cliquez sur le lien suivant pour réinitialiser votre mot de passe : ' . $resetLink;
        sendmail($email, $subject, $message);
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
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" aria-describedby="email" required>
            </div>
            <div class="d-grid">
                <button name="submit" type="submit" class="btn btn-success">Envoyée un lien</button>
                
            </div>
        </form>
    </main>
    <?php require('footer.php');?>
</body>
</html>