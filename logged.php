<?php
session_start();


if (!isset($_SESSION['username'])) {
    header('Location: connexion.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervision Inter-ville</title>
    <link rel="icon" href="logo.png">
    <link rel="stylesheet" href="./style/stylelogged.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
          
            alert("Connexion réussie !");
        });
    </script>  
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
                                <a class="nav-link" href="gestionmairies.php">Gestion des Mairies</a>
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
    </main>
    <footer>
        <p>Projet Supervision Inter-Ville réaliser par Nicolas LEGAL et Cyril RESCUER |2022-2024|</p>
    </footer>
</body>
</html>