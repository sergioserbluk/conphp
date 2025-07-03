<?php
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</body>
</html>
