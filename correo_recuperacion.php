<?php
require 'includes/mail_config.php';
require 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    $consulta = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $consulta->execute([$email]);
    $usuario = $consulta->fetch();
    
    if ($usuario) {
        $token = bin2hex(random_bytes(50));
        $tiempo_expiracion = date("Y-m-d H:i:s", strtotime('+1 hour'));
        
        $consulta = $pdo->prepare("INSERT INTO reseteo_contraseñas (email, token, tiempo_expiracion) VALUES (?, ?, ?)");
        $consulta->execute([$email, $token, $tiempo_expiracion]);

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
