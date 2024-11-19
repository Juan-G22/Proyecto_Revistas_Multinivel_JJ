<?php
session_start(); // Iniciar sesión

// Verificar si la sesión está iniciada y si el rol es de cliente
if (!isset($_SESSION['id_persona']) || $_SESSION['rol'] != 1) {
    header("Location: ../index.php"); // Redirigir al índice si no tiene acceso
    exit();
}

// Incluir la conexión a la base de datos
include_once("../includes/db_connection.php");

// Obtener productos del catálogo
$query = "BEGIN obtener_todos_productos(:cursor); END;";
$stid = oci_parse($conexion, $query);

// Crear un cursor para obtener los resultados
$cursor = oci_new_cursor($conexion);
oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);

// Ejecutar el procedimiento almacenado
oci_execute($stid);
oci_execute($cursor);

// Guardar los productos en un array
$productos = [];
while ($row = oci_fetch_assoc($cursor)) {
    if ($row['ESTADO'] === 'A') { // Filtrar solo productos activos
        $productos[] = $row;
    }
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
    <title>Cliente - Revistas Multinivel JJ</title>
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
                <li class="nav-item"><a class="nav-link" href="#cliente.php">Catálogo de Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Mis Compras</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-5">
        <h2>Bienvenido a tu panel de cliente</h2>
        <p>Aquí puedes ver los productos disponibles, realizar compras y consultar tu historial.</p>

        <!-- Catálogo de productos -->
        <div class="row">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                        <img src="../uploads/<?= htmlspecialchars($producto['FOTO']) ?>" class="card-img-top" alt="Imagen del producto">


                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($producto['NOMBRE']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($producto['DESCRIPCION']) ?></p>
                                <p class="card-text"><strong>Precio:</strong> $<?= htmlspecialchars($producto['PRECIO']) ?></p>
                                <a href="comprarProducto.php?id=<?= $producto['ID_PRODUCTO'] ?>" class="btn btn-primary">Comprar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">No hay productos disponibles en este momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


