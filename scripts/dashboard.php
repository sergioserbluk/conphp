<?php
require_once __DIR__ . '/vehiculoService.php';
require_once __DIR__ . '/usuarioService.php';

$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;
$accionUsr = $_POST['accionUsr'] ?? $_GET['accionUsr'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($accion === 'agregar') {
        $nuevo = [
            'marca' => $_POST['marca'],
            'modelo' => $_POST['modelo'],
            'anio' => (int)$_POST['anio'],
            'precio' => (float)$_POST['precio'],
            'reservado' => false
        ];
        agregarVehiculo($nuevo);
    } elseif ($accion === 'actualizar') {
        $id = (int)$_POST['id'];
        $datos = [
            'marca' => $_POST['marca'],
            'modelo' => $_POST['modelo'],
            'anio' => (int)$_POST['anio'],
            'precio' => (float)$_POST['precio']
        ];
        actualizarVehiculo($id, $datos);
    } elseif ($accionUsr === 'agregar') {
        $nuevoUsr = [
            'correoElectronico' => $_POST['correoElectronico'],
            'nombre' => $_POST['nombre'],
            'apellido' => $_POST['apellido'],
            'dni' => (int)$_POST['dni'],
            'pas' => $_POST['pas']
        ];
        agregarUsuario($nuevoUsr);
        header('Location: dashboard.php#users');
        exit;
    } elseif ($accionUsr === 'actualizar') {
        $correo = $_POST['correoElectronico'];
        $datosUsr = [
            'nombre' => $_POST['nombre'],
            'apellido' => $_POST['apellido'],
            'dni' => (int)$_POST['dni'],
            'pas' => $_POST['pas'] ?? ''
        ];
        actualizarUsuario($correo, $datosUsr);
        header('Location: dashboard.php#users');
        exit;
    }
    header('Location: dashboard.php');
    exit;
}

if ($accion === 'eliminar' && isset($_GET['id'])) {
    eliminarVehiculo((int)$_GET['id']);
    header('Location: dashboard.php');
    exit;
}

if ($accion === 'reservar' && isset($_GET['id'])) {
    $veh = obtenerVehiculo((int)$_GET['id']);
    if ($veh) {
        cambiarEstadoReservado($veh['id'], !$veh['reservado']);
    }
    header('Location: dashboard.php');
    exit;
}

$vehiculoEditar = null;
if ($accion === 'editar' && isset($_GET['id'])) {
    $vehiculoEditar = obtenerVehiculo((int)$_GET['id']);
}

$usuarios = cargarUsuarios();
$usuarioEditar = null;
if ($accionUsr === 'editar' && isset($_GET['correo'])) {
    $usuarioEditar = obtenerUsuario($_GET['correo']);
}

