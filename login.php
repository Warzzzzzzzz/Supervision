<?php
session_start();



$servername = "localhost";
$dbname = "supervision-inter-ville";
$username = "root"; 
$password = ""; 

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") 
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
  
    $sql = "SELECT ID_USERS, type_users, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
     
     $user = $result->fetch_assoc();
     $type_users =$result->fetch_assoc();

    
     if ($password === $user['password']) {
       
         $_SESSION['username'] = $user['username'];
         echo "Connexion réussie !";
     
         header("Location: logged.php");
         exit();
     } else {

         echo "Mot de passe incorrect.";
     }
 }
 

$stmt->close();
}

?>


