<?php
session_start();
if (!isset($_SESSION['id_persona']) || $_SESSION['rol'] != 3) {
    header("Location: ../index.php");
    exit();
}
include_once("../includes/db_connection.php");

$idRevista = $_GET['id'];

// Obtener productos disponibles
$query = "BEGIN obtener_todos_productos(:cursor); END;";
$stid = oci_parse($conexion, $query);
$cursor = oci_new_cursor($conexion);
oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
oci_execute($stid);
oci_execute($cursor);

$productos = [];
while ($row = oci_fetch_assoc($cursor)) {
    $productos[] = $row;
}
oci_free_cursor($cursor);
oci_free_statement($stid);

// Obtener productos ya asociados a la revista
$query = "BEGIN :cursor := obtener_prod_revista(:id_revista); END;";
$stid = oci_parse($conexion, $query);
$cursor = oci_new_cursor($conexion);
oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
oci_bind_by_name($stid, ":id_revista", $idRevista);
oci_execute($stid);
oci_execute($cursor);

$productosAsociados = [];
while ($row = oci_fetch_assoc($cursor)) {
    $productosAsociados[] = $row;
}
oci_free_cursor($cursor);
oci_free_statement($stid);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asociar Productos a Revista</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Asociar Productos a Revista</h2>

        <!-- Botón para volver a gestionRevistas -->
        <div class="mb-3">
            <a href="gestionRevistas.php" class="btn btn-primary">Gestionar Revistas</a>
        </div>
        <!-- Formulario para agregar productos -->
        <form action="procesarAsociacion.php" method="POST">
            <input type="hidden" name="idRevista" value="<?= $idRevista; ?>">

            <div class="form-group">
                <label for="idProducto">Seleccionar Producto:</label>
                <select class="form-control" id="idProducto" name="idProducto" required>
                    <option value="">Seleccione un producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= $producto['ID_PRODUCTO']; ?>">
                            <?= $producto['NOMBRE']; ?> - $<?= $producto['PRECIO']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="esComisionable">¿Producto comisionable?</label>
                <select class="form-control" id="esComisionable" name="esComisionable" required>
                    <option value="N">No</option>
                    <option value="S">Sí</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Asociación</button>
            <a href="gestionRevistas.php" class="btn btn-secondary">Volver</a>
        </form>

        <!-- Tabla de productos asociados -->
        <h3 class="mt-5">Productos Asociados</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productosAsociados as $producto): ?>
                    <tr>
                        <td><?= $producto['ID_PRODUCTO']; ?></td>
                        <td><?= $producto['NOMBRE']; ?></td>
                        <td>$<?= $producto['PRECIO']; ?></td>
                        <td>
                            <form action="procesarEliminacion.php" method="POST" style="display:inline;">
                                <input type="hidden" name="idRevista" value="<?= $idRevista; ?>">
                                <input type="hidden" name="idProducto" value="<?= $producto['ID_PRODUCTO']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Quitar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

