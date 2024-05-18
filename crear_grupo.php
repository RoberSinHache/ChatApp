<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './includes/common.php';

echo var_dump($_FILES['icono-grupo']);

$nombre_grupo = $_POST['nombre-grupo'];
$icono_grupo = $_FILES['icono-grupo'];
$id_usuario = $_SESSION['id_usuario'];


$directorio_objetivo = "../subidos/";
$archivo_objetivo = $directorio_objetivo . basename($icono_grupo["nombre"]);
move_uploaded_file($icono_grupo["nombre_temporal"], $archivo_objetivo);

$consulta = $pdo->prepare("INSERT INTO grupos (nombre, icono, id_admin) VALUES (?, ?, ?)");
$consulta->execute([$nombre_grupo, $archivo_objetivo, $id_usuario]);
$id_grupo = $pdo->lastInsertId();

$consulta = $pdo->prepare("INSERT INTO miembros_grupos (id_grupo, id_usuario) VALUES (?, ?)");
$consulta->execute([$id_grupo, $id_usuario]);

echo json_encode(['status' => 'success']);
?>
