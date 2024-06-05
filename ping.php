<?php
if (isset($_GET['ip'])) {
    $ipAddress = escapeshellarg($_GET['ip']);
    
    // Pour les systèmes Unix/Linux
    $pingResult = shell_exec("ping -n 4 $ipAddress");
    
    // Pour les systèmes Windows, décommentez la ligne suivante et commentez celle du dessus
    // $pingResult = shell_exec("ping -n 4 $ipAddress");

    if ($pingResult) {
        echo "<pre>$pingResult</pre>";
    } else {
        echo "Échec du ping. Vérifiez l'adresse IP et réessayez.";
    }
} else {
    echo "Aucune adresse IP fournie.";
}
?>
