<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Solo vendedor o admin
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['vendedor', 'admin'])) {
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_producto'] ?? '';

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID de producto faltante.']);
        exit;
    }

    $conn = getConnection();
    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión.']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar producto.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
}
?>
