<?php
include("session_check.php");
require('accessDB.php');
$alarm_query = "SELECT ID_EQUIPEMENTS, NAME_EQUIPEMENT, 'Température CPU > 20°C' as cause FROM equipements WHERE temp_cpu > 20";
$alarm_result = $conn->query($alarm_query);
$alarm_count = $alarm_result->num_rows;
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
        .btn-alarm {
            animation: blink 1s step-start infinite;
        }
        @keyframes blink {
            50% {
                background-color: #dc3545;
            }
        }
        </style>
</head>
<body>
  <?php require('header.php');?>
  <main>
    <img src="./img/2.png" class="presentation">
  </main>
  <script>
  // Recharger la page toutes les 5 secondes
  setInterval(function(){
      window.location.reload();
  }, 5000);
</script>
<?php require('footer.php');?>
</body>
</html>
