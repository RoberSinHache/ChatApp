<?php
require 'vendor/autoload.php';

$host = '127.0.0.1';
$db = 'chatapp';
$usuario = 'root';
$contrasenia = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $usuario, $contrasenia, $opciones);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$mailConfig = [
    'host' => 'smtp.gmail.com', 
    'smtp_auth' => true,
    'username' => 'asistentechatapp@gmail.com', 
    'password' => 'oadvsnbzgriifkau',
    'smtp_secure' => PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS,
    'port' => 587,
    'from_email' => 'asistentechatapp@gmail.com',
    'from_name' => 'ChatApp'
];
