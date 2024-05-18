<?php
include './includes/common.php';

// Usuarios con los que se ha hablado
$stmt = $pdo->prepare('SELECT DISTINCT u.id, u.nombre, u.imagen_perfil
                       FROM usuarios u
                       JOIN mensajes_privados m ON (u.id = m.id_usuario OR u.id = m.chat)
                       WHERE (m.chat = ? OR m.id_usuario = ?)
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
