<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include './includes/common.php';


header('Content-Type: application/json');

$datos = json_decode(file_get_contents("php://input"), true);

if (!isset($datos['nombre'])) {
    throw new Exception('Se require el nombre de usuario');
}
$nombre = $datos['nombre'];
$id_usuario = $_SESSION['id_usuario'];

$consulta = $pdo->prepare("SELECT id FROM usuarios WHERE nombre = ?");
$consulta->execute([$nombre]);
$usuario_objetivo = $consulta->fetch();

if ($usuario_objetivo) {
    $consulta = $pdo->prepare("INSERT INTO mensajes_privados (id_usuario_1, id_usuario_2) VALUES (?, ?)");
    $consulta->execute([$id_usuario, $usuario_objetivo['id']]);
    echo json_encode(['status' => 'success']);

} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'El usuario no existe']);
}





?>
