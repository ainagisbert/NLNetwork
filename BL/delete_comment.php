<?php
session_start();

require_once('Usuari.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'deleteComment') {
    
    if (!isset($_SESSION["id_usuari"])) {
        header("Location: ../index.html");
        exit;
    }

    $id_comentari = (int)($_POST['id_comentari'] ?? 0);
    $usuari = new Usuari($_SESSION["id_usuari"]);

    if ($usuari->deleteComment($id_comentari)) {
        $_SESSION["errorNumber"] = 0;
        header("Location: ../home.php");
        exit;
    } else {
        $_SESSION["errorNumber"] = 15;
        $_SESSION["errorMsg"] = "No es pot esborrar el comentari.";
        header("Location: ../error.php");
        exit;
    }
} else {
    header("Location: ../index.html");
    exit;
}

?>