<?php
session_start();
include_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Mise à jour du profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];

    $stmt = $pdo->prepare("UPDATE users SET nom = ?, prenom = ?, adresse = ?, telephone = ? WHERE id = ?");
    $stmt->execute([$nom, $prenom, $adresse, $telephone, $userId]);

    header("Location: profile.php?message=" . urlencode("Profil mis à jour."));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
</head>
<body>
    <h2>Mon Profil</h2>
    <?php if (isset($_GET['message'])) echo "<p style='color:green;'>" . htmlspecialchars($_GET['message']) . "</p>"; ?>
    <form method="POST">
        <input type="text" name="nom" value="<?php echo $user['nom']; ?>" required>
        <input type="text" name="prenom" value="<?php echo $user['prenom']; ?>" required>
        <input type="text" name="adresse" value="<?php echo $user['adresse']; ?>" required>
        <input type="tel" name="telephone" value="<?php echo $user['telephone']; ?>" required>
        <button type="submit">Mettre à jour</button>
    </form>

    <form action="src/controllers/UserController.php?action=deleteAccount" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
        <button type="submit" class="delete-btn">Supprimer mon compte</button>
    </form>
    <?php include 'includes/footer.php'; ?>

