<?php
session_start();

require_once('Usuari.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'deletePost') {
    
    if (!isset($_SESSION["id_usuari"])) {
        header("Location: ../index.html");
        exit;
    }

    $id_publicacio = $_POST['id_publicacio'];
    $usuari = new Usuari($_SESSION["id_usuari"]);

    if ($usuari->deletePost($id_publicacio)) {
        $_SESSION["errorNumber"] = 0;
        $referrer = $_SERVER['HTTP_REFERER'] ?? '../dashboard.php';
        header("Location: $referrer");
        exit;
    } else {
        $_SESSION["errorNumber"] = 13;
        $_SESSION["errorMsg"] = "No es pot esborrar la publicació.";
        header("Location: ../error.php");
        exit;
    }
} else {
    header("Location: ../index.html");
    exit;
}

?>