<?php
require_once 'config.php';
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Solo admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado.']);
    exit;
}

// Verificar método y datos
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
    exit;
}

$id_usuario = intval($_POST['id_usuario']);
$conn = getConnection();

// Evitar eliminar el admin principal (opcional)
if ($id_usuario === 1) {
    echo json_encode(['success' => false, 'message' => 'No puedes eliminar el administrador principal.']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error en la preparación: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $id_usuario);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el usuario.']);
}

$stmt->close();
$conn->close();
?>
