<?php
if (isset($_GET['ip'])) {
    $ipAddress = escapeshellarg($_GET['ip']);
    // Exécutez la commande ping
    exec("ping -n 4 $ipAddress", $output, $status); // Utilisation de -c pour Linux, utilisez -n pour Windows
    $response = htmlspecialchars(implode(" ", $output), ENT_QUOTES, 'UTF-8');
    if ($status === 0) {
        echo json_encode(['status' => 'Online', 'response' => $response]);
    } else {
        echo json_encode(['status' => 'Offline', 'response' => $response]);
    }
} 
?>