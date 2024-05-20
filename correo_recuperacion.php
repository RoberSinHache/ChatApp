<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'includes/mail_config.php';
require 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    $consulta = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $consulta->execute([$email]);
    $usuario = $consulta->fetch();
    
    if ($usuario) {
        $token = bin2hex(random_bytes(50));
        $fecha_expiracion = date("Y-m-d H:i:s", strtotime('+1 hour'));
        
        $consulta = $pdo->prepare("INSERT INTO reseteo_contraseñas (email, token, fecha_expiracion) VALUES (?, ?, ?)");
        $consulta->execute([$email, $token, $fecha_expiracion]);

        try {
            $mail->addAddress($email);
            $mail->Subject = htmlspecialchars('Cambia tu contraseña');
            $mail->Body    = "Clica en el siguiente enlace para establecer una nueva: <a href='http://chatapp.local/nueva_contrasenia.php?token=$token'>Cambiar contraseña</a>";

            if($mail->send()) {
                echo "Se ha enviado un correo para que puedas cambiar tu contraseña. Revisa tu bandeja de entrada<br>";
                echo "<a href='login'>Inciar sesion</a>";
            } else {
                echo "No se pudo enviar el correo";
            }


        } catch (Exception $e) {
            echo "El mensaje no se pudo enviar: {$mail->ErrorInfo}";
        }

    } else {
        echo "No existe una cuenta con ese correo";
    }
}
?>
