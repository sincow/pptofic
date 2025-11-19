<?php
/**
 * Genera un certificado digital PKCS#12 (.p12) autofirmado
 * para uso interno en la aplicación 3PL (packer / QC / warehouse).
 *
 * Requisitos:
 * - PHP con extensión OpenSSL habilitada.
 * - Carpeta storage/certs/ con permisos de escritura.
 */

header('Content-Type: application/json');

// === CONFIGURACIÓN BASE ===
$entity = [
    'C'  => $_POST['country'] ?? 'CA',                   // Country code
    'ST' => $_POST['state'] ?? 'Ontario',                // State/Province
    'L'  => $_POST['city'] ?? 'Vaughan',                 // City
    'O'  => $_POST['organization'] ?? 'Polylogik 3PL',   // Organization
    'OU' => $_POST['unit'] ?? 'Warehouse',               // Organizational Unit
    'CN' => $_POST['common_name'] ?? 'packer.polylogik.com', // Common Name (hostname or user)
    'emailAddress' => $_POST['email'] ?? 'admin@polylogik.com'
];

// Contraseña del archivo .p12
$p12_password = $_POST['password'] ?? bin2hex(random_bytes(6));

// Ruta donde se guardarán los certificados
$cert_dir = __DIR__ . '/../../storage/certs/';
if (!is_dir($cert_dir)) {
    mkdir($cert_dir, 0775, true);
}

// Nombre base del certificado
$cert_name = strtolower(str_replace(' ', '_', $entity['CN'])) . '_' . date('Ymd_His');

// === GENERACIÓN DE CLAVE PRIVADA ===
$privkey = openssl_pkey_new([
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
]);

// === CREACIÓN DEL CERTIFICADO AUTOFIRMADO ===
$csr = openssl_csr_new($entity, $privkey);
$cert = openssl_csr_sign($csr, null, $privkey, 1095, ['digest_alg' => 'sha256']); // válido por 3 años

// === EXPORTACIÓN DE ARCHIVOS ===
$pem_key_file = $cert_dir . $cert_name . '_key.pem';
$pem_cert_file = $cert_dir . $cert_name . '_cert.pem';
$p12_file = $cert_dir . $cert_name . '.p12';

// Guardar los archivos individuales
openssl_pkey_export_to_file($privkey, $pem_key_file);
openssl_x509_export_to_file($cert, $pem_cert_file);

// Crear archivo .p12
$p12_exported = openssl_pkcs12_export_to_file($cert, $p12_file, $privkey, $p12_password);

if (!$p12_exported) {
    echo json_encode(['success' => false, 'error' => 'No se pudo generar el archivo .p12']);
    exit;
}

// Calcular fingerprint SHA256
$fingerprint = strtoupper(implode(':', str_split(hash_file('sha256', $p12_file), 2)));

// === SALIDA ===
$response = [
    'success' => true,
    'message' => 'Certificado generado correctamente',
    'cert_name' => $cert_name,
    'country' => $entity['C'],
    'organization' => $entity['O'],
    'common_name' => $entity['CN'],
    'p12_file' => basename($p12_file),
    'password' => $p12_password,
    'sha256_fingerprint' => $fingerprint,
    'valid_from' => date('Y-m-d'),
    'valid_to' => date('Y-m-d', strtotime('+3 years'))
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>
