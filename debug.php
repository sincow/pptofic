<?php
// Mostrar todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h3>Información del Servidor</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "SAPI: " . php_sapi_name() . "<br>";

// Verificar extensiones necesarias
echo "<h3>Extensiones</h3>";
echo "PDO: " . (extension_loaded('pdo') ? '✓' : '✗') . "<br>";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? '✓' : '✗') . "<br>";
echo "OpenSSL: " . (extension_loaded('openssl') ? '✓' : '✗') . "<br>";

// Verificar permisos de carpetas
echo "<h3>Permisos de Carpetas</h3>";
$folders = array('app', 'config', 'assets/uploads');
foreach ($folders as $folder) {
    $writable = is_writable($folder) ? '✓' : '✗';
    echo "$folder: $writable<br>";
}

// Probar conexión a BD
echo "<h3>Base de Datos</h3>";
try {
    $pdo = new PDO("mysql:host=localhost", "u302138738_si001", "PitosClase#27*");
    echo "Conexión: ✓<br>";
} catch (Exception $e) {
    echo "Conexión: ✗ - " . $e->getMessage() . "<br>";
}