<?php
// src/controllers/UserController.php

include_once 'models/User.php';

class UserController {

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Récupérer les données du formulaire et valider
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $dateOfBirth = $_POST['date_of_birth'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Vérifications de l'email et mot de passe
            if (User::emailExists($email)) {
                header("Location: ../public/register.php?error=email_taken");
                exit();
            }

            // Créer l'utilisateur
            User::createUser($firstName, $lastName, $dateOfBirth, $address, $phone, $email, $password);
            header("Location: ../public/login.php");
            exit();
        }
    }

    public function updateProfile() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id']; // Récupération de l'ID de l'utilisateur connecté
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $dateOfBirth = $_POST['date_of_birth'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];

            // Mettre à jour les informations
            $message = User::updateUser($userId, $firstName, $lastName, $dateOfBirth, $address, $phone, $email);

            header("Location: ../public/profile.php?message=" . urlencode($message));
            exit();
        }
    }


    public function deleteAccount() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];

            // Suppression de l'utilisateur
            $deleted = User::deleteUser($userId);

            if ($deleted) {
                // Déconnexion et suppression de la session
                session_destroy();
                header("Location: ../public/index.php?message=" . urlencode("Compte supprimé avec succès."));
                exit();
            } else {
                header("Location: ../public/profile.php?message=" . urlencode("Erreur lors de la suppression."));
                exit();
            }
        }
    }
}
?>
