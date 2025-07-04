<?php
<<<<<<< HEAD
require 'coneccion.php';

// Obtener marcas y modelos de la base de datos
$sqlVehiculos = "SELECT m.nombre AS marca, md.nombre AS modelo
        FROM marcas m
        JOIN modelos md ON md.marca_id = m.id
        ORDER BY m.nombre, md.nombre";
$vehiculos = $pdo->query($sqlVehiculos)->fetchAll();

// Obtener usuarios de la base de datos
$sqlUsuarios = "SELECT dni, pas FROM usuarios ORDER BY dni";
$usuarios = $pdo->query($sqlUsuarios)->fetchAll();
=======
require_once __DIR__ . '/vehiculoService.php';

$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;

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

$vehiculos = cargarVehiculos();
>>>>>>> develop
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>Dashboard</title>
    <style>
        nav a { margin-right: 1rem; }
        section { display: none; }
        section.active { display: block; }
    </style>
    <script>
    function showTab(id) {
        document.querySelectorAll('section').forEach(s => s.classList.remove('active'));
        document.getElementById(id).classList.add('active');
    }
    </script>
</head>
<body>
    <nav>
        <a href="#" onclick="showTab('vehiculos'); return false;">Vehículos</a>
        <a href="#" onclick="showTab('usuarios'); return false;">Usuarios</a>
    </nav>

    <section id="vehiculos" class="active">
        <h1>Vehículos registrados</h1>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Marca</th>
                    <th>Modelo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehiculos as $v): ?>
                <tr>
                    <td><?php echo htmlspecialchars($v['marca']); ?></td>
                    <td><?php echo htmlspecialchars($v['modelo']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section id="usuarios">
        <h1>Usuarios registrados</h1>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Contraseña</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?php echo htmlspecialchars($u['dni']); ?></td>
                    <td><?php echo htmlspecialchars($u['pas']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
=======
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
        <div class="tab-pane fade p-3" id="users" role="tabpanel" aria-labelledby="users-tab" tabindex="0">Gestión de usuarios próximamente</div>
    </div>
>>>>>>> develop
</body>
</html>
