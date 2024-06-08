<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'includes/config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $consulta = $pdo->prepare("SELECT email FROM reseteo_contraseñas WHERE token = ? AND fecha_expiracion > NOW()");
    $consulta->execute([$token]);
    $solicitudDeCambio = $consulta->fetch();
    
    if ($solicitudDeCambio) {
        $email = $solicitudDeCambio['email'];
    } else {
        die("El token no es válido o ha expirado. Recuerda que tienes 1 hora para cambiar la contraseña");
    }

} else {
    die("Algo salió mal");
}
?>

<div class="contenedor-formulario-previo">
    <form action="actualizar_contrasenia.php" method="post" class='formulario-previo'>
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

        <input type="password" name="contrasenia" placeholder="Nueva contraseña" required>
        <input type="password" name="confirmar_contrasenia" placeholder="Confirmar contraseña" required>

        <input type="submit" value="Actualizar contraseña">
    </form>
</div>
