<?php
include './includes/common.php';

$id_remitente = $_SESSION['id_usuario'];
$contenido = $_POST['contenido'];
$tipo_contenido = $_POST['tipo_contenido'];
$id_destinatario = $_POST['id_destinatario'];
$id_grupo = $_POST['id_grupo'] == "" ? null : $_POST['id_grupo'];

$ruta_archivo = NULL;
if ($tipo_contenido !== 'texto') {
    $archivo = $_FILES['archivo'];
    $directorio_objetivo = "subidos/";
    $ruta_archivo = $directorio_objetivo . basename($archivo["nombre"]);
    move_uploaded_archivo($archivo["nombre_temporal"], $ruta_archivo);
}

$stmt = $pdo->prepare('INSERT INTO mensajes (id_remitente, id_destinatario, id_grupo, contenido, tipo_contenido, ruta_archivo)
                       VALUES (?, ?, ?, ?, ?, ?)');
$stmt->execute([$id_remitente, $id_destinatario, $id_grupo, $contenido, $tipo_contenido, $ruta_archivo]);

echo json_encode(['status' => 'success']);
?>
