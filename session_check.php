<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarrer la session seulement si elle n'est pas déjà démarrée
}
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>