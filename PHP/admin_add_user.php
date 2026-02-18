<?php
require_once 'config.php';
header('Content-Type: application/json');
session_start();

// Solo administrador
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acceso denegado.']);
    exit;
}

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

// Recibir datos
$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$password = trim($_POST['password'] ?? '');
$rol = trim($_POST['rol'] ?? 'cliente');

// Validar campos
if (empty($nombre) || empty($correo) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
    exit;
}

$conn = getConnection();

// Verificar si el correo ya existe
$check = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
$check->bind_param("s", $correo);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'El correo ya está registrado.']);
    exit;
}
$check->close();

// Encriptar contraseña
$hash = password_hash($password, PASSWORD_DEFAULT);

// Insertar nuevo usuario
$stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error al preparar consulta: ' . $conn->error]);
    exit;
}
$stmt->bind_param("ssss", $nombre, $correo, $hash, $rol);
$ok = $stmt->execute();

if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Usuario agregado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al agregar usuario: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
