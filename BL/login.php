<!-- Codi per fer inici de sessió, 
 rep les dades de la capa de presentació, 
 i si són correctes (existeixen i coincideixen amb la BD), les guarda a la sessió -->
<?php

session_start();

require_once('Usuari.php');
require_once('../helpers/validation.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificador = sanitizeString($_POST['identificador'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';

    // Utilitzem la classe Usuari per comprovar si l'usuari existeix i si la contrasenya és correcta
    $usuari = new Usuari();

    if (!$usuari->existsEmail($identificador) && !$usuari->existsAlias($identificador)) {
        $_SESSION["errorNumber"] = 4;
        $_SESSION["errorMsg"] = "Error en l'inici de sessió. L'usuari no existeix";
        header("Location: ../error.php");
        exit;
    }

    if (!$usuari->isValidPassword($identificador, $contrasenya)) {
        $_SESSION["errorNumber"] = 5;
        $_SESSION["errorMsg"] = "Error en l'inici de sessió. La contrasenya no és correcta";
        header("Location: ../error.php");
        exit;
    }

    // Array amb totes les dades de l'usuari que inicia sessió
    $userData = $usuari->getUserById($identificador);
    
    if ($userData) {
        $_SESSION["user_nom"] = $userData['nom'];
        $_SESSION["user_alies"] = $userData['alies'];
        $_SESSION["user_email"] = $userData['email'];
        $_SESSION["avatar"] = $userData['url_imatge'];
        $_SESSION["user_descripcio"] = $userData['descripcio'];
        $_SESSION["likes_rebuts"] = $userData['likes_rebuts'];
        $_SESSION["publicacions"] = $userData['publicacions'];
        $_SESSION["logged_in"] = true;
    }

    $_SESSION["errorNumber"] = 0;
    header("Location: ../dashboard.php");
    exit;
} else {
    header("Location: ../index.html");
    exit;
}

?>