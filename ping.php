<?php
if (isset($_GET['ip'])) {
    $ipAddress = $_GET['ip'];
    // Exécutez la commande ping
    exec("ping -n 4 $ipAddress", $output, $status); // Utilisation de -n au lieu de -c pour Windows
    // Renvoie le résultat
    echo implode("<br>", $output);
} 
    
?>
