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

// Validar solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_producto'])) {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
    exit;
}

$id_producto = intval($_POST['id_producto']);
$conn = getConnection();

$stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $id_producto);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto.']);
}

$stmt->close();
$conn->close();
?>
