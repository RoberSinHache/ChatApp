<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include 'includes/mail_config.php';
require 'includes/config.php';

function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) &&
           preg_match('/^[^@]+@(hotmail|gmail|yahoo|outlook)\.(com|es)$/', $email);
}

function validarContrasenia($contrasenia) {
    return strlen($contrasenia) >= 8 && preg_match('/[A-Za-z0-9]/', $contrasenia);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $contrasenia = $_POST['contrasenia'];
    $repetir_contrasenia = $_POST['repetir_contrasenia'];

    $errores = [];

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE nombre = ?');
    $stmt->execute([$nombre]);
    if ($stmt->fetchColumn() > 0) {
        $errores[] = 'El nombre de usuario ya existe';
    }

    if (!validarEmail($email)) {
        $errores[] = 'El correo no es válido';
    }

    if (!validarContrasenia($contrasenia)) {
        $errores[] = 'La contraseña ha de contener 8 caracteres y al menos una letra o número';
    }

    if ($contrasenia !== $repetir_contrasenia) {
        $errores[] = 'Las contraseñas no coinciden';
    }

    if (empty($errores)) {
        $contrasenia_hasheada = password_hash($contrasenia, PASSWORD_DEFAULT);

        $token = bin2hex(random_bytes(16));

        $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, email, contraseña, token) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nombre, $email, $contrasenia_hasheada, $token]);

        try {
            $mail->addAddress($email);
            $mail->Subject = 'Sólo un paso más';
            $mail->Body    = "<h3>Bienvenido a ChatApp $nombre</h3><br>Clica en el siguiente enlace para completar el registro: <a href='http://chatapp.local/index.php?pagina=verificacion&token=$token'>Finalizar registro</a>";

            $mail->send();
            header('Location: login');
            exit();


        } catch (Exception $e) {
            echo "El mensaje no se pudo enviar: {$mail->ErrorInfo}";
        }
    } else {
        foreach ($errores as $error) {
            echo "<p>$error</p>";
        }
    }
} else {
    header('Location: login');
}
?>
