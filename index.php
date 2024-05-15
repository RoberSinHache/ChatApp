<?php
require 'includes/config.php';

$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 'bienvenida';

include 'templates/header.php';

switch ($pagina) {
    case 'login':
        include 'templates/login_form.php';
        break;
    case 'registro':
        include 'templates/registro_form.php';
        break;
    case 'verificacion':
        include 'verificacion.php';
        break;
    case 'home':
        include 'home.php';
        break;
    default:
        echo '<h2>Bienvenido a la página de inicio</h2>';
        echo '<p>ChatApp aún está en desarrollo</p>';
        echo '<a href=/login>Login</a>';
        echo '<br>';
        echo '<a href=/registro>Registro</a>';
        break;
}

include 'templates/footer.php';
?>
