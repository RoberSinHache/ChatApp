<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include './includes/common.php';

$id_remitente = $_SESSION['id_usuario'];
$destinatario = $_GET['destinatario'];
$tipo = $_GET['tipo']; 

// Conversacion privada
if ($tipo == 'usuario') {
    $stmt = $pdo->prepare('SELECT m.*, u.nombre as nombre_remitente, u.imagen_perfil
                           FROM mensajes m
                           JOIN usuarios u ON m.id_remitente = u.id
                           WHERE (m.id_remitente = ? AND m.id_destinatario = ?) 
                           OR (m.id_remitente = ? AND m.id_destinatario = ?)
                           ORDER BY m.fecha_envio');
    $stmt->execute([$id_remitente, $destinatario, $destinatario, $id_remitente]);


// Mensajes de grupo
} else {
    $stmt = $pdo->prepare('SELECT m.*, u.nombre as nombre_remitente, u.imagen_perfil
                           FROM mensajes m
                           JOIN usuarios u ON m.id_remitente = u.id
                           WHERE m.id_grupo = ?
                           ORDER BY m.fecha_envio');
    $stmt->execute([$chat]);
}

$mensajes = $stmt->fetchAll();
echo json_encode($mensajes);
?>
