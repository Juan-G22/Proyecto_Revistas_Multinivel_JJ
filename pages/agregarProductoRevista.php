<?php
session_start();

// Verificar si la sesión está iniciada y si el rol es de administrador
if (!isset($_SESSION['id_persona']) || $_SESSION['rol'] != 3) {
    header("Location: ../index.php");
    exit();
}

include_once("../includes/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idRevista = $_POST['id_revista'];
    $idProducto = $_POST['id_producto'];
    $comisionable = $_POST['comisionable'];  // 'S' o 'N'

    // Procedimiento para asociar producto con revista
    $query = "BEGIN agregar_detalle_revista(:id_revista, :id_producto, :comisionable); END;";
    $stid = oci_parse($conexion, $query);
    oci_bind_by_name($stid, ":id_revista", $idRevista);
    oci_bind_by_name($stid, ":id_producto", $idProducto);
    oci_bind_by_name($stid, ":comisionable", $comisionable);

    // Ejecutar procedimiento
    oci_execute($stid);

    // Redirigir de vuelta a la gestión de revistas
    header("Location: gestionRevistas.php");
    exit();
}

// Obtener los productos disponibles
$query = "SELECT * FROM productos WHERE estado = 'A'"; // Solo productos activos
$stid = oci_parse($conexion, $query);
oci_execute($stid);

$productos = [];
while ($row = oci_fetch_assoc($stid)) {
    $productos[] = $row;
}
oci_free_statement($stid);

$idRevista = $_GET['id']; // Obtener ID de la revista desde el parámetro GET
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto a Revista</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
                <li class="nav-item"><a class="nav-link" href="gestionRevistas.php">Gestionar Revistas</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Agregar Producto a la Revista</h2>
        <form method="POST" action="agregarProductoRevista.php">
            <input type="hidden" name="id_revista" value="<?= $idRevista ?>">
            
            <div class="form-group">
                <label for="id_producto">Selecciona un Producto</label>
                <select name="id_producto" id="id_producto" class="form-control" required>
                    <option value="">Seleccione un producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= $producto['ID_PRODUCTO'] ?>"><?= $producto['NOMBRE'] ?> - <?= $producto['PRECIO'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="comisionable">¿Producto comisionable?</label>
                <select name="comisionable" id="comisionable" class="form-control" required>
                    <option value="N">No</option>
                    <option value="S">Sí</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Agregar Producto</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
