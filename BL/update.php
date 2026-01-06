<?php

session_start();

require_once('Usuari.php');
require_once('../helpers/validation.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_SESSION['user_email'])) {
        header("Location: ../index.html");
        exit;
    }

    $email = $_SESSION['user_email'];
    $nom = sanitizeString($_POST['nom'] ?? '');
    $alies = sanitizeString($_POST['alies'] ?? '');
    $descripcio = sanitizeString($_POST['descripcio'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';
    $errors = [];
    
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

    $imageResult = validateImage($_FILES['avatar'] ?? null, $_SESSION['avatar']);

    if (!$imageResult['success']) {
        $_SESSION["errorNumber"] = 8;
        $_SESSION["errorMsg"] = $imageResult['error'];
        header("Location: ../error.php");
        exit;
    }

    $url_imatge = $imageResult['url'];

    if (!empty($contrasenya)) {
        if (!validatePassword($contrasenya)) {
            $_SESSION["errorNumber"] = 1;
            $_SESSION["errorMsg"] = 'La contrasenya ha de tenir almenys 8 caràcters, incloure un número i un caràcter especial.';
            header("Location: ../error.php");
            exit;
        }
    }

    $usuari = new Usuari();

    if ($alies !== $_SESSION['user_alies']) {
        if ($usuari->existsAlias($alies)) {
            $_SESSION["errorNumber"] = 2;
            $_SESSION["errorMsg"] = "Aquest àlies ja està en ús.";
            header("Location: ../error.php");
            exit;
        }
    }

    if ($usuari->updateProfile($email, $nom, $alies, $url_imatge, $descripcio)) {
        $_SESSION["user_nom"] = $nom;
        $_SESSION["user_alies"] = $alies;
        $_SESSION["avatar"] = $url_imatge;
        $_SESSION["user_descripcio"] = $descripcio;

        if (!empty($contrasenya)) {
            $usuari->updatePassword($email, $contrasenya);
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