<?php

include './includes/common.php';

session_destroy();
header('Location: bienvenida');
exit();