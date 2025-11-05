<?php

// ===============================
//  Configuración inicial
// ===============================

// Rutas base del proyecto (ajusta si tu hosting no permite carpetas fuera de public_html)
require_once 'config/config.php';
//define('BASE_PATH', '../'.__DIR__);
define('BASE_PATH', './');
//define('CONFIG_PATH', BASE_PATH . 'config');
define('CONFIG_PATH', 'config');


// Cargar configuración de la BD
require_once CONTROLLERS_PATH . "/ctrtemplate.php";
require_once CONTROLLERS_PATH . "/admon/ctrgeneral.php";
require_once CONTROLLERS_PATH . '/ctrauth.php';
require_once MODELS_PATH . "/Usuario.php";
require_once MODELS_PATH . "/admon/mdlgeneral.php";
require_once CONFIG_PATH . "/mdlpermission.php";
require_once CONFIG_PATH . "/Database.php";

/*
session_start();


// Autocarga simple (para modelos y controladores)
spl_autoload_register(function($class) {
    $paths = [APP_PATH . '/controllers/', APP_PATH . '/models/', APP_PATH . '/core/'];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// ===============================
//  Router básico
// ===============================
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Si está logueado y pide la raíz → Dashboard
if (isset($_SESSION['user_id']) && ($uri === '/' || $uri === '/index.php')) {
    require_once APP_PATH . '/controllers/DashboardController.php';
    $controller = new DashboardController();
    $controller->index();
    exit;
}

// Si no está logueado → ir a login
if (!isset($_SESSION['user_id']) && ($uri === '/' || $uri === '/index.php')) {
    require_once APP_PATH . '/controllers/ctrauth.php';
    $controller = new AuthController();
    $controller->login();
    exit;
}

// Rutas adicionales (ejemplo: /citas/listar)
$parts = explode('/', trim($uri, '/'));
$controllerName = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'DashboardController';
$action = $parts[1] ?? 'index';

$controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $action)) {
            call_user_func([$controller, $action]);
            exit;
        }
    }
}

// Si no existe la ruta
http_response_code(404);
echo "Página no encontrada";
*/

$Template = new TemplateController();
$Template->ctrTemplate();
