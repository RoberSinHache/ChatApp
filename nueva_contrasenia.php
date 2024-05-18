<?php
require 'includes/config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $consulta = $pdo->prepare("SELECT email FROM reseteo_contraseñas WHERE token = ? AND tiempo_expiracion > NOW()");
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

<form action="actualizar_contrasenia.php" method="post">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

    <label for="contrasenia">Nueva contraseña:</label>
    <input type="password" name="contrasenia" required>

    <label for="confirmar_contrasenia">Confirmar contraseña:</label>
    <input type="password" name="confirmar_contrasenia" required>

    <input type="submit" value="Actualizar contraseña">
</form>
