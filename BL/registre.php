<?php

session_start();

require_once('Usuari.php');
require_once('../helpers/validation.php');

function sendWelcomeEmail($email, $nom, $alies) {
    $to = $email;
    $subject = "Benvingut/da a NLNetwork!";

    $message  = "Hola $nom!\r\n\r\n";
    $message .= "El teu compte a NLNetwork s'ha creat correctament.\r\n";
    $message .= "El teu àlies és: @$alies\r\n\r\n";
    $message .= "Gràcies per unir-te a la nostra xarxa social!\r\n\r\n";
    $message .= "Atentament,\r\n";
    $message .= "L'equip de NLNetwork";

    $message = wordwrap($message, 70, "\r\n");

    $headers  = "From: NLNetwork <nlnetwork@alwaysdata.net>\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    return mail($to, $subject, $message, $headers, "-f nlnetwork@alwaysdata.net");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = sanitizeString($_POST['nom'] ?? '');
    $alies = sanitizeString($_POST['alies'] ?? '');
    $email = sanitizeEmail($_POST['email'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';
    $errors = [];

    if (empty($errors)) {
        $validationErrors = validateUserFields($nom, $alies, $email, $contrasenya);
        $errors = array_merge($errors, $validationErrors);
    }

    if (!empty($errors)) {
        $_SESSION['errorNumber'] = 1;
        $_SESSION['errorMsg'] = $errors;
        header("Location: ../error.php");
        exit;
    }

    $usuari = new Usuari();

    if ($usuari->existsEmail($email)) {
        $_SESSION["errorNumber"] = 2;
        $_SESSION["errorMsg"] = "Aquest correu electrònic ja està registrat.";
        header("Location: ../error.php");
        exit;
    }

    if ($usuari->existsAlias($alies)) {
        $_SESSION["errorNumber"] = 2;
        $_SESSION["errorMsg"] = "Aquest àlies ja està en ús.";
        header("Location: ../error.php");
        exit;
    }

    if ($usuari->addUser($nom, $alies, $email, $contrasenya)) {
        sendWelcomeEmail($email, $nom, $alies);
        $_SESSION["errorNumber"] = 0;
        header("Location: ../index.html");
        exit;
    } else {
        $_SESSION["errorNumber"] = 3;
        $_SESSION["errorMsg"] = "Error en l'alta a la base de dades.";
        header("Location: ../error.php");
        exit;
    }
} else {
    header("Location: ../index.html");
    exit;
}

?>