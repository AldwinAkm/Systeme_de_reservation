<?php
// src/models/Appointment.php

include_once 'config/database.php';

class Appointment {

    public static function createAppointment($userId, $date, $time) {
        global $pdo;

        // Préparer la requête d'insertion d'un rendez-vous
        $stmt = $pdo->prepare("INSERT INTO appointments (user_id, date, time) VALUES (:user_id, :date, :time)");
        $stmt->execute([
            ':user_id' => $userId,
            ':date' => $date,
            ':time' => $time
        ]);
    }

    public static function getAppointmentsByUserId($userId) {
        global $pdo;

        // Récupérer tous les rendez-vous d'un utilisateur
        $stmt = $pdo->prepare("SELECT * FROM appointments WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
