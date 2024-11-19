<?php
session_start(); // Iniciar sesión

// Verificar si la sesión está iniciada y si el rol es de administrador
if (!isset($_SESSION['id_persona']) || $_SESSION['rol'] != 3) {
    header("Location: ../index.php"); // Redirigir al índice si no tiene acceso
    exit();
}

// Incluir el archivo de conexión a la base de datos
include_once("../includes/db_connection.php");

// Verificar si se ha recibido un ID de producto para eliminar
if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];

    // Llamar al procedimiento para eliminar el producto
    $query = "BEGIN eliminar_producto(:id_producto); END;";
    $stid = oci_parse($conexion, $query);

    // Enlazar el ID del producto al procedimiento
    oci_bind_by_name($stid, ":id_producto", $id_producto);

    // Ejecutar el procedimiento
    if (oci_execute($stid)) {
        // Redirigir al administrador después de la eliminación exitosa
        header("Location: administrador.php");
        exit();
    } else {
        echo "Error al eliminar el producto.";
    }

    // Liberar recursos
    oci_free_statement($stid);
}

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
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Eliminar Producto</h2>
        <form action="eliminarProducto.php" method="get">
            <div class="form-group">
                <label for="id_producto">Seleccionar Producto a Eliminar</label>
                <select name="id" id="id_producto" class="form-control">
                    <option value="">Seleccione un producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= $producto['ID_PRODUCTO']; ?>"><?= $producto['NOMBRE']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

