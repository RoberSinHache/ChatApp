<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'includes/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $contrasenia = $_POST['contrasenia'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($contrasenia)) {
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            if ($usuario['activo']) {
                if (password_verify($contrasenia, $usuario['contraseña'])) {
                    $_SESSION['id_usuario'] = $usuario['id'];
                    $_SESSION['nombre_usuario'] = $usuario['nombre'];
                    $_SESSION['imagen_perfil'] = $usuario['imagen_perfil'];
                    header('Location: home');
                    exit();
                } else {
                    echo 'El correo o contraseña no son correctos';
                }
            } else {
                echo 'Tu cuenta aún no se ha activado. Por favor, comprueba tu correo.';
            }
        } else {
            echo 'El correo o contraseña no son correctos';
        }
    } else {
        echo 'Introduce un correo o contraseña válidos';
    }
} else {
    echo 'La solicitud no es válida';
}
?>
