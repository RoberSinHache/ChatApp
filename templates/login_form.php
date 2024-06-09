<?php session_start(); ?>

<div class="contenedor-formulario-previo">
    <form action="login.php" method="post" id="formulario-login" class='formulario-previo'>
        <h1>Inicio de sesión</h1>
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
        <input type="text" id="email" name="email" placeholder="Email" autocomplete="off" required>
        <input type="password" id="contrasenia" name="contrasenia" placeholder="Contraseña" autocomplete="off" required>
        <input type="submit" value="Iniciar sesión" class='col-12'>
        <div class="links">
            <a href="registro">Regístrate</a> o 
            <a href="recuperar_contrasenia">Recupera tu contraseña</a>
        </div>
    </form>
</div>

<script src='js\borrar_mensaje_sesion.js'></script>
