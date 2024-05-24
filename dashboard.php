<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervision Inter-ville</title>
    <link rel="icon" href="logo.png">
    <link rel="stylesheet" href="./style/styledashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <div>
          <H1>Supervision Inter-Ville</H1> 
          <form class="form-deconnexion" method="post" action="logout.php">
            <button type="submit" class="btn btn-danger">Se Déconnecter</button>
        </form>
        <nav class="navbar navbar-expand-lg  bg-body-tertiary"data-bs-theme="dark">
        <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
                  <span class="badge text-bg-danger">ADMIN</span>
                  <ul class="navbar-nav">
                      <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="dashboard.php">DashBoard</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="latence.php">Latence</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="logs.php">Logs</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="listedesequipement.php">Listes des équipements</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="gestionmairies.php">Gestion des Mairies</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="gestionutilisateurs.php">Gestion Utilisateurs</a>
                      </li>
                       </ul>
                  </div>
                </div>
              </nav>
              <?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit;
}
?>
        </div>
    </header>
    <main>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="logged.php">Accueil Connexion</a></li>
          <li class="breadcrumb-item active" aria-current="page">DashBoard</li>
        </ol>
      </nav>
      <div style="width: 75%; margin: auto;">
        <canvas id="chargeChart"></canvas>
    </div>
    <script>
        // Fonction pour récupérer les données depuis data.php
        async function fetchData() {
            const response = await fetch('data.php');
            const data = await response.json();
            return data;
        }

        // Fonction pour créer le graphique
        async function createChart() {
            const data = await fetchData();
            
            const labels = data.map(row => row.NAME_EQUIPEMENT);
            const tauxDeCharge = data.map(row => row.taux_de_charge);

            const chartData = {
                labels: labels,
                datasets: [{
                    label: 'Taux de Charge',
                    backgroundColor: 'rgba(75, 192, 192, 0.8)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    data: tauxDeCharge,
                }]
            };

            const config = {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Équipement'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Taux de Charge'
                            }
                        }
                    }
                }
            };

            const chargeChart = new Chart(
                document.getElementById('chargeChart'),
                config
            );
        }

        // Créer le graphique lors du chargement de la page
        window.onload = createChart;
    </script>
    </main>
    <footer>
        <p>Projet Supervision Inter-Ville réaliser par Nicolas LEGAL et Cyril RESCUER |2022-2024|</p>
    </footer>
</body>
</html>