<?php

include './includes/common.php';

$data = json_decode(file_get_contents('php://input'), true);
$id_grupo = $data['id_grupo'];
$nombre_usuario = $data['nombre_usuario'];
$id_usuario_actual = $_SESSION['id_usuario'];

if ($id_grupo && $nombre_usuario) {
    try {
        // Verifico que el usuario actual es el administrador del grupo
        $sqlAdmin = "SELECT id_admin FROM grupos WHERE id = :id_grupo";
        $stmtAdmin = $pdo->prepare($sqlAdmin);
        $stmtAdmin->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
        $stmtAdmin->execute();
        $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

        if ($admin['id_admin'] != $id_usuario_actual) {
            echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para agregar usuarios a este grupo']);
            exit;
        }

        // Verifico que el usuario existe
        $sqlUsuario = "SELECT id FROM usuarios WHERE nombre = :nombre_usuario";
        $stmtUsuario = $pdo->prepare($sqlUsuario);
        $stmtUsuario->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $stmtUsuario->execute();
        $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
            exit;
        }

        // Agrego el usuario al grupo
        $sql = "INSERT INTO miembros_grupos (id_grupo, id_usuario) VALUES (:id_grupo, :id_usuario)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $usuario['id'], PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['status' => 'ok']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos no proporcionados']);
}
?>
