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
    $nombre = $_POST['nombre'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';
    $disponible = $_POST['disponible'] ?? 1;

    if (!$id || !$nombre) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
        exit;
    }

    $conn = getConnection();
    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión.']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE productos SET nombre_producto = ?, cantidad = ?, disponible = ? WHERE id_producto = ?");
    $stmt->bind_param("siii", $nombre, $cantidad, $disponible, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar producto.']);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
}
?>
