<?php
// Configuración básica
session_start();

// Definir constantes de rutas
define('BASE_URL', 'http://localhost/dival');
define('ASSETS_URL', BASE_URL . '/assets');
define('APP_PATH', __DIR__ . '/app');
define('ROOT_PATH', __DIR__);

// ✅ CARGAR CONFIGURACIÓN DE BD PRIMERO
require_once __DIR__ . '/config/database.php';

// Autocargar clases básicas
spl_autoload_register(function($class) {
    $file = __DIR__ . '/app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Router MEJORADO
$url = isset($_GET['url']) ? $_GET['url'] : 'dashboard';
$url = rtrim($url, '/');
$urlParts = explode('/', $url);

$controller = isset($urlParts[0]) ? $urlParts[0] : 'dashboard';
$action = isset($urlParts[1]) ? $urlParts[1] : 'index';
$params = array_slice($urlParts, 2);

// Convertir kebab-case a CamelCase para el controlador
$controller = str_replace(' ', '', ucwords(str_replace('-', ' ', $controller)));
$controllerClass = $controller . 'Controller';

// Verificar si el usuario está logueado (excepto para auth)
if ($controller != 'Auth' && empty($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/auth/login');
    exit;
}

// Incluir controlador
$controllerFile = APP_PATH . '/controllers/' . $controllerClass . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    if (class_exists($controllerClass)) {
        $controllerInstance = new $controllerClass();
        
        if (method_exists($controllerInstance, $action)) {
            // Llamar al método con parámetros
            call_user_func_array([$controllerInstance, $action], $params);
        } else {
            $this->showError("Método $action no existe en $controllerClass");
        }
    } else {
        $this->showError("Controlador $controllerClass no existe");
    }
} else {
    $this->showError("Página no encontrada: $controllerFile");
}

function showError($message) {
    http_response_code(404);
    if (defined('APP_DEBUG') && APP_DEBUG) {
        die("Error: " . $message);
    } else {
        // Vista de error amigable
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Página no encontrada</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                h1 { color: #333; }
                p { color: #666; }
                .debug { background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 5px; text-align: left; }
            </style>
        </head>
        <body>
            <h1>Página no encontrada</h1>
            <p>La página que buscas no existe.</p>
            <a href='" . BASE_URL . "'>Volver al inicio</a>
            
            " . (defined('APP_DEBUG') && APP_DEBUG ? "<div class='debug'><strong>Debug:</strong> $message</div>" : "") . "
        </body>
        </html>";
    }
    exit;
}