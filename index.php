<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revistas Multinivel JJ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
        }
        .section-title {
            font-size: 2rem;
            font-weight: bold;
            color: #5A5A5A;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .card-body {
            background-color: #ffffff;
            padding: 20px;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1.1rem;
            text-align: center;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
        .navbar-custom {
            background-color: #007bff;
        }
        .navbar-custom .nav-link {
            color: white;
        }
        .navbar-custom .nav-link:hover {
            color: #ffeb3b;
        }
    </style>
</head>
<body>

<!-- Barra de Navegación -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="#">Revistas JJ</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="pages/login.php">Iniciar Sesión</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pages/registro.php">Registrarse</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Mostrar el mensaje de éxito si el parámetro 'registro' está en la URL -->
<?php if (isset($_GET['registro']) && $_GET['registro'] == 'exitoso'): ?>
    <div class="alert alert-success text-center" role="alert">
        ¡Registro exitoso! Ahora puedes iniciar sesión.
    </div>
<?php endif; ?>

<div class="container mt-5">

    <!-- Título Principal -->
    <header class="text-center mb-5">
        <h1 class="display-4">Bienvenido a Revistas Multinivel JJ</h1>
        <p class="lead">La empresa que revoluciona la venta de productos a través de la venta de revistas y el modelo multinivel.</p>
    </header>

    <!-- Sección de Navegación Rápida -->
    <section>
        <div class="row text-center mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Catálogo de Productos
                    </div>
                    <div class="card-body">
                        <p>Consulta el catálogo completo de productos disponibles para la venta.</p>
                        <a href="catalogo.php" class="btn btn-custom">Ver Catálogo</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Colaboradores
                    </div>
                    <div class="card-body">
                        <p>Accede a la lista de colaboradores y gestiona tus datos de vendedor.</p>
                        <a href="pages/login.php" class="btn btn-custom">Ver Colaboradores</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Clientes
                    </div>
                    <div class="card-body">
                        <p>Consulta los clientes registrados y sus compras realizadas.</p>
                        <a href="pages/login.php" class="btn btn-custom">Ver Clientes</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Registro de Colaboradores -->
    <section class="mb-5">
        <h2 class="section-title text-center">¿Quieres ser Colaborador?</h2>
        <p class="text-center">Únete a nosotros y empieza a ganar comisiones por cada venta realizada. ¡Es fácil y rápido!</p>
        <div class="text-center">
            <a href="pages/registro.php" class="btn btn-custom">Regístrate como Colaborador(Empleado)</a>
        </div>
    </section>

    <!-- Sección de Panel de Control (solo para admins o gerentes) -->
    <section class="mb-5">
        <h2 class="section-title text-center">Panel de Control Administrativo</h2>
        <p class="text-center">Accede a herramientas administrativas para gestionar productos, pedidos y más.</p>
        <div class="text-center">
            <a href="pages/login.php" class="btn btn-custom">Ir al Panel</a>
        </div>
    </section>

    <!-- Pie de Página -->
    <footer class="footer">
        <p>&copy; 2024 Revistas Multinivel JJ | Todos los derechos reservados</p>
        <p>Dirección de la empresa | Tel: 123-456-789 | Email: contacto@revistasjj.com</p>
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



