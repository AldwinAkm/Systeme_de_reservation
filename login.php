<?php
session_start();
include_once 'config/database.php';
use App\Controllers\AuthController;

require_once __DIR__ . '/vendor/autoload.php';


if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}

//$auth = new AuthController();

/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $error = $auth->login($email, $password);
}*/


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: Appointment.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<?php include 'includes/header.php'; ?>


<div class="container">
    <h2 class="text-center">Connexion</h2>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    </form>
    <p class="text-center mt-3">Pas encore inscrit ? <a href="register.php">Inscription</a></p>
</div>

<?php include 'includes/footer.php'; ?>



