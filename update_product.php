<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Solo los vendedores pueden modificar productos
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'vendedor') {
    echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
    exit;
}

// Validar que se reciban todos los datos necesarios
$campos = ['id_producto','nombre_producto','precio','categoria','cantidad','stock','disponible'];
foreach($campos as $campo){
    if(!isset($_POST[$campo])){
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }
}

$id_producto   = intval($_POST['id_producto']);
$nombre        = trim($_POST['nombre_producto']);
$precio        = floatval($_POST['precio']);
$categoria     = trim($_POST['categoria']);
$cantidad      = intval($_POST['cantidad']);
$stock         = intval($_POST['stock']);
$disponible    = intval($_POST['disponible']);

$conn = getConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Error en la conexión a la base de datos']);
    exit;
}

// Obtener datos anteriores antes de actualizar (para la comparación)
$getOld = $conn->prepare("SELECT nombre_producto, precio, categoria, cantidad, stock, disponible FROM productos WHERE id_producto = ?");
$getOld->bind_param("i", $id_producto);
$getOld->execute();
$oldData = $getOld->get_result()->fetch_assoc();
$getOld->close();

// Actualizar el producto
$sql = "UPDATE productos 
        SET nombre_producto=?, precio=?, categoria=?, cantidad=?, stock=?, disponible=? 
        WHERE id_producto=?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conn->error]);
    exit;
}

$stmt->bind_param('sdsiiii', $nombre, $precio, $categoria, $cantidad, $stock, $disponible, $id_producto);

if ($stmt->execute()) {
    // Construir descripción SOLO con los campos que cambiaron
    $cambios = [];
    if ($oldData['nombre_producto'] !== $nombre)
        $cambios[] = "nombre de '{$oldData['nombre_producto']}' a '$nombre'";
    if ($oldData['precio'] != $precio)
        $cambios[] = "precio de {$oldData['precio']} a $precio";
    if ($oldData['categoria'] !== $categoria)
        $cambios[] = "categoría de '{$oldData['categoria']}' a '$categoria'";
    if ($oldData['cantidad'] != $cantidad)
        $cambios[] = "cantidad de {$oldData['cantidad']} a $cantidad";
    if ($oldData['stock'] != $stock)
        $cambios[] = "stock de {$oldData['stock']} a $stock";
    if ($oldData['disponible'] != $disponible)
        $cambios[] = "disponibilidad de " . ($oldData['disponible'] ? 'Sí' : 'No') . " a " . ($disponible ? 'Sí' : 'No');

    $descripcion = empty($cambios) 
        ? "No se detectaron cambios." 
        : implode(", ", $cambios) . ".";

    // Registrar en la tabla auditoría
    $accion = 'UPDATE';
    $tabla = 'productos';
    $auditoria = $conn->prepare("INSERT INTO auditoria (tabla, accion, id_registro, descripcion) VALUES (?, ?, ?, ?)");
    if ($auditoria) {
        $auditoria->bind_param("ssis", $tabla, $accion, $id_producto, $descripcion);
        $auditoria->execute();
        $auditoria->close();
    }

    echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
