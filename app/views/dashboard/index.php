<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background-color: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .nav-link {
            color: #adb5bd;
        }
        .nav-link:hover {
            color: white;
        }
        .nav-link.active {
            color: white;
            background-color: #495057;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="p-3">
            <h4 class="text-center">Sistema Cheques</h4>
            <hr>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link active" href="<?php echo BASE_URL; ?>/dashboard">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a class="nav-link" href="clientes">
                <i class="fas fa-users me-2"></i>Clientes
            </a>
            <a class="nav-link" href="#">
                <i class="fas fa-money-check me-2"></i>Cambiar Cheque
            </a>
            <a class="nav-link" href="#">
                <i class="fas fa-piggy-bank me-2"></i>Consignaciones
            </a>
            <a class="nav-link" href="#">
                <i class="fas fa-chart-bar me-2"></i>Reportes
            </a>
            <a class="nav-link" href="<?php echo BASE_URL; ?>/auth/logout">
                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Dashboard</h1>
            <div class="text-end">
                <span class="text-muted">Bienvenido, </span>
                <strong><?php echo $_SESSION["user_name"]; ?></strong>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Clientes</h5>
                                <h3>0</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Cheques Hoy</h5>
                                <h3>0</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-money-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Por Cobrar</h5>
                                <h3>$0</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-hand-holding-usd fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Consignaciones</h5>
                                <h3>0</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-piggy-bank fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Sistema de Gestión de Cambio de Cheques</h5>
                <p class="card-text">Bienvenido al sistema de gestión integral para el servicio de cambio de cheques.</p>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6>Módulos Disponibles:</h6>
                        <ul>
                            <li>Gestión de Clientes</li>
                            <li>Cambio de Cheques</li>
                            <li>Consignaciones</li>
                            <li>Pagos e Intereses</li>
                            <li>Reportes y Estadísticas</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Acciones Rápidas:</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary">Nuevo Cliente</button>
                            <button class="btn btn-outline-success">Cambiar Cheque</button>
                            <button class="btn btn-outline-info">Realizar Consignación</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>