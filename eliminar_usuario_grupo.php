<?php

include './includes/common.php';

$data = json_decode(file_get_contents('php://input'), true);
$id_grupo = $data['id_grupo'];
$id_usuario = $data['id_usuario'];
$id_usuario_actual = $_SESSION['user_id'];

if ($id_grupo && $id_usuario) {
    try {
        // Verificar que el usuario actual es el administrador del grupo
        $sqlAdmin = "SELECT id_admin FROM grupos WHERE id = :id_grupo";
        $stmtAdmin = $pdo->prepare($sqlAdmin);
        $stmtAdmin->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
        $stmtAdmin->execute();
        $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

        if ($admin['id_admin'] != $id_usuario_actual) {
            echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para eliminar usuarios de este grupo']);
            exit;
        }

        // Eliminar el usuario del grupo
        $sql = "DELETE FROM miembros_grupos WHERE id_grupo = :id_grupo AND id_usuario = :id_usuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['status' => 'ok']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos no proporcionados']);
}
?>
