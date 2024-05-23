
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Supervision</title>
    <link rel="icon" href="logo.png">
    <link rel="stylesheet" href="./style/styleconnexion.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <div>
          <H1>Supervision Inter-Ville</H1>
            <nav class="navbar navbar-expand-lg bg-body-tertiary"data-bs-theme="dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"></a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                      <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.html">Accueil</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="presentation.html">Présentation</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="connexion.php">Accès Supervision</a>
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
          <li class="breadcrumb-item"><a href="index.html">Accueil</a></li>
          <li class="breadcrumb-item active" aria-current="page">Accès Supervision</li>
        </ol>
      </nav>
      <h2>Connexion Supervision</h2>
      <div class="d-flex justify-content-center align-items-center vh-40" >
      <form method="post">
  <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
    <label for="username" class="user">Nom d'utilisateur</label>
    <input type="text" class="form-control" id="username" name="username" aria-describedby="username" required>
  </div>
  <div class="shadow p-2 mb-4 bg-body-tertiary rounded">
    <label for="password" class="user">Mot de Passe</label>
    <input type="password" class="form-control" id="password" name="password">
  </div>
  <button name="submit" type="submit" class="btn btn-danger">Se connecter</button>
</div>
      </form>
      <?php

include("login.php");

$message = '';

if (isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = :username";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['username'] = $user['username'];
        $_SESSION['type_users'] = $user['type_users'];
        header('Location: logged.php');
        exit();
    } else {
        $message = 'Mauvais identifiants';
    }
}
?>

    </div>
    </main>
    <footer>
      <p>Projet Supervision Inter-Ville réaliser par Nicolas LEGAL et Cyril RESCUER |2022-2024|</p>
  </footer>
</body>
</html>
