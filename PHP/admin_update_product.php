<?php
require_once 'config.php';
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit;
}

$conn = getConnection();

$id = $_POST['id_producto'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$cantidad = $_POST['cantidad'] ?? 0;
$disponible = $_POST['disponible'] ?? 1;

if (empty($id) || empty($nombre)) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

$stmt = $conn->prepare("UPDATE productos SET nombre_producto=?, cantidad=?, disponible=? WHERE id_producto=?");
$stmt->bind_param("siii", $nombre, $cantidad, $disponible, $id);
echo json_encode(['success' => $stmt->execute(), 'message' => 'Producto actualizado']);
?>
