<?php

namespace App\Controllers;

use App\models\Appointment;
use App\config\Database;

class AppointmentController {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function bookAppointment($user_id, $date, $time) {
        // Vérifier si le créneau est disponible
        $stmt = $this->db->prepare("SELECT * FROM appointments WHERE date = ? AND time = ?");
        $stmt->execute([$date, $time]);
        $existing = $stmt->fetch();

        if ($existing) {
            return false; // Créneau déjà pris
        }

        // Insérer le rendez-vous
        $stmt = $this->db->prepare("INSERT INTO appointments (user_id, date, time) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $date, $time]);
    }


    private $appointment;

    public function __construct() {
        $this->appointment = new Appointment();
    }

    // Prendre un rendez-vous
    public function bookAppointment() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $date = $_POST['date'];
            $time = $_POST['time'];
            $userId = $_SESSION['user_id'];

            if ($this->appointment->book($userId, $date, $time)) {
                $_SESSION['success'] = "Rendez-vous pris avec succès !";
            } else {
                $_SESSION['error'] = "Créneau déjà occupé, choisissez une autre date/heure.";
            }
            header("Location: appointment.php");
            exit;
        }
    }

    // Annuler un rendez-vous
    public function cancelAppointment() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id'])) {
            $appointmentId = $_POST['appointment_id'];
            $userId = $_SESSION['user_id'];

            if ($this->appointment->cancel($appointmentId, $userId)) {
                $_SESSION['success'] = "Rendez-vous annulé avec succès.";
            } else {
                $_SESSION['error'] = "Impossible d'annuler ce rendez-vous.";
            }
            header("Location: appointment.php");
            exit;
        }
    }
}
