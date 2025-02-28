<?php
$servername = "localhost"; // Serveur MySQL (Laragon utilise "localhost" par défaut)
$username = "root"; // Nom d'utilisateur par défaut dans Laragon
$password = ""; // Mot de passe par défaut (vide)
$dbname = "reservations"; // Remplace par le bon nom de ta base de données

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
?>
