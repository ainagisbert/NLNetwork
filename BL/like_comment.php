<?php
session_start();
require_once('Usuari.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION["id_usuari"])) {
        header("Location: ../index.html");
        exit;
    }

    $id_comentari = (int)($_POST['id_comentari']);

    $usuari = new Usuari($_SESSION['id_usuari']);
    if ($usuari->toggleLikeComentari($id_comentari)) {
        $_SESSION["errorNumber"] = 0;
        $referrer = $_SERVER['HTTP_REFERER'] ?? '../dashboard.php';
        header("Location: $referrer");
        exit;
    } else {
        $_SESSION["errorNumber"] = 16;
        $_SESSION["errorMsg"] = "Error en donar like.";
        header("Location: ../error.php");
        exit;
    }
} else {
    header("Location: ../index.html");
    exit;
}
?>