<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
    <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
        <a class="navbar-brand" href="index.php">
            <p class="d-inline-block align-text-top" style="">QuickResa</p>
         </a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="btn btn-success" href="login.php">🔑 Se Connecter</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary" href="register.php">📝 S'inscrire</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link dropdown-toggle" href="">🇫🇷</a>
                        </li>
                        </li>
                        <li class="nav-item">
                            <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
                            <?php endif; ?>
                        </li>
                </ul>
            </div>
        </div>
    </nav>
    </header>




