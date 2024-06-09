<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'includes/config.php';
session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $consulta = $pdo->prepare("SELECT email FROM reseteo_contraseñas WHERE token = ? AND fecha_expiracion > NOW()");
    $consulta->execute([$token]);
    $solicitudDeCambio = $consulta->fetch();
    
    if ($solicitudDeCambio) {
        $email = $solicitudDeCambio['email'];

    } else {
        $_SESSION['mensaje_sesion'] = "El token no es válido o ha expirado. Recuerda que tienes 1 hora para cambiar la contraseña";
        $_SESSION['tipo_mensaje'] = "error";

        header('Location: recuperar_contrasenia');
        exit();
    }

} else {
    $_SESSION['mensaje_sesion'] = "El enlace falló. Solicite un nuevo cambio";
    $_SESSION['tipo_mensaje'] = "error";

    header('Location: recuperar_contrasenia');
    exit();
}
?>

<div class="contenedor-formulario-previo">
    <form action="actualizar_contrasenia.php" id="formulario-nueva-contrasenia" method="post" class='formulario-previo'>
        <h1>Nueva contraseña</h1>
        <?php
        if (isset($_SESSION['mensaje_sesion']) && !empty($_SESSION['mensaje_sesion'])) {
            $mensaje = $_SESSION['mensaje_sesion'];
            $tipoMensaje = $_SESSION['tipo_mensaje'];
            $claseMensaje = ($tipoMensaje === 'ok') ? 'mensaje-ok' : 'mensaje-error';
            echo "<p id='mensaje_sesion' class='fade-out $claseMensaje'>$mensaje</p>";
        }

        unset($_SESSION['mensaje_sesion']);
        unset($_SESSION['tipo_mensaje']);
        ?>
        <p id="mensaje-error-registro" class="mensaje-error-registro"></p>
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

        <input type="password" name="contrasenia" id="contrasenia" placeholder="Nueva contraseña" required>
        <input type="password" name="confirmar_contrasenia" id="confirmar_contrasenia" placeholder="Confirmar contraseña" required>

        <input type="submit" value="Actualizar contraseña">
    </form>
</div>

<script src="js\validacion_nueva_contrasenia.js"></script>
