<?php
require 'includes/config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE token = ? AND activo = 0');
    $stmt->execute([$token]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $stmt = $pdo->prepare('UPDATE usuarios SET activo = 1, token = NULL WHERE id = ?');
        $stmt->execute([$usuario['id']]);

        echo 'Tu cuenta se ha activado con éxito. Ya puedes iniciar sesión. <a href="login">Inicia sesión</a>';
    } else {
        echo 'Token inválido o cuenta ya registrada';
    }
} else {
    echo 'No se ha proporcionado ningún token';
}
?>
