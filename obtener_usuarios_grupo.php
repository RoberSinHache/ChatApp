<?php

include './includes/common.php';

$id_grupo = isset($_GET['id_grupo']) ? $_GET['id_grupo'] : null;
$id_usuario_actual = $_SESSION['user_id']; // Suponiendo que el ID del usuario actual está en la sesión

if ($id_grupo) {
    try {
        // Obtener el ID del administrador del grupo
        $sqlAdmin = "SELECT id_admin FROM grupos WHERE id = :id_grupo";
        $stmtAdmin = $pdo->prepare($sqlAdmin);
        $stmtAdmin->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
        $stmtAdmin->execute();
        $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

        // Obtener los usuarios del grupo
        $sql = "SELECT usuarios.id, usuarios.nombre, usuarios.imagen_perfil 
                FROM usuarios 
                JOIN miembros_grupos ON usuarios.id = miembros_grupos.id_usuario 
                WHERE miembros_grupos.id_grupo = :id_grupo";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'ok',
            'usuarios' => $usuarios,
            'es_admin' => $admin['id_admin'] == $id_usuario_actual
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID de grupo no proporcionado']);
}
?>