$vehiculos = cargarVehiculos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <title>Dashboard</title>
</head>
<body class="container py-4">
    <h1>Tablero de Administración</h1>
    <p>Bienvenido al tablero de control. Aquí puedes gestionar las publicaciones de vehículos.</p>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pub-tab" data-bs-toggle="tab" data-bs-target="#pub" type="button" role="tab" aria-controls="pub" aria-selected="true">Publicaciones</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="false">Usuarios</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active p-3" id="pub" role="tabpanel" aria-labelledby="pub-tab" tabindex="0">
            <h2><?php echo $vehiculoEditar ? 'Editar Vehículo' : 'Nueva Publicación'; ?></h2>
            <form method="post" class="row g-3">
                <?php if ($vehiculoEditar): ?>
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" name="id" value="<?php echo $vehiculoEditar['id']; ?>">
                <?php else: ?>
                    <input type="hidden" name="accion" value="agregar">
                <?php endif; ?>
                <div class="col-md-3">
                    <label class="form-label">Marca</label>
                    <input type="text" name="marca" class="form-control" value="<?php echo $vehiculoEditar['marca'] ?? ''; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Modelo</label>
                    <input type="text" name="modelo" class="form-control" value="<?php echo $vehiculoEditar['modelo'] ?? ''; ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Año</label>
                    <input type="number" name="anio" class="form-control" value="<?php echo $vehiculoEditar['anio'] ?? ''; ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Precio</label>
                    <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo $vehiculoEditar['precio'] ?? ''; ?>" required>
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <?php if ($vehiculoEditar): ?>
                        <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
                    <?php endif; ?>
                </div>
            </form>
            <hr>
            <h3>Listado de Vehículos</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehiculos as $v): ?>
                    <tr>
                        <td><?php echo $v['id']; ?></td>
                        <td><?php echo htmlspecialchars($v['marca']); ?></td>
                        <td><?php echo htmlspecialchars($v['modelo']); ?></td>
                        <td><?php echo $v['anio']; ?></td>
                        <td><?php echo number_format($v['precio'], 2); ?></td>
                        <td><?php echo $v['reservado'] ? 'Reservado' : 'Disponible'; ?></td>
                        <td>
                            <a class="btn btn-sm btn-warning" href="?accion=editar&id=<?php echo $v['id']; ?>">Editar</a>
                            <a class="btn btn-sm btn-info" href="?accion=reservar&id=<?php echo $v['id']; ?>">
                                <?php echo $v['reservado'] ? 'Liberar' : 'Reservar'; ?>
                            </a>
                            <a class="btn btn-sm btn-danger" href="?accion=eliminar&id=<?php echo $v['id']; ?>" onclick="return confirm('¿Eliminar vehículo?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade p-3" id="profile" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">Sección en construcción</div>
        <div class="tab-pane fade p-3" id="contact" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">Sección en construcción</div>
        <div class="tab-pane fade p-3" id="users" role="tabpanel" aria-labelledby="users-tab" tabindex="0">
            <h2><?php echo $usuarioEditar ? 'Editar Usuario' : 'Nuevo Usuario'; ?></h2>
            <form method="post" class="row g-3">
                <?php if ($usuarioEditar): ?>
                    <input type="hidden" name="accionUsr" value="actualizar">
                    <input type="hidden" name="correoElectronico" value="<?php echo $usuarioEditar['correoElectronico']; ?>">
                <?php else: ?>
                    <input type="hidden" name="accionUsr" value="agregar">
                <?php endif; ?>
                <div class="col-md-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="correoElectronico" class="form-control" value="<?php echo $usuarioEditar['correoElectronico'] ?? ''; ?>" <?php echo $usuarioEditar ? 'readonly' : 'required'; ?>>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo $usuarioEditar['nombre'] ?? ''; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="apellido" class="form-control" value="<?php echo $usuarioEditar['apellido'] ?? ''; ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">DNI</label>
                    <input type="number" name="dni" class="form-control" value="<?php echo $usuarioEditar['dni'] ?? ''; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Contraseña<?php if ($usuarioEditar) echo ' (dejar en blanco para no cambiar)'; ?></label>
                    <input type="password" name="pas" class="form-control">
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <?php if ($usuarioEditar): ?>
                        <a href="dashboard.php#users" class="btn btn-secondary">Cancelar</a>
                    <?php endif; ?>
                </div>
            </form>
            <hr>
            <h3>Listado de Usuarios</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Correo</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['correoElectronico']); ?></td>
                        <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($u['apellido']); ?></td>
                        <td><?php echo $u['dni']; ?></td>
                        <td>
                            <a class="btn btn-sm btn-warning" href="?accionUsr=editar&correo=<?php echo urlencode($u['correoElectronico']); ?>#users">Editar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        if (location.hash) {
            var triggerEl = document.querySelector('button[data-bs-target="' + location.hash + '"]');
            if (triggerEl) {
                bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            }
        }

        var tabButtons = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabButtons.forEach(function (button) {
            button.addEventListener('shown.bs.tab', function (event) {
                var target = event.target.getAttribute('data-bs-target');
                history.replaceState(null, '', target);
            });
        });
    });
    </script>
</body>
</html>
