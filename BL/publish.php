<?php
session_start();

require_once('Usuari.php');
require_once('../helpers/validation.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_SESSION["id_usuari"])) {
        header("Location: ../index.html");
        exit;
    }

    $contingut = sanitizeString($_POST['textContent'] ?? '');
    $id_categoria = ($_POST['textCategory'] ?? 0);

    $usuari = new Usuari($_SESSION["id_usuari"]);

    $imageResult = validateImageForPost($_FILES['textImage'] ?? null);
    if (!$imageResult['success']) {
        $_SESSION["errorNumber"] = 8;
        $_SESSION["errorMsg"] = $imageResult['error'];
        header("Location: ../error.php");
        exit;
    }
    $url_imatge = $imageResult['url'];

    if ($usuari->addPost($id_categoria, $contingut, $url_imatge)) {
        header("Location: ../dashboard.php");
        exit;
    } else {
        $_SESSION["errorNumber"] = 12;
        $_SESSION["errorMsg"] = "Error en publicar el text.";
        header("Location: ../error.php");
        exit;
    }

} else {
    header("Location: ../index.html");
    exit;
}

?>