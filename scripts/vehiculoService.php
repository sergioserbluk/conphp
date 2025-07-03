<?php

const DATA_FILE = __DIR__ . '/../db/vehiculos.json';

function cargarVehiculos() {
    if (!file_exists(DATA_FILE)) {
        return [];
    }
    $json = file_get_contents(DATA_FILE);
    $data = json_decode($json, true);
    return $data ?: [];
}

function guardarVehiculos($vehiculos) {
    $json = json_encode($vehiculos, JSON_PRETTY_PRINT);
    file_put_contents(DATA_FILE, $json);
}

function obtenerVehiculo($id) {
    $vehiculos = cargarVehiculos();
    foreach ($vehiculos as $v) {
        if ($v['id'] == $id) {
            return $v;
        }
    }
    return null;
}

function agregarVehiculo($nuevo) {
    $vehiculos = cargarVehiculos();
    $nuevo['id'] = obtenerSiguienteId($vehiculos);
    $vehiculos[] = $nuevo;
    guardarVehiculos($vehiculos);
}

function obtenerSiguienteId($vehiculos) {
    $max = 0;
    foreach ($vehiculos as $v) {
        if ($v['id'] > $max) {
            $max = $v['id'];
        }
    }
    return $max + 1;
}

function actualizarVehiculo($id, $datos) {
    $vehiculos = cargarVehiculos();
    foreach ($vehiculos as &$v) {
        if ($v['id'] == $id) {
            $v = array_merge($v, $datos);
            break;
        }
    }
    guardarVehiculos($vehiculos);
}

function eliminarVehiculo($id) {
    $vehiculos = cargarVehiculos();
    $vehiculos = array_filter($vehiculos, function($v) use ($id) {
        return $v['id'] != $id;
    });
    guardarVehiculos(array_values($vehiculos));
}

function cambiarEstadoReservado($id, $reservado) {
    actualizarVehiculo($id, ['reservado' => $reservado]);
}
?>
