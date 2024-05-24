<?php
include("login.php"); // Assurez-vous que ce fichier contient la connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_users'])) {
        $id_users = $_POST['id_users'];

        // Préparation et exécution de la requête de suppression
        $sql = "DELETE FROM users WHERE id_users = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_users);

            if ($stmt->execute()) {
                header("Location: gestionutilisateurs.php?message=Utilisateur supprimé avec succès");
                exit();
            } else {
                echo "Erreur lors de la suppression de l'utilisateur: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Erreur de préparation de la requête: " . $conn->error;
        }
    }
}
$conn->close();
?>
