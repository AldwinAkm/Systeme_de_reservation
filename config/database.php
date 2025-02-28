<?php
// config/database.php

$host = 'localhost';  // ou l'adresse de votre serveur MySQL
$db = 'reservations'; // Nom de la base de données (vous pouvez le changer)
$user = 'root';       // Nom d'utilisateur MySQL
$pass = '';           // Mot de passe MySQL


try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
