<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervision Inter-ville</title>
    <link rel="icon" href="logo.png">
    <link rel="stylesheet" href="./style/stylegestionutilisateurs.css">
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
                      <li class="nav-item">
                        <a class="nav-link" href="gestionutilisateurs.php">Gestion utilisateurs</a>
                      </li>
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
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="logged.php">Accueil Connexion</a></li>
          <li class="breadcrumb-item active" aria-current="page">Gestion utilisateurs</li>
        </ol>
      </nav>
      <form method="post" action="adduser.php">
  <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
    <label for="username" class="user">Nom</label>
    <input type="text" class="form-control" id="username" name="username" aria-describedby="username" required>
  </div>
  <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
    <label for="username" class="user">Prénom</label>
    <input type="text" class="form-control" id="username" name="username" aria-describedby="username" required>
  </div>
  <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
        <label for="role">Rôle :</label>
        <select id="role" name="role" required>
            <option value="Administrateur">Administrateur</option>
            <option value="Technicien">Technicien</option>
        </select>

  </div>
  <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
    <label for="password" class="user">Créer un mot de Passe</label>
    <input type="password" class="form-control" id="password" name="password" required>
  </div>
  <button name="submit" type="submit" class="btn btn-danger">Créer un compte</button>
</div>
      </form>
    </main>
    <footer>
        <p>Projet Supervision Inter-Ville réaliser par Nicolas LEGAL et Cyril RESCUER |2022-2024|</p>
    </footer>
</body>
</html>