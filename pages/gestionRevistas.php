<?php
session_start(); // Iniciar sesión

// Verificar si la sesión está iniciada y si el rol es de administrador
if (!isset($_SESSION['id_persona']) || $_SESSION['rol'] != 3) {
    header("Location: ../index.php"); // Redirigir al índice si no tiene acceso
    exit();
}

// Incluir el archivo de conexión a la base de datos
include_once("../includes/db_connection.php");

// Llamar al procedimiento que obtiene todas las revistas con un cursor
$query = "BEGIN obtener_todas_revistas(:cursor); END;";
$stid = oci_parse($conexion, $query);

// Creamos un cursor en PHP
$cursor = oci_new_cursor($conexion);
oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);

// Ejecutar el procedimiento
oci_execute($stid);

// Abrir el cursor para leer los resultados
oci_execute($cursor);

// Almacenar los resultados en un array
$revistas = [];
while ($row = oci_fetch_assoc($cursor)) {
    $revistas[] = $row;
}

// Cerrar el cursor y liberar recursos
oci_free_cursor($cursor);
oci_free_statement($stid);

// Llamar a la función almacenada para obtener los productos asociados a una revista
function obtenerProductosPorRevista($idRevista) {
    global $conexion;

    // Preparar la llamada a la función almacenada
    $query = "BEGIN :cursor := obtener_prod_revista(:id_revista); END;";
    $stid = oci_parse($conexion, $query);

    // Crear un cursor para almacenar los resultados
    $cursor = oci_new_cursor($conexion);
    oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
    oci_bind_by_name($stid, ":id_revista", $idRevista);

    // Ejecutar la función
    oci_execute($stid);

    // Abrir el cursor para leer los resultados
    oci_execute($cursor);

    // Almacenar los productos en un array
    $productos = [];
    while ($row = oci_fetch_assoc($cursor)) {
        $productos[] = $row;
    }

    // Liberar recursos
    oci_free_cursor($cursor);
    oci_free_statement($stid);

    return $productos;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Revistas</title>
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
        <p>Aquí puedes gestionar las revistas de la tienda.</p>

        <!-- Gestión de revistas -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Gestión de revistas</h5>
                <!-- Botón para añadir nueva revista -->
                <a href="agregarRevista.php" class="btn btn-success">Añadir nueva revista</a>
            </div>
        </div>

        <!-- Visualización de revistas -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Lista de revistas</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha de Publicación</th>
                            <th>Número de Edición</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Productos Asociados</th> <!-- Nueva columna -->
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Mostrar las revistas obtenidas del cursor
                        foreach ($revistas as $row) {
                            // Mostrar solo las revistas activas
                            if ($row['ESTADO'] == 'A') {
                                echo "<tr>";
                                echo "<td>" . $row['ID_REVISTA'] . "</td>";
                                echo "<td>" . $row['FECHA_PUBLICACION'] . "</td>";
                                echo "<td>" . $row['NUMERO_EDICION'] . "</td>";
                                echo "<td>" . $row['DESCRIPCION'] . "</td>";
                                echo "<td>" . ($row['ESTADO'] == 'A' ? 'Activo' : 'Inactivo') . "</td>";
                                echo "<td>";

                                // Obtener los productos asociados a la revista
                                $productos = obtenerProductosPorRevista($row['ID_REVISTA']);

                                // Mostrar los productos asociados
                                if (count($productos) > 0) {
                                    echo "<ul>";
                                    foreach ($productos as $producto) {
                                        echo "<li>" . $producto['NOMBRE'] . " - " . $producto['PRECIO'] . "</li>";
                                    }
                                    echo "</ul>";
                                } else {
                                    echo "No hay productos asociados";
                                }

                                echo "</td>";
                                echo "<td>";
                                echo "<a href='editarRevista.php?id=" . $row['ID_REVISTA'] . "' class='btn btn-warning btn-sm'>Editar</a>";
                                echo "<a href='eliminarRevista.php?id=" . $row['ID_REVISTA'] . "' class='btn btn-danger btn-sm'>Eliminar</a>";
                                echo "<a href='asociarProductosRevista.php?id=". $row['ID_REVISTA'] . "' class='btn btn-info btn-sm'>Agregar productos</a> ";
                                //echo "<a href='actualizarComisionable.php?id=" . $row['ID_REVISTA'] . "' class='btn btn-info btn-sm'>Actualizar Comisión</a>"; // Enlace agregado
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>





