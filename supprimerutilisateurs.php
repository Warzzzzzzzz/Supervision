<?php
include("session_check.php");
require('accessDB.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_users'])) {
        
        $sql_count_users = "SELECT COUNT(*) AS total_users FROM users";
        $result_count = $conn->query($sql_count_users);
        $total_users = $result_count->fetch_assoc()['total_users'];

        if ($total_users > 1) {
            $id_users = $_POST['id_users'];

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
        } else {
            echo "Impossible de supprimer l'utilisateur. Au moins un utilisateur doit être présent.";
        }
    }
}
$conn->close();
?>