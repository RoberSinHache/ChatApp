<?php 
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include 'includes/config.php';

$sql = "DELETE FROM fecha_expiracion WHERE fecha_expiracion < CURDATE()";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>