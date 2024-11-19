<?php
// Incluir el archivo de conexión a la base de datos
include_once '../includes/db_connection.php';

// Intentar la conexión y verificar errores
if (!$conexion) {
    $error = oci_error();
    die("Conexión fallida: " . $error['message']);
}

try {
    // Llamada al procedimiento para cargar las ciudades
    $cursorCiudades = oci_new_cursor($conexion);
    $stidCiudades = oci_parse($conexion, 'BEGIN CARGAR_CIUDADES(:cursor); END;');
    oci_bind_by_name($stidCiudades, ':cursor', $cursorCiudades, -1, OCI_B_CURSOR);
    oci_execute($stidCiudades);
    oci_execute($cursorCiudades);
    oci_fetch_all($cursorCiudades, $ciudades, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    // Llamada al procedimiento para cargar los roles
    $cursorRoles = oci_new_cursor($conexion);
    $stidRoles = oci_parse($conexion, 'BEGIN CARGAR_ROLES(:cursor); END;');
    oci_bind_by_name($stidRoles, ':cursor', $cursorRoles, -1, OCI_B_CURSOR);
    oci_execute($stidRoles);
    oci_execute($cursorRoles);
    oci_fetch_all($cursorRoles, $roles, null, null, OCI_FETCHSTATEMENT_BY_ROW);
} catch (Exception $e) {
    die("Error al ejecutar procedimientos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Formulario de Registro</h2>
    <form action="../logic/registroLogic.php" method="POST">
        <!-- Ciudad -->
        <div class="form-group">
            <label for="ciudad">Ciudad</label>
            <select name="ciudad" class="form-control" required>
                <option value="">Seleccionar Ciudad</option>
                <?php 
                if ($ciudades) {
                    foreach ($ciudades as $ciudad) { ?>
                        <option value="<?php echo $ciudad['ID_CIUDAD']; ?>"><?php echo $ciudad['NOMBRE']; ?></option>
                    <?php }
                } ?>
            </select>
        </div>

        <!-- Cédula -->
        <div class="form-group">
            <label for="cedula">Cédula</label>
            <input type="text" name="cedula" class="form-control" required>
        </div>

        <!-- Fecha de Nacimiento -->
        <div class="form-group">
            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control" required>
        </div>

        <!-- Teléfono -->
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" class="form-control" required>
        </div>

        <!-- Primer Nombre -->
        <div class="form-group">
            <label for="primer_nombre">Primer Nombre</label>
            <input type="text" name="primer_nombre" class="form-control" required>
        </div>

        <!-- Segundo Nombre -->
        <div class="form-group">
            <label for="segundo_nombre">Segundo Nombre</label>
            <input type="text" name="segundo_nombre" class="form-control">
        </div>

        <!-- Primer Apellido -->
        <div class="form-group">
            <label for="primer_apellido">Primer Apellido</label>
            <input type="text" name="primer_apellido" class="form-control" required>
        </div>

        <!-- Segundo Apellido -->
        <div class="form-group">
            <label for="segundo_apellido">Segundo Apellido</label>
            <input type="text" name="segundo_apellido" class="form-control">
        </div>

        <!-- Correo -->
        <div class="form-group">
            <label for="correo">Correo Electrónico</label>
            <input type="email" name="correo" class="form-control" required>
        </div>

        <!-- Contraseña -->
        <div class="form-group">
            <label for="contrasena">Contraseña</label>
            <input type="password" name="contrasena" class="form-control" required>
        </div>

        <!-- Rol -->
        <div class="form-group">
            <label for="rol">Rol</label>
            <select name="rol" class="form-control" required>
                <option value="">Seleccionar Rol</option>
                <?php 
                if ($roles) {
                    foreach ($roles as $rol) { ?>
                        <option value="<?php echo $rol['ID_ROL']; ?>"><?php echo $rol['NOMBRE']; ?></option>
                    <?php }
                } ?>
            </select>
        </div>

        <!-- Estado -->
        <div class="form-group">
            <label for="estado">Estado</label>
            <select name="estado" class="form-control" required>
                <option value="activo">activo</option>
                <option value="inactivo">inactivo</option>
            </select>
        </div>

        <!-- Botón de Enviar -->
        <button type="submit" class="btn btn-primary btn-block">Registrar</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
oci_free_statement($stidCiudades);
oci_free_statement($stidRoles);
oci_free_cursor($cursorCiudades);
oci_free_cursor($cursorRoles);
oci_close($conexion);
?>









