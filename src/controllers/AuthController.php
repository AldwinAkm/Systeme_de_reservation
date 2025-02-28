<?php
session_start();
include 'config/database.php';
namespace App\Controllers;

class AuthController {
    public function login($email, $password) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: profile.php");
            exit();
        } else {
            return "Email ou mot de passe incorrect.";
        }
    }

    public function logout() {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
