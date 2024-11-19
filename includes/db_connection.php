<?php
// Configuración de conexión a la base de datos Oracle
$host = "localhost";   // Cambia esto por el host de tu base de datos
$port = "1521";        // Puerto por defecto de Oracle
$sid = "XE";           // SID de tu base de datos
$username = "BASES2";  // Usuario de la base de datos
$password = "1234";     // Contraseña de la base de datos

// Establecer el parámetro NLS_LANG para asegurar que se use UTF-8
putenv('NLS_LANG=SPANISH_SPAIN.AL32UTF8');

// Conectar a Oracle usando OCI8
$conexion = oci_connect($username, $password, "//{$host}:{$port}/{$sid}");
if (!$conexion) {
    $e = oci_error();
    error_log("Error de conexión: " . $e['message'], 3, 'error_log.txt');
    echo "Hubo un problema al conectar con la base de datos.";
    exit;
}
?>

