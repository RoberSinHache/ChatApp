<?php

error_reporting(E_ERROR | E_PARSE);

include './includes/common.php';
setlocale(LC_ALL, 'en_US.UTF-8');

$id_remitente = $_SESSION['id_usuario'];
$id_destinatario = $_POST['id_destinatario'];
$id_grupo = $_POST['id_grupo'] == "" ? null : $_POST['id_grupo'];
$contenido = $_POST['contenido'];
$tipo_contenido = $_POST['tipo_contenido'];
$archivo = $_FILES['archivo'];


$ruta_informacion = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
$subida_permitida = 1;
$ruta_archivo = NULL;
$directorio_objetivo = "subidos/";
$ruta_imagenes = 'subidos/imagenes/';
$ruta_videos = 'subidos/videos/';
$ruta_archivos = 'subidos/archivos/';

// Me aseguro de que existan las carpetas donde se almacenarán 
// los archivos y que tienen los permisos correspondientes
if (!is_dir($directorio_objetivo)) {
    mkdir($directorio_objetivo, 0777, true);
} 
if(!is_dir($ruta_imagenes)){
    mkdir($ruta_imagenes, 0777, true);
} 
if(!is_dir($ruta_videos)){
    mkdir($ruta_videos, 0777, true); 
}
if(!is_dir($ruta_archivos)){
    mkdir($ruta_archivos, 0777, true);
}


// Compruebo que el archivo no sea muy pesado y del tipo que es
if ($archivo["size"] > 5000000) {
    $subida_permitida = 0;

} else if($tipo_contenido == 'imagen' || $tipo_contenido == 'video'){
    $tipo_archivo = mime_content_type($archivo["tmp_name"]);

    if ($tipo_contenido == 'imagen') {
        $ruta_archivo = $ruta_imagenes . basename($archivo["name"]);
    
    } elseif ($tipo_contenido == 'video') {
        $ruta_archivo = $ruta_videos . basename($archivo["name"]);
    
    } else {
        //El archivo tiene un formato inválido
        $subida_permitida = 0;
    }

    $imagenes_permitidas = ["jpg", "jpeg", "png", "gif"];
    $videos_permitidos = ["mp4", "avi", "mov", "wmv"];
    if ($subida_permitida) {
        if ($tipo_contenido == 'imagen' && !in_array($ruta_informacion, $imagenes_permitidas)) {
            // Formato de imagen no permitido
            $subida_permitida = 0;
        }
        if ($tipo_contenido == 'video' && !in_array($ruta_informacion, $videos_permitidos)) {
            // Formato de video no permitido
            $subida_permitida = 0;
        }
    }

    if ($subida_permitida != 0) {
        move_uploaded_file($archivo["tmp_name"], $ruta_archivo);
    }

} else if($tipo_contenido == 'archivo'){
    $nombre_archivo = basename($archivo['name']);
    $ruta_archivo = $ruta_archivos . $nombre_archivo;

    move_uploaded_file($archivo['tmp_name'], $ruta_archivo);
}


try {
    $sql = "INSERT INTO mensajes (id_remitente, id_destinatario, id_grupo, contenido, tipo_contenido, ruta_archivo, nombre_archivo)
            VALUES (:id_remitente, :id_destinatario, :id_grupo, :contenido, :tipo_contenido, :ruta_archivo, :nombre_archivo)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_remitente', $id_remitente, PDO::PARAM_INT);
    $stmt->bindParam(':id_destinatario', $id_destinatario, PDO::PARAM_INT);
    $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
    $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_contenido', $tipo_contenido, PDO::PARAM_STR);
    $stmt->bindParam(':ruta_archivo', $ruta_archivo, PDO::PARAM_STR);
    $stmt->bindParam(':nombre_archivo', $nombre_archivo, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
