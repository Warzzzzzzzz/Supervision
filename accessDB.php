<?php
$servername = "localhost";
$dbname = "supervision-inter-ville";
$username = "root"; 
$password = ""; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}
?>