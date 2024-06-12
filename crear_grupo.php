<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include './includes/common.php';

$nombre_grupo = isset($_POST['nombre-grupo']) ? $_POST['nombre-grupo'] : null;
$icono_grupo = isset($_FILES['icono-grupo']) ? $_FILES['icono-grupo'] : null;
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
$archivo_objetivo = 'subidos/grupos/porDefecto.png';

if ($nombre_grupo === null || $id_usuario === null) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos', 'post' => $_POST, 'files' => $_FILES]);
    exit;
}

if ($icono_grupo && !empty($icono_grupo['name'])) {
    $directorio_objetivo = "subidos/grupos/";
    $archivo_objetivo = $directorio_objetivo . basename($icono_grupo["name"]);

    if (!move_uploaded_file($icono_grupo["tmp_name"], $archivo_objetivo)) {
        echo json_encode(['status' => 'error', 'message' => 'Error al subir el archivo', 'files' => $icono_grupo]);
        exit;
    }
}

try {
    $pdo->beginTransaction();

    $consulta = $pdo->prepare("INSERT INTO grupos (nombre, icono, id_admin) VALUES (:nombre, :icono, :id_admin)");
    $consulta->execute([
        ':nombre' => $nombre_grupo,
        ':icono' => $archivo_objetivo,
        ':id_admin' => $id_usuario
    ]);

    $id_grupo = $pdo->lastInsertId();

    $consulta = $pdo->prepare("INSERT INTO miembros_grupos (id_grupo, id_usuario) VALUES (:id_grupo, :id_usuario)");
    $consulta->execute([
        ':id_grupo' => $id_grupo,
        ':id_usuario' => $id_usuario
    ]);

    $pdo->commit();

    echo json_encode(['status' => 'ok']);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
