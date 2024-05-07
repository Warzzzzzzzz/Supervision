<?php
session_start();

$servername = "localhost";  // ou 127.0.0.1
$username = "root"; // Ton nom d'utilisateur pour la base de données
$password = "";     // Ton mot de passe pour la base de données
$dbname = "supervision-inter-ville";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT ID_USERS, PASSWORD FROM users WHERE NOM_USERS = '$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['PASSWORD'])) {
            // Connexion réussie, mettre en place la session
            $_SESSION['user_id'] = $row['ID_USERS'];
            header("Location: welcome.php"); // Rediriger vers la page d'accueil sécurisée
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec ce nom.";
    }
}

$conn->close();
?>