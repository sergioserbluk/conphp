<?php
require_once __DIR__ . '/coneccion.php';

function cargarUsuarios() {
    global $pdo;
    $stmt = $pdo->query('SELECT correoElectronico, nombre, apellido, dni FROM usuarios');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerUsuario($correo) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT correoElectronico, nombre, apellido, dni, pas FROM usuarios WHERE correoElectronico = ?');
    $stmt->execute([$correo]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function agregarUsuario($datos) {
    global $pdo;
    $sql = 'INSERT INTO usuarios (correoElectronico, nombre, apellido, dni, pas) VALUES (?, ?, ?, ?, ?)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $datos['correoElectronico'],
        $datos['nombre'],
        $datos['apellido'],
        $datos['dni'],
        password_hash($datos['pas'], PASSWORD_DEFAULT)
    ]);
}

function actualizarUsuario($correo, $datos) {
    global $pdo;
    $campos = ['nombre = ?', 'apellido = ?', 'dni = ?'];
    $params = [$datos['nombre'], $datos['apellido'], $datos['dni']];

    if (!empty($datos['pas'])) {
        $campos[] = 'pas = ?';
        $params[] = password_hash($datos['pas'], PASSWORD_DEFAULT);
    }

    $params[] = $correo;
    $sql = 'UPDATE usuarios SET ' . implode(', ', $campos) . ' WHERE correoElectronico = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
}
?>
