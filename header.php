<header>
    <div>
        <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">Accueil</a>
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
                        <?php
                        if($_SESSION['type_users'] != 'T'){
                        echo('
                        <li>
                        <a class="nav-link" href="gestionutilisateurs.php">Gestion utilisateurs</a>
                        </li>');
                        } ?>
                        </ul>
                    <form class="form-account" method="post" action="account.php">
                        <button type="submit" class="btn btn-light">
                            <?php
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