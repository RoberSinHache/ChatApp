<?php
error_reporting(E_ERROR | E_PARSE);

include './includes/common.php';

session_start();

$id_remitente = $_SESSION['id_usuario'];
$id_destinatario = isset($_POST['id_destinatario']) ? $_POST['id_destinatario'] : null;
$id_grupo = isset($_POST['id_grupo']) ? $_POST['id_grupo'] : null;
$contenido = $_POST['contenido'];
$tipo_contenido = $_POST['tipo_contenido'];
$archivo = isset($_FILES['archivo']) ? $_FILES['archivo'] : null;
$nombre_archivo = null;

$ruta_informacion = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
$subida_permitida = 1;
$ruta_archivo = NULL;
$directorio_objetivo = "subidos/";
$ruta_imagenes = 'subidos/imagenes/';
$ruta_videos = 'subidos/videos/';
$ruta_archivos = 'subidos/archivos/';

if (!is_dir($directorio_objetivo)) {
    mkdir($directorio_objetivo, 0777, true);
} 
if (!is_dir($ruta_imagenes)) {
    mkdir($ruta_imagenes, 0777, true);
} 
if (!is_dir($ruta_videos)) {
    mkdir($ruta_videos, 0777, true); 
}
if (!is_dir($ruta_archivos)) {
    mkdir($ruta_archivos, 0777, true);
}


if ($tipo_contenido == 'imagen' || $tipo_contenido == 'video' || $tipo_contenido == 'archivo') {
    $tipo_archivo = mime_content_type($archivo["tmp_name"]);

    if ($tipo_contenido == 'imagen') {
        $ruta_archivo = $ruta_imagenes . basename($archivo["name"]);
    
    } elseif ($tipo_contenido == 'video') {
        $ruta_archivo = $ruta_videos . basename($archivo["name"]);
    
    } elseif ($tipo_contenido == 'archivo') {
        $ruta_archivo = $ruta_archivos . basename($archivo["name"]);
    
    } else {
        $subida_permitida = 0;
    }

    $imagenes_permitidas = ["jpg", "jpeg", "png", "gif"];
    $videos_permitidos = ["mp4", "avi", "mov", "wmv"];

    if ($subida_permitida) {
        if ($tipo_contenido == 'imagen' && !in_array($ruta_informacion, $imagenes_permitidas)) {
            $subida_permitida = 0;
        }
        if ($tipo_contenido == 'video' && !in_array($ruta_informacion, $videos_permitidos)) {
            $subida_permitida = 0;
        }
    }

    if ($subida_permitida != 0) {
        if (move_uploaded_file($archivo["tmp_name"], $ruta_archivo)) {
            $nombre_archivo = basename($archivo["name"]);
        } else {
            $ruta_archivo = NULL;
            $nombre_archivo = NULL;
        }
    }

} else {
    $ruta_archivo = NULL;
    $nombre_archivo = NULL;
}

try {
    if (!empty($id_destinatario)) {
        $sql = "INSERT INTO mensajes (id_remitente, id_destinatario, contenido, tipo_contenido, ruta_archivo, nombre_archivo)
                VALUES (:id_remitente, :id_destinatario, :contenido, :tipo_contenido, :ruta_archivo, :nombre_archivo)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_destinatario', $id_destinatario, PDO::PARAM_INT);

    } else {
        $sql = "INSERT INTO mensajes (id_remitente, id_grupo, contenido, tipo_contenido, ruta_archivo, nombre_archivo)
                VALUES (:id_remitente, :id_grupo, :contenido, :tipo_contenido, :ruta_archivo, :nombre_archivo)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
    }

    $stmt->bindParam(':id_remitente', $id_remitente, PDO::PARAM_INT);
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
