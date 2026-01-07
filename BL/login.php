<?php
session_start();

require_once('Usuari.php');
require_once('../helpers/validation.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificador = sanitizeString($_POST['identificador'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';

    if (!Usuari::existsEmail($identificador) && !Usuari::existsAlias($identificador)) {
        $_SESSION["errorNumber"] = 4;
        $_SESSION["errorMsg"] = "Error en l'inici de sessió. L'usuari no existeix";
        header("Location: ../error.php");
        exit;
    }

    if (!Usuari::isValidPassword($identificador, $contrasenya)) {
        $_SESSION["errorNumber"] = 5;
        $_SESSION["errorMsg"] = "Error en l'inici de sessió. La contrasenya no és correcta";
        header("Location: ../error.php");
        exit;
    }

    $usuari = new Usuari($identificador);
    $_SESSION["id_usuari"] = $usuari->getId();
    $_SESSION["logged_in"] = true;

    $_SESSION["errorNumber"] = 0;
    header("Location: ../dashboard.php");
    exit;
} else {
    header("Location: ../index.html");
    exit;
}

?>