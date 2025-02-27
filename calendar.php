<?php
session_start();
include 'config/database.php';
<?php include 'includes/header.php'; ?>


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM appointments WHERE user_id = ? ORDER BY date, time");
$stmt->execute([$userId]);
$appointments = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_id'])) {
    $cancelId = $_POST['cancel_id'];
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ? AND user_id = ?");
    $stmt->execute([$cancelId, $userId]);
    header("Location: calendar.php?message=" . urlencode("Rendez-vous annulé !"));
    exit();
}
?>

<div class="container">
    <h2 class="text-center">Mes Rendez-vous</h2>
    <?php if (isset($_GET['message'])) echo "<div class='alert alert-success'>".$_GET['message']."</div>"; ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment) : ?>
                <tr>
                    <td><?= htmlspecialchars($appointment['date']) ?></td>
                    <td><?= htmlspecialchars($appointment['time']) ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="cancel_id" value="<?= $appointment['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Annuler</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
