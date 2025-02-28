<?php
session_start();
require 'config/db_connection.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fonction pour récupérer les rendez-vous de l'utilisateur
function getAppointments($conn, $user_id) {
    $stmt = $conn->prepare("SELECT id, date, time FROM appointments WHERE user_id = ? ORDER BY date, time");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Prendre un rendez-vous
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'], $_POST['time'])) {
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Vérifier la disponibilité
    $stmt = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE date = ? AND time = ?");
    $stmt->bind_param('ss', $date, $time);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count == 0) {
        // Enregistrer le rendez-vous
        $stmt = $conn->prepare("INSERT INTO appointments (user_id, date, time) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $user_id, $date, $time);
        $stmt->execute();
        $stmt->close();
        $message = "Rendez-vous pris avec succès !";
    } else {
        $message = "Ce créneau horaire n'est pas disponible.";
    }
}

// Annuler un rendez-vous
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_appointment_id'])) {
    $appointment_id = $_POST['cancel_appointment_id'];

    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $appointment_id, $user_id);
    $stmt->execute();
    $stmt->close();

    $message = "Rendez-vous annulé avec succès.";
}

// Récupérer les rendez-vous de l'utilisateur
$appointments = getAppointments($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prise de rendez-vous</title>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1 class="mb-4">Prise de rendez-vous</h1>

    <?php if (isset($message)): ?>
        <div class="alert alert-info" role="alert">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire de prise de rendez-vous -->
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="date" class="form-label">Date :</label>
            <input type="text" id="date" name="date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="time" class="form-label">Heure :</label>
            <input type="time" id="time" name="time" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Prendre rendez-vous</button>
    </form>

    <!-- Liste des rendez-vous -->
    <div class="appointments">
        <h2 class="mb-3">Vos rendez-vous :</h2>
        <?php if (count($appointments) > 0): ?>
            <ul class="list-group">
                <?php foreach ($appointments as $appointment): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Date : <?= htmlspecialchars($appointment['date']) ?> | Heure : <?= htmlspecialchars($appointment['time']) ?></span>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="cancel_appointment_id" value="<?= $appointment['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Annuler</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-muted">Vous n'avez aucun rendez-vous.</p>
        <?php endif; ?>
    </div>

    <script>
        // Initialisation de Flatpickr pour le champ de date
        flatpickr("#date", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            minDate: "today",
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
