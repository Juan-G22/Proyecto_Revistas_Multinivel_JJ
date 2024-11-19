<?php
session_start(); // Iniciar sesión

// Verificar si la sesión está iniciada y si el rol es de administrador
if (!isset($_SESSION['id_persona']) || $_SESSION['rol'] != 3) {
    header("Location: ../index.php"); // Redirigir al índice si no tiene acceso
    exit();
}

// Incluir el archivo de conexión a la base de datos
include_once("../includes/db_connection.php");

// Llamar al procedimiento que obtiene todos los productos con un cursor
$query = "BEGIN obtener_todos_productos(:cursor); END;";
$stid = oci_parse($conexion, $query);

// Creamos un cursor en PHP
$cursor = oci_new_cursor($conexion);
oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);

// Ejecutar el procedimiento
oci_execute($stid);

// Abrir el cursor para leer los resultados
oci_execute($cursor);

// Almacenar los resultados en un array
$productos = [];
while ($row = oci_fetch_assoc($cursor)) {
    $productos[] = $row;
}

// Cerrar el cursor y liberar recursos
oci_free_cursor($cursor);
oci_free_statement($stid);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Revistas Multinivel JJ</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Revistas JJ</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="administrador.php">Gestionar Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="verVentas.php">Ver Ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="verColaboradores.php">Ver Colaboradores</a></li>
                <li class="nav-item"><a class="nav-link" href="gestionRevistas.php">Gestionar Revistas</a></li> <!-- Nueva sección -->
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-5">
        <h2>Bienvenido, Administrador</h2>
        <p>Aquí puedes gestionar los productos, consultar las estadísticas de ventas y revisar los colaboradores registrados.</p>

        <!-- Gestión de productos -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Gestión de productos</h5>
                <!-- Botón para añadir nuevo producto -->
                <a href="productos.php" class="btn btn-success">Añadir nuevo producto</a>
                
            </div>
        </div>

        <!-- Visualización de productos -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Lista de productos</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Comisionable</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Mostrar los productos obtenidos del cursor
                        foreach ($productos as $row) {
                            // Mostrar solo los productos activos
                            if ($row['ESTADO'] == 'A') {
                                echo "<tr>";
                                echo "<td>" . $row['ID_PRODUCTO'] . "</td>";
                                echo "<td>" . $row['NOMBRE'] . "</td>";
                                echo "<td>" . $row['DESCRIPCION'] . "</td>";
                                echo "<td>" . number_format($row['PRECIO'], 2) . "</td>";
                                echo "<td>" . ($row['COMISIONABLE'] == 'S' ? 'Sí' : 'No') . "</td>";
                                echo "<td>" . ($row['ESTADO'] == 'A' ? 'Activo' : 'Inactivo') . "</td>";
                                echo "<td>";
                                echo "<a href='editarProducto.php?id=" . $row['ID_PRODUCTO'] . "' class='btn btn-warning btn-sm'>Editar</a>";
                                echo "<a href='eliminarProducto.php?id=" . $row['ID_PRODUCTO'] . "' class='btn btn-danger btn-sm'>Eliminar</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ventas generales -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Ventas Generales</h5>
                <p>Total vendido: $XXXX</p>
                <p>Total de productos vendidos: XXXX</p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


