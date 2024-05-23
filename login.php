<?php
session_start();

// Configuration de la connexion à la base de données

$servername = "localhost";
$dbname = "supervision-inter-ville";
$username = "root"; // Remplacez par votre nom d'utilisateur MySQL
$password = ""; // Remplacez par votre mot de passe MySQL

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Requête pour sélectionner l'utilisateur
    $sql = "SELECT ID_USERS, type_users, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
     // Obtenir les données de l'utilisateur
     $user = $result->fetch_assoc();
     $type_users =$result->fetch_assoc();

     // Vérifier le mot de passe
     if ($password === $user['password']) {
         // Mot de passe correct
         $_SESSION['username'] = $user['username'];
         echo "Connexion réussie !";
         // Redirection vers une page protégée
         header("Location: logged.php");
         exit();
     } else {
         // Mot de passe incorrect
         echo "Mot de passe incorrect.";
     }
 }

$stmt->close();
}

?>