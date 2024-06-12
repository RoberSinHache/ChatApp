<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require 'includes/mail_config.php';
require 'includes/config.php';
session_start();

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
            $mail->Body = "<div style='font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 30px; border-radius: 10px;'>
                                <h2 style='color: #333; text-align: center; margin-bottom: 20px;'>Cambia tu contraseña</h2>
                                <p style='color: #666; text-align: center; margin-bottom: 30px;'>Haz clic en el siguiente enlace para establecer una nueva:</p>
                                <div style='text-align: center;'>
                                    <a href='http://chatapp.local/index.php?pagina=nueva_contrasenia&token=$token' style='background-color: #007bff; color: #fff; text-decoration: none; padding: 15px 30px; border-radius: 30px; display: inline-block; transition: background-color 0.3s;'>Cambiar contraseña</a>
                                </div>
                            </div>";



            if($mail->send()) {
                $_SESSION['mensaje_sesion'] = "Se ha enviado un correo para que puedas cambiar tu contraseña. Revisa tu bandeja de entrada";
                $_SESSION['tipo_mensaje'] = "ok";
        
                header('Location: login');
                exit();

            } else {
                $_SESSION['mensaje_sesion'] = "No se pudo enviar el correo.";
                $_SESSION['tipo_mensaje'] = "error";
        
                header('Location: login');
                exit();
            }


        } catch (Exception $e) {
            $_SESSION['mensaje_sesion'] = "No se pudo enviar el correo.";
            $_SESSION['tipo_mensaje'] = "error";
    
            header('Location: recuperar_contrasenia');
            exit();
        }

    } else {
        $_SESSION['mensaje_sesion'] = "No existe una cuenta con ese correo";
        $_SESSION['tipo_mensaje'] = "error";

        header('Location: recuperar_contrasenia');
        exit();
    }

} else{
    header('Location: login');
    exit();
}
?>
