<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Cheques</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="login-card">
                    <h4 class="text-center mb-4">Sistema Cambio de Cheques</h4>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="username" class="form-control" value="admin" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" value="admin123" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <small class="text-muted">Usuario: admin / Contraseña: admin123</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>