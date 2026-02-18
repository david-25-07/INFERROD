<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: iniciar_sesion.php");
    exit;
}

// Obtener rol
$stmt = $conn->prepare("SELECT rol FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$rol = $stmt->get_result()->fetch_assoc()['rol'];

// Solo admin o vendedor pueden ver
if($rol != 'admin' && $rol != 'vendedor'){
    die("No tienes permisos para acceder a esta página.");
}

// Consultar ventas con detalle
$sql = "SELECT v.id_venta, v.total, v.metodo_pago, v.fecha_venta, u.nombre_usuario, c.nombre AS cliente
        FROM ventas v
        LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
        LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
        ORDER BY v.fecha_venta DESC";

$result = $conn->query($sql);
$ventas = $result->fetch_all(MYSQLI_ASSOC);
?>

<h2>Movimientos de Ventas</h2>

<table border="1" cellpadding="5" cellspacing="0">
<tr>
<th>ID Venta</th>
<th>Usuario</th>
<th>Cliente</th>
<th>Total</th>
<th>Método Pago</th>
<th>Fecha</th>
<th>Detalle</th>
</tr>

<?php foreach($ventas as $venta): ?>
<tr>
<td><?= $venta['id_venta'] ?></td>
<td><?= htmlspecialchars($venta['nombre_usuario']) ?></td>
<td><?= htmlspecialchars($venta['cliente'] ?? 'No asignado') ?></td>
<td>$<?= number_format($venta['total'],0) ?></td>
<td><?= $venta['metodo_pago'] ?></td>
<td><?= $venta['fecha_venta'] ?></td>
<td>
    <ul>
    <?php
    $stmt2 = $conn->prepare("SELECT p.nombre_producto, dv.cantidad, dv.subtotal 
                             FROM detalle_venta dv 
                             JOIN productos p ON dv.id_producto = p.id_producto 
                             WHERE dv.id_venta = ?");
    $stmt2->bind_param("i", $venta['id_venta']);
    $stmt2->execute();
    $detalle = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($detalle as $d){
        echo "<li>{$d['nombre_producto']} x{$d['cantidad']} = $".number_format($d['subtotal'],0)."</li>";
    }
    ?>
    </ul>
</td>
</tr>
<?php endforeach; ?>
</table>
