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
    $id_publicacio = (int)($_POST['idPost'] ?? 0);

    $usuari = new Usuari($_SESSION["id_usuari"]);
    if ($usuari->addComment($id_publicacio, $contingut)) {
        header("Location: ../home.php");
        exit;
    } else {
        $_SESSION["errorNumber"] = 14;
        $_SESSION["errorMsg"] = "Error en publicar el comentari.";
        header("Location: ../error.php");
        exit;
    }

} else {
    header("Location: ../index.html");
    exit;
}

?>