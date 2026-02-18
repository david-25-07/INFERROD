<?php
require_once 'config.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_usuario'] ?? null;
    $nuevo_rol = $_POST['nuevo_rol'] ?? null;

    if (!$id || !$nuevo_rol) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }

    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE usuarios SET rol = ? WHERE id_usuario = ?");
    $stmt->bind_param("si", $nuevo_rol, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Rol actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar rol']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
