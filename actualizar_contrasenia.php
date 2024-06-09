<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require 'includes/config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $contrasenia = $_POST['contrasenia'];
    $confirmarContrasenia = $_POST['confirmar_contrasenia'];
    
    if ($contrasenia !== $confirmarContrasenia) {
        die("Las contraseñas no coinciden");
    }
    
    $consulta = $pdo->prepare("SELECT email FROM reseteo_contraseñas WHERE token = ? AND fecha_expiracion > NOW()");
    $consulta->execute([$token]);
    $solicitudReseteo = $consulta->fetch();
    
    if ($solicitudReseteo) {
        $email = $solicitudReseteo['email'];
        $contraseniaHasheada = password_hash($contrasenia, PASSWORD_BCRYPT);
        
        $consulta = $pdo->prepare("UPDATE usuarios SET contraseña = ? WHERE email = ?");
        $consulta->execute([$contraseniaHasheada, $email]);
        
        $consulta = $pdo->prepare("DELETE FROM reseteo_contraseñas WHERE email = ?");
        $consulta->execute([$email]);
        
        $_SESSION['mensaje_sesion'] = "Se ha cambiado la contraseña satisfactoriamente";
        $_SESSION['tipo_mensaje'] = "ok";

        header('Location: login');
        exit();

    } else {
        $_SESSION['mensaje_sesion'] = "El token no es válido o ha expirado. Recuerda que tienes una hora para actualizarla";
        $_SESSION['tipo_mensaje'] = "error";

        header('Location: recuperar_contrasenia');
        exit();
    }

} else {
    header('Location: login');
    exit();
}
?>
