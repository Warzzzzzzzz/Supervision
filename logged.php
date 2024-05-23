<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header('Location: connexion.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervision Inter-ville</title>
    <link rel="icon" href="logo.png">
    <link rel="stylesheet" href="./style/stylelogged.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                        <a class="nav-link" href="listedesequipement.php">Listes des équipements</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="latence.php">Latence</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="logs.php">Logs</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="gestionutilisateurs.php">Gestion Utilisateurs</a>
                      </li>
                       </ul>
                  </div>
                </div>
              </nav>
        </div>
    </header>
    <main>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="logged.php">Accueil Connexion</a></li>
        </ol>
      </nav>
      <div class="d-inline-block">
    <form class="form-dashboard" method="post" action="dashboard.php">
        <button type="submit" class="btn btn-primary">Dashboard</button>
    </form>
</div>
<div class="d-inline-block">
    <form class="form-listedesequipements" method="post" action="listedesequipement.php">
        <button type="submit" class="btn btn-primary">Liste des équipements</button>
    </form>
</div>
<div class="d-inline-block">
    <form class="form-latence" method="post" action="latence.php">
        <button type="submit" class="btn btn-primary">Latence</button>
    </form>
</div>
<div class="d-inline-block">
    <form class="form-logs" method="post" action="logs.php">
        <button type="submit" class="btn btn-primary">Logs</button>
    </form>
</div>
<div class="d-inline-block">
    <form class="form-gestionutilisateurs" method="post" action="gestionutilisateurs.php">
        <button type="submit" class="btn btn-primary">Gestion utilisateurs</button>
    </form>
</div>
    </main>
    <footer>
        <p>Projet Supervision Inter-Ville réaliser par Nicolas LEGAL et Cyril RESCUER |2022-2024|</p>
    </footer>
</body>
</html>