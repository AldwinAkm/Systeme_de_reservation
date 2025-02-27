<?php
session_start();
include_once 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $dob = $_POST['date_naissance'];
    $adresse = htmlspecialchars($_POST['adresse']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!$email) {
        $error = "Email invalide.";
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email déjà utilisé.";
        } else {
            // Insérer l'utilisateur
            $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, date_naissance, adresse, telephone, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$nom, $prenom, $dob, $adresse, $telephone, $email, $password])) {
                header("Location: login.php?message=" . urlencode("Compte créé, connectez-vous !"));
                exit();
            } else {
                $error = "Erreur lors de l'inscription.";
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h2 class="text-center">Inscription</h2>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    
    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" name="prenom" required>
        </div>
        <div class="mb-3">
            <label for="date_naissance" class="form-label">Date de naissance</label>
            <input type="date" class="form-control" name="date_naissance" required>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse postale</label>
            <input type="text" class="form-control" name="adresse" required>
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="tel" class="form-control" name="telephone" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
