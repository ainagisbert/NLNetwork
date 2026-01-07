<?php
session_start();

require_once('Usuari.php');
require_once('../helpers/validation.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_SESSION["id_usuari"])) {
        header("Location: ../index.html");
        exit;
    }

    $nom = sanitizeString($_POST['nom'] ?? '');
    $alies = sanitizeString($_POST['alies'] ?? '');
    $descripcio = sanitizeString($_POST['descripcio'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';
    $errors = [];

    $usuari = new Usuari($_SESSION["id_usuari"]);
    $email = $usuari->getEmail();
    
    if (empty($errors)) {
        $validationErrors = validateUserFields($nom, $alies, $email, $contrasenya, true);
        $errors = array_merge($errors, $validationErrors);
    }

    if (!empty($errors)) {
        $_SESSION['errorNumber'] = 1;
        $_SESSION['errorMsg'] = $errors;
        header("Location: ../error.php");
        exit;
    }

    $imageResult = validateImage($_FILES['avatar'] ?? null, $usuari->getAvatar());
    if (!$imageResult['success']) {
        $_SESSION["errorNumber"] = 8;
        $_SESSION["errorMsg"] = $imageResult['error'];
        header("Location: ../error.php");
        exit;
    }
    $url_imatge = $imageResult['url'];

    if (!empty($contrasenya) && !validatePassword($contrasenya)) {
        $_SESSION["errorNumber"] = 1;
        $_SESSION["errorMsg"] = 'La contrasenya ha de tenir almenys 8 caràcters, incloure un número i un caràcter especial.';
        header("Location: ../error.php");
        exit;
    }

    if ($alies !== $usuari->getAlies() && Usuari::existsAlias($alies)) {
        $_SESSION["errorNumber"] = 2;
        $_SESSION["errorMsg"] = "Aquest àlies ja està en ús.";
        header("Location: ../error.php");
        exit;
    }

    if ($usuari->updateProfile($nom, $alies, $url_imatge, $descripcio)) {

        if (!empty($contrasenya)) {
            $usuari->updatePassword($contrasenya);
        }
        
        header("Location: ../dashboard.php?updated=true");
        exit;

    } else {
        $_SESSION["errorNumber"] = 7;
        $_SESSION["errorMsg"] = "Error en actualitzar les dades del perfil.";
        header("Location: ../error.php");
        exit;
    }

} else {
    header("Location: ../index.html");
    exit;
}
?>