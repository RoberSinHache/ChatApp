<?php
session_start();
$errores = isset($_SESSION['errores']) ? $_SESSION['errores'] : [];
unset($_SESSION['errores']);
?>

<div class="contenedor-formulario-previo">
    <form action='registro.php' class="formulario-previo" id="formulario-registro" method='post'>
        <h1>Registro</h1>
        <p id="mensaje-error-registro" class="mensaje-error-registro">
            <?php
                if (!empty($errores)) {
                    echo implode('<br>', $errores);
                }
            ?>
        </p>
        <input type='text' id='nombre' name='nombre' placeholder="Usuario" autocomplete="off" required><br>
        <input type='email' id='email' name='email' placeholder="Email" autocomplete="off" required><br>
        <input type='password' id='contrasenia' name='contrasenia' placeholder="Contraseña" autocomplete="off" required><br>
        <input type='password' id='repetir_contrasenia' name='repetir_contrasenia' placeholder="Repetir contraseña" autocomplete="off" required><br>

        <input type='submit' value='Registrarse'>

        <div class="contenedor-enlace">
            <a href="login" id="enlace-retroceso"></a>
        </div>
    </form>
</div>

<script src="js/validar_registro.js"></script>

