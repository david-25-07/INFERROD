<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Solo accesible para vendedores
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'vendedor') {
    header("Location: index.php");
    exit;
}

$conn = getConnection();
$result = $conn->query("SELECT * FROM productos ORDER BY id_producto ASC");
$productos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Vendedor - Inferrod</title>
<style>
body { font-family: Arial, sans-serif; background: #0a0e27; color: #e0e0e0; padding: 20px; }
h1 { color: #00ff41; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { padding: 10px; border: 1px solid #00ff41; text-align: center; }
input[type="number"], input[type="text"] { width: 100%; }
select { padding: 5px; }
.btn { padding: 8px 15px; background: #00ff41; color: #0a0e27; border: none; cursor: pointer; border-radius: 5px; }
.btn:hover { background: #00cc33; }
</style>
</head>
<body>
<h1>Panel de Vendedor</h1>
<form action="logout.php" method="post" style="text-align: right; margin-bottom: 10px;">
    <button type="submit" class="btn" style="background:#ff3535;">Cerrar Sesión</button>
</form>
<p>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>

<!-- Tabla de productos -->
<table>
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Categoría</th>
<th>Precio</th>
<th>Cantidad</th>
<th>Stock</th>
<th>Disponibilidad</th>
<th>Acción</th>
</tr>
</thead>
<tbody>
<?php foreach($productos as $p): ?>
<tr>
<td><?php echo $p['id_producto']; ?></td>
<td><input type="text" id="nombre-<?php echo $p['id_producto']; ?>" value="<?php echo htmlspecialchars($p['nombre_producto']); ?>"></td>
<td><input type="text" id="categoria-<?php echo $p['id_producto']; ?>" value="<?php echo htmlspecialchars($p['categoria']); ?>"></td>
<td><input type="number" step="0.01" id="precio-<?php echo $p['id_producto']; ?>" value="<?php echo $p['precio']; ?>"></td>
<td><input type="number" id="cantidad-<?php echo $p['id_producto']; ?>" value="<?php echo $p['cantidad']; ?>" min="0"></td>
<td><input type="number" id="stock-<?php echo $p['id_producto']; ?>" value="<?php echo $p['stock']; ?>" min="0"></td>
<td>
<select id="disponible-<?php echo $p['id_producto']; ?>">
<option value="1" <?php echo $p['disponible'] ? 'selected' : ''; ?>>Disponible</option>
<option value="0" <?php echo !$p['disponible'] ? 'selected' : ''; ?>>No disponible</option>
</select>
</td>
<td><button class="btn" onclick="guardarCambios(<?php echo $p['id_producto']; ?>)">Guardar</button></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<!-- Tabla Cambios Recientes -->
<h2>Cambios Recientes</h2>
<table>
<thead>
<tr>
<th>ID</th>
<th>Tabla</th>
<th>Acción</th>
<th>ID Registro</th>
<th>Descripción</th>
<th>Fecha</th>
</tr>
</thead>
<tbody>
<?php
$auditoria = $conn->query("SELECT id_auditoria, tabla, accion, id_registro, descripcion, fecha 
                           FROM auditoria 
                           WHERE tabla='productos' 
                           ORDER BY id_auditoria ASC"); // Ordenado por ID ascendente

$id_contador = 1; // Contador para mostrar IDs 1,2,3...
if($auditoria && $auditoria->num_rows > 0) {
    while($row = $auditoria->fetch_assoc()):
        if(empty($row['descripcion'])) continue; // Saltar descripciones vacías
?>
<tr>
<td><?php echo $id_contador++; ?></td>
<td><?php echo $row['tabla']; ?></td>
<td><?php echo $row['accion']; ?></td>
<td><?php echo $row['id_registro']; ?></td>
<td><small><?php echo htmlspecialchars($row['descripcion']); ?></small></td>
<td><?php echo $row['fecha']; ?></td>
</tr>
<?php endwhile; 
} else {
    echo "<tr><td colspan='6'>Sin registros recientes</td></tr>";
}
$conn->close();
?>
</tbody>
</table>

<script>
async function guardarCambios(id) {
    const formData = new FormData();
    formData.append('id_producto', id);
    formData.append('nombre_producto', document.getElementById('nombre-' + id).value);
    formData.append('precio', document.getElementById('precio-' + id).value);
    formData.append('categoria', document.getElementById('categoria-' + id).value);
    formData.append('cantidad', document.getElementById('cantidad-' + id).value);
    formData.append('stock', document.getElementById('stock-' + id).value);
    formData.append('disponible', document.getElementById('disponible-' + id).value);

    try {
        const res = await fetch('update_product.php', { method:'POST', body: formData });
        const data = await res.json();
        alert(data.message);
        if(data.success) location.reload();
    } catch(err) {
        console.error('Error:', err);
        alert('Error al guardar los cambios');
    }
}
</script>
</body>
</html>
