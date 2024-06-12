<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'includes/config.php';
session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE token = ? AND activo = 0');
    $stmt->execute([$token]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $stmt = $pdo->prepare('UPDATE usuarios SET activo = 1, token = NULL WHERE id = ?');
        $stmt->execute([$usuario['id']]);

        $_SESSION['mensaje_sesion'] = "Tu cuenta se ha activado con éxito";
        $_SESSION['tipo_mensaje'] = "ok";

        header('Location: login');
        exit();

    } else {
        $_SESSION['mensaje_sesion'] = "Token inválido o cuenta ya registrada";
        $_SESSION['tipo_mensaje'] = "error";

        header('Location: login');
        exit();
    }
} else {
    $_SESSION['mensaje_sesion'] = "No se ha proporcionado ningún token";
        $_SESSION['tipo_mensaje'] = "error";

        header('Location: login');
        exit();
}
?>
