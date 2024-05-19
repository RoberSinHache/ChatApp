<?php
require 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $contrasenia = $_POST['contrasenia'];
    $confirmarContrasenia = $_POST['confirmar_contrasenia'];
    
    if ($contrasenia !== $confirmarContrasenia) {
        die("Las contraseñas no coinciden");
    }
    
    $consulta = $pdo->prepare("SELECT email FROM reseteo_contraseñas WHERE token = ? AND tiempo_expiracion > NOW()");
    $consulta->execute([$token]);
    $solicitudReseteo = $consulta->fetch();
    
    if ($solicitudReseteo) {
        $email = $solicitudReseteo['email'];
        $contraseniaHasheada = password_hash($contrasenia, PASSWORD_BCRYPT);
        
        $consulta = $pdo->prepare("UPDATE usuarios SET contraseña = ? WHERE email = ?");
        $consulta->execute([$contraseniaHasheada, $email]);
        
        $consulta = $pdo->prepare("DELETE FROM reseteo_contraseñas WHERE token = ?");
        $consulta->execute([$token]);
        
        echo "Has actualizado tu contraseña satisfactoriamente, ya puedes <a href='login'>iniciar sesión!</a>";

    } else {
        die("El token no es válido o ha expirado. Recuerda que tienes una hora para actualizarla");
    }
}
?>