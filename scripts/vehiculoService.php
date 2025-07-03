<?php

require_once __DIR__ . '/coneccion.php';

function cargarVehiculos() {
    global $pdo;
    $stmt = $pdo->query('SELECT id, marca, modelo, anio, precio, reservado FROM vehiculos');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function guardarVehiculos($vehiculos) {
    // Esta función ya no es necesaria con base de datos, se deja por compatibilidad
}

function obtenerVehiculo($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT id, marca, modelo, anio, precio, reservado FROM vehiculos WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function agregarVehiculo($nuevo) {
    global $pdo;
    $sql = 'INSERT INTO vehiculos (marca, modelo, anio, precio, reservado) VALUES (?, ?, ?, ?, ?)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nuevo['marca'],
        $nuevo['modelo'],
        $nuevo['anio'],
        $nuevo['precio'],
        $nuevo['reservado'] ?? 0
    ]);
    return $pdo->lastInsertId();
}

function actualizarVehiculo($id, $datos) {
    global $pdo;
    $sql = 'UPDATE vehiculos SET marca = ?, modelo = ?, anio = ?, precio = ? WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $datos['marca'],
        $datos['modelo'],
        $datos['anio'],
        $datos['precio'],
        $id
    ]);
}

function eliminarVehiculo($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM vehiculos WHERE id = ?');
    $stmt->execute([$id]);
}

function cambiarEstadoReservado($id, $reservado) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE vehiculos SET reservado = ? WHERE id = ?');
    $stmt->execute([$reservado, $id]);
}
?>
