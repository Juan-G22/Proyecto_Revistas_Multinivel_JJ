<?php
session_start(); // Iniciar sesión

// Verificar si la sesión está iniciada y si el rol es de administrador
if (!isset($_SESSION['id_persona']) || $_SESSION['rol'] != 3) {
    header("Location: ../index.php"); // Redirigir al índice si no tiene acceso
    exit();
}

// Incluir el archivo de conexión a la base de datos
include_once("../includes/db_connection.php");

// Verificar si se ha recibido un ID de revista
if (isset($_GET['id'])) {
    $id_revista = $_GET['id'];

    // Consultar los datos de la revista
    $query = "SELECT * FROM revistas WHERE id_revista = :id_revista";
    $stid = oci_parse($conexion, $query);
    oci_bind_by_name($stid, ":id_revista", $id_revista);

    oci_execute($stid);

    // Obtener los resultados
    $revista = oci_fetch_assoc($stid);

    // Liberar recursos
    oci_free_statement($stid);
} else {
    // Redirigir si no se pasa un ID
    header("Location: gestionRevistas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Revista - Revistas Multinivel JJ</title>
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
                <li class="nav-item"><a class="nav-link" href="gestionRevistas.php">Gestionar Revistas</a></li>
                <li class="nav-item"><a class="nav-link" href="verVentas.php">Ver Ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="verColaboradores.php">Ver Colaboradores</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Editar Revista</h2>
        <form action="procesarEdicionRevista.php" method="post">
            <input type="hidden" name="id_revista" value="<?= $revista['ID_REVISTA']; ?>">

            <div class="form-group">
                <label for="fecha_publicacion">Fecha de Publicación</label>
                <input type="date" name="fecha_publicacion" id="fecha_publicacion" class="form-control" value="<?= date('Y-m-d', strtotime($revista['FECHA_PUBLICACION'])); ?>" required>
            </div>

            <div class="form-group">
                <label for="numero_edicion">Número de Edición</label>
                <input type="text" name="numero_edicion" id="numero_edicion" class="form-control" value="<?= $revista['NUMERO_EDICION']; ?>" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control"><?= $revista['DESCRIPCION']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="estado">Estado</label>
                <select name="estado" id="estado" class="form-control" required>
                    <option value="A" <?= $revista['ESTADO'] == 'A' ? 'selected' : ''; ?>>Activo</option>
                    <option value="I" <?= $revista['ESTADO'] == 'I' ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

