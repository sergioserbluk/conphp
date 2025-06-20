<?php
$host = 'localhost';       // o 127.0.0.1
$db   = 'consecionaria'; // Cambiar por el nombre real de la base
$user = 'root';      // Cambiar por tu usuario de MySQL
$pass = '';        // Cambiar por tu clave de MySQL
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Modo error: excepciones
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch por defecto: asociativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Sin emulación de prepare
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>