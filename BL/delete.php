<!-- Esborrar usuari i redirigim a la plana de login destruint la sessió -->
<?php
session_start();

require_once('Usuari.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'deleteUser') {
    
    if (!isset($_SESSION["id_usuari"])) {
        header("Location: ../index.html");
        exit;
    }

    $usuari = new Usuari($_SESSION["id_usuari"]);

    if ($usuari->deleteUser()) {
        session_unset();
        session_destroy();
        header("Location: ../index.html");
        exit;
    } else {
        $_SESSION["errorNumber"] = 6;
        $_SESSION["errorMsg"] = "Error en eliminar l'usuari.";
        header("Location: ../error.php");
        exit;
    }
} else {
    header("Location: ../index.html");
    exit;
}

?>