<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervision Inter-ville</title>
    <link rel="icon" href="logo.png">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <div>
          <H1>Supervision Inter-Ville</H1> 
          <span class="badge text-bg-danger">ADMIN</span>
            <nav class="navbar navbar-expand-lg  bg-body-tertiary"data-bs-theme="dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"></a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                      <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">DashBoard</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="listedesequipement.php">Listes des équipements</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="latence.php">Latence</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="logs.php">Logs</a>
                      </li>
                      <form method="post" action="logout.php">
            <button type="submit" class="btn btn-danger">Se Déconnecter</button>
        </form>
                    </ul>
                  </div>
                </div>
              </nav>
              <?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit;
}
?>
        </div>
    </header>
    <main>
    </main>
    <footer>
        <p>Projet Supervision Inter-Ville réaliser par Nicolas LEGAL et Cyril RESCUER |2022-2024|</p>
    </footer>
</body>
</html>