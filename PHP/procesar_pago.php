<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_id'])){
    die('Usuario no logueado');
}

// Obtener carrito
$sql = "SELECT c.id_carrito, p.id_producto, p.nombre_producto, p.precio, c.cantidad 
        FROM carrito c 
        JOIN productos p ON c.id_producto = p.id_producto 
        WHERE c.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$cart = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if(empty($cart)){
    die('Carrito vacío');
}

// Calcular total
$total = 0;
foreach($cart as $item) $total += $item['precio'] * $item['cantidad'];

// Registrar venta
$stmt = $conn->prepare("INSERT INTO ventas (id_usuario, total, metodo_pago) VALUES (?, ?, 'tarjeta')");
$stmt->bind_param("id", $_SESSION['user_id'], $total);
$stmt->execute();
$id_venta = $stmt->insert_id;

// Registrar detalle venta
$stmt2 = $conn->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
foreach($cart as $item){
    $subtotal = $item['precio'] * $item['cantidad'];
    $stmt2->bind_param("iiidd", $id_venta, $item['id_producto'], $item['cantidad'], $item['precio'], $subtotal);
    $stmt2->execute();
}

// Vaciar carrito
$stmt = $conn->prepare("DELETE FROM carrito WHERE id_usuario = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

echo "Pago simulado exitoso. Tu compra ha sido registrada con ID: $id_venta.";
