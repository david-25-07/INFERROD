<?php
// admin_reset_passwords.php
require_once 'config.php';
session_start();

// Asegúrate de proteger este archivo: solo admin puede ejecutarlo.
// Ejemplo simple (mejor reemplazar con verificación real):
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo "Acceso denegado.";
    exit;
}

$conn = getConnection();

// Usuarios y contraseñas iniciales (cámbialas).
$replacements = [
    'admin@techhardware.com' => 'AdminNuevo2025!',
    'vendedor@techhardware.com' => 'VendedorNuevo2025!',
    'cliente@techhardware.com' => 'ClienteNuevo2025!'
];

$stmt = $conn->prepare("UPDATE usuarios SET contrasena = ?, force_password_change = 1 WHERE correo = ?");
foreach ($replacements as $correo => $plain) {
    $hash = password_hash($plain, PASSWORD_DEFAULT);
    $stmt->bind_param("ss", $hash, $correo);
    $stmt->execute();
    echo "contrasena para $correo actualizada.<br>";
}

echo "Listo. Los usuarios deberán cambiar su contrasena al ingresar.";
