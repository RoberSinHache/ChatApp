<?php session_start(); ?>

<div class="contenedor-formulario-previo">
    <form action="correo_recuperacion.php" method="post" id="formulario-recuperacion" class="formulario-previo">
        <label for="email">Indica tu correo:</label>
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
        <input type="email" name="email" placeholder="Email" autocomplete="off" required>
        <input type="submit" value="Cambiar contraseña">

        <div class="contenedor-enlace">
            <a href="login" id="enlace-retroceso"></a>
        </div>
    </form>
</div>