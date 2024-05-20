<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contrasenia = $_POST['contrasenia'];
    $token = null;
    echo $nombre . $email .  $contrasenia . $token;

    $contrasenia_hasheada = password_hash($contrasenia, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, email, contraseña, token, activo) VALUES (?, ?, ?, ?, 1)');
    $stmt->execute([$nombre, $email, $contrasenia_hasheada, $token]);
}