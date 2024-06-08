<?php

$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 'login';

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
        include 'templates/home.php';
        break;
    case 'recuperar_contrasenia':
        include 'templates/recuperar_contrasenia.php';
        break;
    case 'nueva_contrasenia':
        include 'nueva_contrasenia.php';
        break;
}

include 'templates/footer.php';
?>
