<!-- Codi per esborrar permanentment un usuari de la base de dades quan li dona al botó d'eliminar -->
<?php

session_start();

require_once('Usuari.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
    
    $email = $_SESSION['user_email'];
    $usuari = new Usuari();

    if ($usuari->deleteUser($email)) {
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