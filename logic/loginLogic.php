<?php

include '../includes/db_connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Asegúrate de iniciar la sesión al principio

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $id_persona = null;
    $rol = null;
    $contrasena_almacenada = null;

    $stid = oci_parse($conexion, 'BEGIN verificar_login(:correo, :id_persona, :rol, :contrasena); END;');
    oci_bind_by_name($stid, ':correo', $correo);
    oci_bind_by_name($stid, ':id_persona', $id_persona, -1, SQLT_INT);
    oci_bind_by_name($stid, ':rol', $rol, -1, SQLT_INT);
    oci_bind_by_name($stid, ':contrasena', $contrasena_almacenada, 1000);

    $execute_result = oci_execute($stid);

    if ($execute_result) {
        if (password_verify($_POST['contrasena'], $contrasena_almacenada)) {
            $_SESSION['id_persona'] = $id_persona;
            $_SESSION['rol'] = $rol;

            if ($rol == 1) {
                header("Location: ../pages/cliente.php");
            } elseif ($rol == 2) {
                header("Location: ../pages/empleado.php");
            } elseif ($rol == 3) {
                header("Location: ../pages/administrador.php");
            }
            exit();
        } else {
            $_SESSION['login_error'] = "Credenciales incorrectas. Por favor, inténtalo de nuevo.";
            $_SESSION['correo_ingresado'] = $correo; // Guardar el correo ingresado
            header("Location: ../pages/login.php");
            exit();
        }
    } else {
        $e = oci_error($stid);
        $_SESSION['login_error'] = "Error al procesar el inicio de sesión: " . $e['message'];
        header("Location: ../pages/login.php");
        exit();
    }

    oci_free_statement($stid);
    oci_close($conexion);
}
?>





