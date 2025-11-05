<?php
session_start();
define('BASE_URL', 'http://localhost/dival');
define('APP_PATH', __DIR__ . '/app');

echo "<h2>Debug de Rutas y Controladores</h2>";

// Simular diferentes URLs para probar
$testUrls = [
    'clientes',
    'clientes/index',
    'clientes/crear',
    'auth/login',
    'dashboard'
];

echo "<h3>Controladores Existentes:</h3>";
$controllers = ['AuthController', 'DashboardController', 'ClientesController'];
foreach ($controllers as $controller) {
    $file = APP_PATH . "/controllers/{$controller}.php";
    echo "{$controller}: " . (file_exists($file) ? '✅ EXISTE' : '❌ NO EXISTE') . "<br>";
    
    if (file_exists($file)) {
        require_once $file;
        echo "&nbsp;&nbsp;Clase: " . (class_exists($controller) ? '✅ EXISTE' : '❌ NO EXISTE') . "<br>";
        
        if (class_exists($controller)) {
            $methods = get_class_methods($controller);
            echo "&nbsp;&nbsp;Métodos: " . implode(', ', $methods) . "<br>";
        }
    }
    echo "<br>";
}

echo "<h3>Probar URLs:</h3>";
foreach ($testUrls as $url) {
    echo "<a href='" . BASE_URL . "/{$url}' target='_blank'>" . BASE_URL . "/{$url}</a><br>";
}

echo "<h3>Variables de Sesión:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";