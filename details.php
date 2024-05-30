<?php
include("login.php");
include("session_check.php");

$message = '';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

if (isset($_POST['ID_EQUIPEMENTS'])) {
    $id_equipement = $_POST['ID_EQUIPEMENTS'];

    // Récupération des données de Zabbix
    function zabbixApiRequest($url, $authToken, $method, $params) {
        $payload = json_encode([
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => $params,
            'auth' => $authToken,
            'id' => 1
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    $url = 'http://192.168.112.153/api_jsonrpc.php'; // URL de l'API Zabbix
    $authToken = 'b930180ba40730660536b3d3c0d07c47'; // Token d'authentification

    // Étape 1 : Récupérer tous les éléments
    $itemParams = [
        'output' => ['hostid', 'lastvalue', 'name', 'lastclock', 'hostid'],
        'selectHosts' => ['host']
    ];

    $itemResponse = zabbixApiRequest($url, $authToken, 'item.get', $itemParams);
    if (isset($itemResponse['error'])) {
        echo 'Erreur dans la requête item.get : ' . htmlspecialchars(json_encode($itemResponse['error'])) . '<br>';
        exit;
    }
    $items = $itemResponse['result'];

    // Étape 2 : Filtrer les éléments localement pour les débits reçus et envoyés
    $filteredItemsRX = [];
    $filteredItemsTX = [];
    $keywordsRX = "Bits received";
    $keywordsTX = "Bits sent";

    foreach ($items as $item) {
        if (strpos($item['name'], $keywordsRX) !== false) {
            $filteredItemsRX[] = $item;
        } elseif (strpos($item['name'], $keywordsTX) !== false) {
            $filteredItemsTX[] = $item;
        }
    }
    $filteredItemsTempCPU = [];
    $keywordsTempCPU = "CPU temperature";
    
    foreach ($items as $item) {
        if (strpos($item['name'], $keywordsRX) !== false) {
            $filteredItemsRX[] = $item;
        } elseif (strpos($item['name'], $keywordsTX) !== false) {
            $filteredItemsTX[] = $item;
        } elseif (strpos($item['name'], $keywordsTempCPU) !== false) {
            $filteredItemsTempCPU[] = $item;
        }
    }

    $filteredItemsUptime = [];
$filteredItemsLatency = [];
$filteredItemsCpuUsage = [];
$keywordsUptime = "Uptime";
$keywordsLatency = "Latency";
$keywordsCpuUsage = "CPU utilization";

foreach ($items as $item) {
    if (strpos($item['name'], $keywordsRX) !== false) {
        $filteredItemsRX[] = $item;
    } elseif (strpos($item['name'], $keywordsTX) !== false) {
        $filteredItemsTX[] = $item;
    } elseif (strpos($item['name'], $keywordsTempCPU) !== false) {
        $filteredItemsTempCPU[] = $item;
    } elseif (strpos($item['name'], $keywordsUptime) !== false) {
        $filteredItemsUptime[] = $item;
    } elseif (strpos($item['name'], $keywordsLatency) !== false) {
        $filteredItemsLatency[] = $item;
    } elseif (strpos($item['name'], $keywordsCpuUsage) !== false) {
        $filteredItemsCpuUsage[] = $item;
    }
}

    
    // Étape 3 : Récupérer les interfaces des hôtes pour obtenir les adresses IP
    $hostIds = array_unique(array_column($items, 'hostid'));
    $interfaceParams = [
        'output' => ['hostid', 'ip'],
        'hostids' => $hostIds
    ];

    $interfaceResponse = zabbixApiRequest($url, $authToken, 'hostinterface.get', $interfaceParams);
    if (isset($interfaceResponse['error'])) {
        echo 'Erreur dans la requête hostinterface.get : ' . htmlspecialchars(json_encode($interfaceResponse['error'])) . '<br>';
        exit;
    }
    $interfaces = $interfaceResponse['result'];
    if (empty($interfaces)) {
        echo 'Aucune interface trouvée.<br>';
        exit;
    }

    // Créer un dictionnaire pour mapper hostid à IP
    $hostIdIpMapping = [];
    foreach ($interfaces as $interface) {
        $hostIdIpMapping[$interface['hostid']] = $interface['ip'];
    }

foreach ($filteredItemsRX as $itemRX) {
    $hostId = $itemRX['hostid'] ?? '';
    $hostName = $itemRX['hosts'][0]['host'] ?? 'Unknown Host'; // Utilisez '??' pour fournir une valeur par défaut
    $bitsRX = $itemRX['lastvalue'] ?? 0;
    $timeRX = (time() - ($itemRX['lastclock'] ?? time()));

    $kilobitsRX = $bitsRX / 1000;
    $kbpsRX = ($timeRX > 0) ? $kilobitsRX / $timeRX : 0; // Éviter la division par zéro

    // Trouver l'élément correspondant pour le débit TX
    $matchingItemTX = null;
    foreach ($filteredItemsTX as $itemTX) {
        if ($itemTX['hostid'] === $hostId) {
            $matchingItemTX = $itemTX;
            break;
        }
    }

    $bitsTX = $matchingItemTX['lastvalue'] ?? 0;
    $timeTX = (time() - ($matchingItemTX['lastclock'] ?? time()));
    $kilobitsTX = $bitsTX / 1000;
    $kbpsTX = ($timeTX > 0) ? $kilobitsTX / $timeTX : 0; // Éviter la division par zéro

    // Trouver l'élément correspondant pour la température CPU
    $matchingItemTempCPU = null;
    foreach ($filteredItemsTempCPU as $itemTempCPU) {
        if ($itemTempCPU['hostid'] === $hostId) {
            $matchingItemTempCPU = $itemTempCPU;
            break;
        }
    }
    $tempCPU = $matchingItemTempCPU['lastvalue'] ?? 0;

    // Trouver l'élément correspondant pour le temps d'uptime
    $matchingItemUptime = null;
    foreach ($filteredItemsUptime as $itemUptime) {
        if ($itemUptime['hostid'] === $hostId) {
            $matchingItemUptime = $itemUptime;
            break;
        }
    }
    $uptime = $matchingItemUptime['lastvalue'] ?? 0;

    // Trouver l'élément correspondant pour la latence
    $matchingItemLatency = null;
    foreach ($filteredItemsLatency as $itemLatency) {
        if ($itemLatency['hostid'] === $hostId) {
            $matchingItemLatency = $itemLatency;
            break;
        }
    }
    $latency = $matchingItemLatency['lastvalue'] ?? 0;

    // Trouver l'élément correspondant pour l'utilisation CPU
    $matchingItemCpuUsage = null;
    foreach ($filteredItemsCpuUsage as $itemCpuUsage) {
        if ($itemCpuUsage['hostid'] === $hostId) {
            $matchingItemCpuUsage = $itemCpuUsage;
            break;
        }
    }
    $cpuUsage = $matchingItemCpuUsage['lastvalue'] ?? 0;

    // Supposons que $conn est déjà connecté à la base de données
    $ipAddress = $hostIdIpMapping[$hostId] ?? '0.0.0.0'; // Fournir une adresse IP par défaut si non trouvée

    // Requête d'insertion SQL
    $sql = "SELECT * FROM equipements WHERE ID_EQUIPEMENTS = '$hostId'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $sql = "UPDATE equipements SET 
                NAME_EQUIPEMENT = '$hostName', 
                debit_rx = '$kbpsRX', 
                debit_tx = '$kbpsTX', 
                address_ip = '$ipAddress', 
                temp_cpu = '$tempCPU', 
                temps_uptime = '$uptime', 
                latence = '$latency', 
                utilisation_cpu = '$cpuUsage' 
                WHERE ID_EQUIPEMENTS = '$hostId'";
    } else {
        $sql = "INSERT INTO equipements 
                (ID_EQUIPEMENTS, NAME_EQUIPEMENT, debit_rx, debit_tx, address_ip, temp_cpu, temps_uptime, latence, utilisation_cpu)
                VALUES ('$hostId', '$hostName', '$kbpsRX', '$kbpsTX', '$ipAddress', '$tempCPU', '$uptime', '$latency', '$cpuUsage')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Erreur lors de l'insertion des données : " . $conn->error;
    }
}
    
// Récupération des détails de l'équipement
$sql = $conn->prepare("SELECT NAME_EQUIPEMENT, debit_rx, debit_tx, address_ip, temp_cpu, temps_uptime, latence, utilisation_cpu
                       FROM equipements 
                       WHERE ID_EQUIPEMENTS = ?");
$sql->bind_param("i", $id_equipement);
$sql->execute();
$result = $sql->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nom_equipement = $row['NAME_EQUIPEMENT'];
    $debit_rx = $row['debit_rx'];
    $debit_tx = $row['debit_tx'];
    $ipAddress = $row['address_ip'];
    $temp_cpu = $row['temp_cpu'];
    $uptime = $row['temps_uptime'];
    $latency = $row['latence'];
    $cpuUsage = $row['utilisation_cpu'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervision Inter-ville</title>
    <link rel="icon" href="./img/logo.png">
    <link rel="stylesheet" href="./style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container {
            margin-top: 50px;
        }
        .navbar-nav {
            flex-grow: 1;
        }
        .form-deconnexion {
            margin-left: auto; 
        }
        .form-retour {
            margin-left: left;
        }
        .table th, .table td {
            text-align: center;
        }
        .navbar-brand {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        
    </style>
</head>
<body>
<header>
    <div>
        <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><?php echo htmlspecialchars($nom_equipement); ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <form class="form-retour" method="post" action="dashboard.php">
                        <button type="submit" class="btn btn-secondary">Retour</button>
                    </form>
                    <form class="form-deconnexion" method="post" action="logout.php">
                        <button type="submit" class="btn btn-danger">Se Déconnecter</button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
</header>
<main>
    <div class="container">
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>Nom de l'équipement</th>
                    <th>Adresse IP</th>
                    <th>Débit RX</th>
                    <th>Débit TX</th>
                    <th>Température</th>
                    <th>Uptime</th>
                    <th>Latence</th>
                    <th>Utilisation CPU</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($nom_equipement); ?></td>
                    <td><?php echo htmlspecialchars($ipAddress); ?></td>
                    <td><?php echo htmlspecialchars(number_format($debit_rx, 2, '.', '')); ?> ko/s</td>
                    <td><?php echo htmlspecialchars(number_format($debit_tx, 2, '.', '')); ?> ko/s</td>
                    <td><?php echo htmlspecialchars(number_format($temp_cpu, 2, '.', '')); ?> °C</td>
                    <td><?php echo htmlspecialchars(number_format($uptime, 2, '.', '')); ?> s</td>
                    <td><?php echo htmlspecialchars(number_format($latency, 2, '.', '')); ?> ms</td>
                    <td><?php echo htmlspecialchars(number_format($cpuUsage, 2, '.', '')); ?> %</td>
                </tr>
            </tbody>
        </table>
        <canvas id="myChart" width="400" height="400"></canvas>
    </div>
</main>
<script>
    // Recharger la page toutes les 5 secondes
    setInterval(function(){
        window.location.reload();
    }, 5000);
</script>
</body>
</html>
<?php
} else {
    echo "Aucun équipement trouvé pour l'ID spécifié.";
}
?>
