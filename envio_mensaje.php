<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include './includes/common.php';
setlocale(LC_ALL, 'en_US.UTF-8');

$id_remitente = $_SESSION['id_usuario'];
$contenido = $_POST['contenido'];
$tipo_contenido = $_POST['tipo_contenido'];
$id_destinatario = $_POST['id_destinatario'];
$id_grupo = $_POST['id_grupo'] == "" ? null : $_POST['id_grupo'];
$archivo = $_FILES['archivo'];


$informacion_path = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
$subida_permitida = 1;
$ruta_archivo = NULL;
$directorio_objetivo = "subidos/";
$ruta_imagenes = 'subidos/imagenes/';
$ruta_videos = 'subidos/videos/';
$ruta_archivos = 'subidos/archivos/';

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


if ($archivo["size"] > 5000000) {
    $subida_permitida = 0;

} else if($tipo_contenido == 'imagen' || $tipo_contenido == 'video'){
    $tipo_archivo = mime_content_type($archivo["tmp_name"]);

    if ($tipo_contenido == 'imagen') {
        $ruta_archivo = $ruta_imagenes . basename($archivo["name"]);
    
    } elseif ($tipo_contenido == 'video') {
        $ruta_archivo = $ruta_videos . basename($archivo["name"]);
    
    } else {
        echo "El archivo no tiene un formato inválido";
        $subida_permitida = 0;
    }

    $imagenes_permitidas = ["jpg", "jpeg", "png", "gif"];
    $videos_permitidos = ["mp4", "avi", "mov", "wmv"];
    if ($subida_permitida) {
        if ($tipo_contenido == 'imagen' && !in_array($informacion_path, $imagenes_permitidas)) {
            echo "Formato de imagen no permitido";
            $subida_permitida = 0;
        }
        if ($tipo_contenido == 'video' && !in_array($informacion_path, $videos_permitidos)) {
            echo "Formato de video no permitido";
            $subida_permitida = 0;
        }
    }

    if ($subida_permitida == 0) {
        echo "El archivo no pudo subirse";

    } else {
        if (move_uploaded_file($archivo["tmp_name"], $ruta_archivo)) {
            echo "El archivo". basename($archivo["name"]). " se ha guradado";
        } else {
            echo "El archivo no se ha guardado";
        }
    }

} else if($tipo_contenido == 'archivo'){
    $ruta_archivo = $ruta_archivos . basename($archivo["name"]);

    if ($subida_permitida == 0) {
        echo "El archivo no pudo subirse";

    } else {
        if (move_uploaded_file($archivo["tmp_name"], $ruta_archivo)) {
            echo "El archivo". basename($archivo["name"]). " se ha guradado";
        } else {
            echo "El archivo no se ha guardado";
        }
    }
}


$stmt = $pdo->prepare('INSERT INTO mensajes (id_remitente, id_destinatario, id_grupo, contenido, tipo_contenido, ruta_archivo)
                       VALUES (?, ?, ?, ?, ?, ?)');
$stmt->execute([$id_remitente, $id_destinatario, $id_grupo, $contenido, $tipo_contenido, $ruta_archivo]);

echo json_encode(['status' => 'success']);
?>
