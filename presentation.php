<?php
include("session_check.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Présentation du Projet</title>
    <link rel="icon" href="./img/logo.png">
    <link rel="stylesheet" href="./style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .presentation {
            max-width: 100%;
            max-height: 100%;
        }

        .navbar-nav {
            flex-grow: 1;
        }
        .form-deconnexion {
          margin-left: 20px; 
        }    
        </style>
</head>
<body>
    <header>
        <div>
            <nav class="navbar navbar-expand-lg  bg-body-tertiary"data-bs-theme="dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"></a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                      <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="connexion.php">Accueil</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="presentation.php">Présentation</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">DashBoard</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="gestionmairies.php">Gestion Mairies</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="gestionutilisateurs.php">Gestion utilisateurs</a>
                      </li>
                    </ul>
                    <form class="form-account" method="post" action="account.php">
                            <button type="submit" class="btn btn-light">
                                <?php
                                include("login.php");
                                if (isset($_SESSION['nom_users']) && isset($_SESSION['prenom_user'])) {
                                    echo "" . htmlspecialchars($_SESSION['prenom_user']) . " " . htmlspecialchars($_SESSION['nom_users']);
                                }
                                ?>
                            </button>
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
      <img src="./img/presentation.png" class="presentation">
    </main>
    <footer>
        <p>Projet Supervision Inter-Ville réalisé par Nicolas LEGAL et Cyril MAGUIRE |2022-2024|</p>
    </footer>
</body>
</html>
