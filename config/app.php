<?php
// Configuración de la aplicación
define('APP_NAME', 'DIVAL');
define('APP_VERSION', '1.0');
define('APP_DEBUG', true);

// URLs - IMPORTANTE: Cambiar por tu dominio real
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . '://' . $host;

define('BASE_URL', $base_url);
define('ASSETS_URL', BASE_URL . '/assets');

// Rutas físicas
define('UPLOAD_PATH', ROOT_PATH . '/assets/uploads/cheques/');
define('UPLOAD_URL', ASSETS_URL . '/uploads/cheques/');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

// Mostrar errores solo en desarrollo
if (APP_DEBUG) {
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
} else {
   error_reporting(0);
   ini_set('display_errors', 0);
}