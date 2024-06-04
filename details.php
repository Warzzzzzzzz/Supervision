<?php
include("session_check.php");
require('accessDB.php');

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

    $url = 'http://192.168.200.10/api_jsonrpc.php'; // URL de l'API Zabbix
    $authToken = '8efbc7b02e8381f751273e8909d605a5e31d0032161d4b051f9aa17c8055a1ea'; // Token d'authentification

    // Étape 1 : Récupérer tous les éléments
    $itemParams = [
        'output' => ['hostid', 'lastvalue', 'name', 'lastclock', 'hostid','status'],
        'selectHosts' => ['host']
    ];

    $itemResponse = zabbixApiRequest($url, $authToken, 'item.get', $itemParams);
    if (isset($itemResponse['error'])) {
        echo 'Erreur dans la requête item.get : ' . htmlspecialchars(json_encode($itemResponse['error'])) . '<br>';
        exit;
    }
    $items = $itemResponse['result'];

    // Récupérer le statut des hôtes
    $hostParams = [
        'output' => ['hostid', 'name', 'status']
    ];

    $hostResponse = zabbixApiRequest($url, $authToken, 'host.get', $hostParams);
    if (isset($hostResponse['error'])) {
        echo 'Erreur dans la requête host.get : ' . htmlspecialchars(json_encode($hostResponse['error'])) . '<br>';
        exit;
    }
    $hosts = $hostResponse['result'];

    // Créer un dictionnaire pour mapper hostid à status
    $hostStatusMapping = [];
    foreach ($hosts as $host) {
        $hostStatusMapping[$host['hostid']] = $host['status'] == 0 ? 'Online' : 'Offline';
    }

    // Filtrer les éléments pour les différents métriques
    $filteredItemsRX = [];
    $filteredItemsTX = [];
    $filteredItemsTempCPU = [];
    $filteredItemsUptime = [];
    $filteredItemsLatency = [];
    $filteredItemsCpuUsage = [];
    
    $keywordsRX = "Bits received";
    $keywordsTX = "Bits sent";
    $keywordsTempCPU = "CPU temperature";
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

    // Récupérer les interfaces des hôtes pour obtenir les adresses IP
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

        // Ajouter le statut
        $status = $hostStatusMapping[$hostId] ?? 'Unknown';

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
                    utilisation_cpu = '$cpuUsage',
                    status = '$status' 
                    WHERE ID_EQUIPEMENTS = '$hostId'";
        } else {
            $sql = "INSERT INTO equipements 
                    (ID_EQUIPEMENTS, NAME_EQUIPEMENT, debit_rx, debit_tx, address_ip, temp_cpu, temps_uptime, latence, utilisation_cpu, status)
                    VALUES ('$hostId', '$hostName', '$kbpsRX', '$kbpsTX', '$ipAddress', '$tempCPU', '$uptime', '$latency', '$cpuUsage', '$status')";
        }

        if ($conn->query($sql) === TRUE) {
            echo "";
        } else {
            echo "Erreur lors de l'insertion des données : " . $conn->error;
        }
    }
    
    $sql = $conn->prepare("
    SELECT e.NAME_EQUIPEMENT, e.debit_rx, e.debit_tx, e.address_ip, e.temp_cpu, e.temps_uptime, e.latence, e.utilisation_cpu, e.status,
           M.NOM_MAIRIE, S.LIBELLE_SERVICES, SA.LIBELLE_SALLE
    FROM equipements e
    LEFT JOIN services S ON e.ID_EQUIPEMENTS = S.ID_EQUIPEMENT
    LEFT JOIN salles SA ON S.ID_SALLES = SA.ID_SALLES
    LEFT JOIN mairie M ON S.ID_MAIRIE = M.ID_MAIRIE
    WHERE e.ID_EQUIPEMENTS = ?
");

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
    $status = $row['status'];
    $nom_mairie = $row['NOM_MAIRIE'];
    $libelle_service = $row['LIBELLE_SERVICES'];
    $libelle_salle = $row['LIBELLE_SALLE'];
} else {
    echo "Aucun équipement trouvé pour l'ID spécifié.";
    exit;
}}
$conn->close();
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
                <a class="navbar-brand" href="#"><?php echo htmlspecialchars($nom_equipement ?? ''); ?></a>
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
                    <th>Status Equipement</th>
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
                    <td><?php echo htmlspecialchars($nom_equipement ?? ''); ?></td>
                    <td>
                    <form id="pingForm">
                        <button type="button" class="btn btn-warning" onclick="ping()">Ping</button>
                    </form>
                    </td>
                    <td><?php echo htmlspecialchars($ipAddress ?? ''); ?></td>
                    <td><?php echo htmlspecialchars(number_format($debit_rx ?? 0, 2, '.', '')); ?> ko/s</td>
                    <td><?php echo htmlspecialchars(number_format($debit_tx ?? 0, 2, '.', '')); ?> ko/s</td>
                    <td><?php echo htmlspecialchars(number_format($temp_cpu ?? 0, 2, '.', '')); ?> °C</td>
                    <td><?php echo htmlspecialchars(number_format($uptime ?? 0, 2, '.', '')); ?> s</td>
                    <td><?php echo htmlspecialchars(number_format($latency ?? 0, 2, '.', '')); ?> ms</td>
                    <td><?php echo htmlspecialchars(number_format($cpuUsage ?? 0, 2, '.', '')); ?> %</td>
                </tr>
            </tbody>
        </table>
    <div class="container">
      <table class="table table-dark table-hover">
        <tr>
        <thead>
                <tr>
                    <th>Nom Mairie</th>
                    <th>Nom Service</th>
                    <th>Nom de la Salle</th>
                </tr>
            </thead>
            <tbody>
          <tr>
          <td><?php echo htmlspecialchars($nom_mairie ?? ''); ?></td>
          <td><?php echo htmlspecialchars($libelle_service ?? ''); ?></td>
          <td><?php echo htmlspecialchars($libelle_salle ?? ''); ?></td>
        </tr>
        </tbody>
        </table>
      </div>
  </main>
  <?php include("ping.php"); ?>
    <script>
        function ping() {
            var ipAddress = "<?php echo htmlspecialchars($ipAddress ?? ''); ?>";
            $.ajax({
                type: "GET",
                url: "ping.php?ip=" + ipAddress,
                success: function(response) {
                    alert(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
</main>
<script>
    // Recharger la page toutes les 20 secondes
    setInterval(function(){
        window.location.reload();
    }, 20000);
</script>
<?php require('footer.php');?>
</body>
</html>