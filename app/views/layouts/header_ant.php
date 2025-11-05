<!DOCTYPE html>
<html lang="es">
<head>
    <!-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Sistema Cambio de Cheques'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> -->
    <style>
        /* body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        } */
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo $base_url; ?>/dashboard">
                <i class="fas fa-exchange-alt me-2"></i>Sistema Cheques
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user me-1"></i><?php echo $_SESSION["user_name"]; ?>
                </span>
                <!-- <a class="nav-link" href="<?php //echo $base_url; ?>/auth/signout"> -->
                <a class="nav-link" href="app/views/auth/signout">
                    <i class="fas fa-sign-out-alt me-1"></i>Salir
                </a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">