<?php
// Vérification si le formulaire a été soumis
if(isset($_POST['submit'])){
    
    // Inclusion du fichier de configuration de la base de données
    include_once 'login.php';
    
    // Récupération des données du formulaire
    $username = $_POST['username'];
    $TYPE_USERS = $_POST['TYPE_USERS'];
    $password = $_POST['password']; // Attention : Le mot de passe doit être sécurisé (hachage, etc.)

    // Requête SQL pour insérer l'utilisateur dans la base de données
    $sql = "INSERT INTO users (username, TYPE_USERS, password) VALUES ('$username', '$TYPE_USERS', '$password')";
    
    // Exécution de la requête
    if(mysqli_query($conn, $sql)){
        // Utilisateur ajouté avec succès
        echo "Utilisateur ajouté avec succès.";
    } else{
        // Erreur lors de l'ajout de l'utilisateur
        echo "Erreur : " . mysqli_error($conn);
    }
    
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
}
?>