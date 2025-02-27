<?php
// src/models/User.php

include_once 'config/database.php';

class User {

    public static function createUser($firstName, $lastName, $dateOfBirth, $address, $phone, $email, $password) {
        global $pdo;

        // Hacher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Préparer la requête d'insertion
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, date_of_birth, address, phone, email, password) 
                               VALUES (:first_name, :last_name, :date_of_birth, :address, :phone, :email, :password)");

        // Exécuter la requête avec les paramètres
        $stmt->execute([
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':date_of_birth' => $dateOfBirth,
            ':address' => $address,
            ':phone' => $phone,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);
    }

    public static function emailExists($email) {
        global $pdo;

        // Vérifier si l'email existe déjà dans la base de données
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        return $stmt->fetchColumn() > 0;
    }

    public static function getUserByEmail($email) {
        global $pdo;

        // Récupérer un utilisateur par son email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUserById($userId) {
        global $pdo;

        // Récupérer un utilisateur par son ID
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public static function updateUser($id, $firstName, $lastName, $dateOfBirth, $address, $phone, $email) {
        global $pdo;

        // Vérifier si l'email existe déjà (hors utilisateur actuel)
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
        $stmt->execute([
            ':email' => $email,
            ':id' => $id
        ]);
        if ($stmt->fetch()) {
            return "L'email est déjà utilisé par un autre utilisateur.";
        }

        // Mise à jour des informations de l'utilisateur
        $stmt = $pdo->prepare("UPDATE users 
                               SET first_name = :first_name, last_name = :last_name, date_of_birth = :date_of_birth,
                                   address = :address, phone = :phone, email = :email 
                               WHERE id = :id");

        $stmt->execute([
            ':id' => $id,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':date_of_birth' => $dateOfBirth,
            ':address' => $address,
            ':phone' => $phone,
            ':email' => $email
        ]);

        return "Mise à jour réussie !";
    }

    public static function deleteUser($id) {
        global $pdo;

        // Suppression des rendez-vous liés à l'utilisateur
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE user_id = :id");
        $stmt->execute([':id' => $id]);

        // Suppression de l'utilisateur
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return true;
    }
}
?>
