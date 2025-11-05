<?php
// Configuración de base de datos

define('DB_HOSTSITE','localhost');
define('DB_USERSITE','u302138738_sinco');
define('DB_PASSSITE','PitosClase#27*');
//define('DB_SERV','mysql:host=localhost;dbname=');
define('DB_NAMESITE', 'u302138738_sinco');
define('DB_CHARSET', 'utf8');


define('DB_HOST', 'localhost');
define('DB_USER', 'u302138738_si001');
define('DB_PASS', 'PitosClase#27*');
define('DB_NAME', 'u302138738_si001');
// define('DB_CHARSET', 'utf8');

// Intentar diferentes configuraciones de conexión
$dbConfigs = [
   [
      'host' => 'localhost',
      'port' => '3306'
   ],
   [
      'host' => '127.0.0.1', 
      'port' => '3306'
   ]
];