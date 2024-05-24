<?php
include("login.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['ID_MAIRIE'])) {
        $ID_MAIRIE = $_POST['ID_MAIRIE'];

      
        $sql = "DELETE FROM mairie WHERE ID_MAIRIE = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $ID_MAIRIE);

            if ($stmt->execute()) {
                header("Location: gestionmairies.php?message=Mairie Supprimé avec succès");
                exit();
            } else {
                echo "Erreur lors de la suppression de la mairie: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Erreur de préparation de la requête: " . $conn->error;
        }
    }
}



$conn->close();
?>