<?php
require '../MODELO/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) &&
           preg_match('/^[^@]+@(hotmail|gmail|yahoo|outlook)\.(com|es)$/', $email);
}

function validatePassword($contrasenia) {
    return strlen($contrasenia) >= 8 && preg_match('/[A-Za-z0-9]/', $contrasenia);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $contrasenia = $_POST['contrasenia'];
    $repetir_contrasenia = $_POST['repetir_contrasenia'];

    $errors = [];

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE nombre = ?');
    $stmt->execute([$nombre]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = 'El nombre de usuario ya existe';
    }

    if (!validateEmail($email)) {
        $errors[] = 'El correo no es válido';
    }

    if (!validatePassword($contrasenia)) {
        $errors[] = 'La contraseña ha de contener 8 caracteres y al menos una letra o número';
    }

    if ($contrasenia !== $repetir_contrasenia) {
        $errors[] = 'Las contraseñas no coinciden';
    }

    if (empty($errors)) {
        $contrasenia_hasheada = password_hash($contrasenia, PASSWORD_DEFAULT);

        $token = bin2hex(random_bytes(16));

        $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, email, contraseña, token) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nombre, $email, $contrasenia_hasheada, $token]);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'asistentechatapp@gmail.com';
            $mail->Password = 'oadvsnbzgriifkau';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('asistentechatapp@gmail.com', 'Rober');
            $mail->addAddress($email, $nombre);

            $mail->isHTML(true);
            $mail->Subject = 'Completa el registro';
            $mail->Body    = "Clica en el siguiente enlace para completar el registro: <a href='http://chatapp.local/CONTROLADOR/procesoVerificarRegistro.php?token=$token'>Finalizar registro</a>";

            $mail->send();
            echo 'Registro finalizado con éxito, comprueba tu correo para acceder al enlace de verificaión';


        } catch (Exception $e) {
            echo "El mensaje no se pudo enviar: {$mail->ErrorInfo}";
        }
    } else {
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
}
?>
