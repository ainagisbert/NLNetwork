<?php
session_start();

$errorNumber = null;
$errorMessages = [];

if (isset($_SESSION['errorNumber']) && $_SESSION['errorNumber'] !== 0) {
    $errorNumber = $_SESSION['errorNumber'];
    $msg = $_SESSION['errorMsg'] ?? 'S\'ha produït un error desconegut.';
    unset($_SESSION['errorNumber'], $_SESSION['errorMsg']);

    if (is_array($msg)) {
        $errorMessages = $msg;
    } else {
        $errorMessages = [$msg];
    }
} else {
    $errorMessages = ['No hi ha cap error.'];
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error | NLNetwork</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./scss/reducido.css">
</head>
<body class="bg-white">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <img src="images/logo.png" alt="Logo NLN" height="30">
            </a>
        </div>
    </nav>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-6">
                <div class="card shadow-sm bg-dark text-white text-center">
                    <div class="card-body p-5">
                        <div class="text-warning">
                            <i class="bi bi-exclamation-triangle" style="font-size: 4rem;"></i>
                        </div>
                        <h1 class="h3 mb-3">Hi ha hagut un problema</h1>
                        <?php if ($errorNumber !== null): ?>
                            <p class="lead mb-1">Error número <strong><?= htmlspecialchars($errorNumber) ?></strong></p>
                        <?php endif; ?>

                        <?php foreach ($errorMessages as $msg): ?>
                            <p class="mb-2"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endforeach; ?>
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center mt-4">
                            <a class="btn btn-primary" href="home.php">
                                <i class="bi bi-house-door"></i> Torna a l'inici
                            </a>
                            <a class="btn btn-outline-light" href="index.html">
                                <i class="bi bi-door-open"></i> Inicia sessió
                            </a>
                        </div>
                    </div>
                </div>
                <p class="text-center text-muted mt-3 small">Si el problema persisteix, contacta amb l'equip de suport.</p>
            </div>
        </div>
    </main>
</body>
</html>