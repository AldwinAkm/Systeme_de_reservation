<?php
// src/controllers/AppointmentController.php

include_once 'models/Appointment.php';

class AppointmentController {

    public function book() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $date = $_POST['date'];
            $time = $_POST['time'];
            $userId = $_SESSION['user_id'];

            // Ajouter un rendez-vous à la base de données
            Appointment::createAppointment($userId, $date, $time);
            header("Location: ../public/calendar.php?success=appointment_booked");
        }
    }

    public function getAppointments() {
        $userId = $_SESSION['user_id'];
        return Appointment::getAppointmentsByUserId($userId);
    }

    // Afficher les rendez-vous
    public function listAppointments() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../public/login.php");
            exit();
        }

        $userId = $_SESSION['user_id'];
        return Appointment::getUserAppointments($userId);
    }

    // Annuler un rendez-vous
    public function cancelAppointment() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'])) {
            $userId = $_SESSION['user_id'];
            $appointmentId = $_POST['appointment_id'];

            if (Appointment::cancelAppointment($appointmentId, $userId)) {
                header("Location: ../public/calendar.php?message=" . urlencode("Rendez-vous annulé avec succès."));
            } else {
                header("Location: ../public/calendar.php?message=" . urlencode("Erreur lors de l'annulation."));
            }
            exit();
        }
    }
}
?>
