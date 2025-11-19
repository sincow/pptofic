<?php
date_default_timezone_set('America/Bogota');

// Detección del entorno
define('ENVIRONMENT', 'development'); // development, production

// Configuración según el entorno
if (ENVIRONMENT == 'development') {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
} else {
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
}


define('DB_HOST', 'mysql:host=localhost;dbname=u302138738_si001');
define('DB_NAME', 'u302138738_si001');
define('DB_USER', 'u302138738_si001');
define('DB_PASS', 'PitosClase#27*');
define('DB_CHARSET', 'utf8');

define('BD_SERVDEFUSU', '');
define('BD_USERDEFUSU', '');
define('BD_PASSDEFUSU', '');


define('APP_NAME', 'DIVAL');
define('APP_DESCRIPTION', 'Gestión completa ERP. Accede a utilidades financieras, contabilidad, inventario, compras, ventas, facturación, gestión de clientes y proveedores y mucho más dentro de un mismo sistema de gestión integrado.');
define('APP_ICON', 'assets/img/favicons/dival_tra.png');
define('APP_URL', 'http://localhost/dival/');
define('UPLOADS_PATH', '../storage/uploads/');
define('APP_PATH', 'app');
//define('CONFIG_PATH', '../config');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('MODELS_PATH', APP_PATH . '/models');

define("DATE_FORMAT", 'Y-m-d');
define("DATE_DISPLAY", 'yyyy-mm-dd');

define('CSRF_TOKEN_EXPIRATION', 3600);

define('slotMinTime', '09:00:00');
define('slotMaxTime', '19:00:00');
define('slotDuration', '00:30:00');

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}



function setCSRFToken() {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	$_SESSION['csrf_token_time'] = time() + CSRF_TOKEN_EXPIRATION;
	echo '<meta name="csrf-token" content="' . $_SESSION['csrf_token'] . '">';
	echo '<meta name="csrf-token_time" content="' . $_SESSION['csrf_token_time'] . '">';
}


// Función para generar y obtener token
function getCSRFToken() {
	return $_SESSION['csrf_token'];
}


// Función para validar token
function validateCSRFToken($token) {
	return isset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']) &&
		hash_equals($_SESSION['csrf_token'], $token) &&
		time() <= $_SESSION['csrf_token_time'];
}


// Generar token CSRF si no existe o ha expirado
if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_time']) || time() > $_SESSION['csrf_token_time']) {
	setCSRFToken();
	/*
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	$_SESSION['csrf_token_time'] = time() + CSRF_TOKEN_EXPIRATION;
	echo '<meta name="csrf-token" content="' . $_SESSION['csrf_token'] . '">';
	echo '<meta name="csrf-token_time" content="' . $_SESSION['csrf_token_time'] . '">';
	*/
}


//require_once 'app/core/Autoloader.php';
//require_once 'app/core/Controller.php';
//require_once 'config/languages.php';
//require_once 'app/core/Language.php';
//require_once 'app/controllers/ctrpets.php';
//require_once 'app/controllers/LanguageController.php';

// $lang = new Controller();
//var_dump($lang);
//$lenguaje = new Language();
//$lang = Language::getInstance();

// require_once 'app/core/Session.php';
// require_once 'app/core/FlashMessage.php';
// require_once 'app/core/Router.php';
// require_once 'app/core/Model.php';
// require_once 'app/core/View.php';
// require_once 'app/core/Database.php';
