<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include './includes/common.php';

// Usuarios con los que se ha hablado
$stmt = $pdo->prepare('SELECT DISTINCT u.id, u.nombre, u.imagen_perfil
                       FROM usuarios u
                       JOIN mensajes_privados m ON (u.id = m.id_usuario_1 OR u.id = m.id_usuario_2)
                       WHERE (m.id_usuario_2 = ? OR m.id_usuario_1 = ?)
                       AND u.id != ?');

$stmt->execute([$_SESSION['id_usuario'], $_SESSION['id_usuario'], $_SESSION['id_usuario']]);
$usuarios = $stmt->fetchAll();

// Grupos de los que se es parte
$stmt = $pdo->prepare('SELECT g.id, g.nombre, g.icono
                       FROM grupos g
                       JOIN miembros_grupos mg ON g.id = mg.id_grupo
                       WHERE mg.id_usuario = ?');

$stmt->execute([$_SESSION['id_usuario']]);
$grupos = $stmt->fetchAll();

echo json_encode(['usuarios' => $usuarios, 'grupos' => $grupos]);
?>
