<?php
$servername = "localhost";
$dbname = "supervision-inter-ville";
$username = "supervision"; 
$password = "Supervision59*"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}
?>